<?php

namespace App\Http\Controllers;

use App\Models\CafeItem;
use App\Models\CafeOrder;
use App\Models\CafeOrderItem;
use App\Models\CafeTableReservation;
use App\Models\PortalStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CafeController extends Controller
{
    public function index(Request $request)
    {
        $categories = config('cafe.categories');

        $items = CafeItem::query()->orderBy('category')->orderBy('name')->get();
        $allMenuItems = $items->map(function (CafeItem $item) use ($categories): array {
            $isDiscounted = $item->discount_price !== null && $item->discount_price < $item->price;

            return [
                'id' => $item->id,
                'name' => $item->name,
                'category' => $item->category,
                'categoryLabel' => $categories[$item->category] ?? ucfirst($item->category),
                'price' => (float) ($isDiscounted ? $item->discount_price : $item->price),
                'originalPrice' => $isDiscounted ? (float) $item->price : null,
                'description' => $item->description,
                'image' => $item->image_url ?: '🍽️',
                'available' => $item->is_available,
                'specialOffer' => $item->is_special_offer,
            ];
        })->values();

        $menuItems = $allMenuItems->reject(fn (array $item) => $item['specialOffer'])->values();
        $menuCategories = $menuItems->pluck('category')
            ->unique()
            ->mapWithKeys(fn (string $category) => [$category => $categories[$category] ?? ucfirst($category)]);
        $itemsByName = $items->keyBy('name');
        $weeklyMenu = collect(config('cafe.weekly_menu'))->map(function (array $day, int $index) use ($itemsByName): array {
            $meals = collect($day['meals'])->map(function (array $meal) use ($itemsByName): array {
                if (isset($meal['products'])) {
                    $meal['products'] = collect($meal['products'])->map(function (string $name) use ($itemsByName): ?array {
                        $item = $itemsByName->get($name);

                        return $item ? [
                            'id' => $item->id,
                            'name' => $item->name,
                            'price' => (float) ($item->discount_price !== null && $item->discount_price < $item->price
                                ? $item->discount_price
                                : $item->price),
                            'available' => $item->is_available,
                            'image' => $item->image_url ?: '🍽️',
                        ] : null;
                    })->filter()->values();

                    return $meal;
                }

                $item = $itemsByName->get($meal['name']);

                return array_merge($meal, [
                    'id' => $item?->id,
                    'available' => $item?->is_available ?? false,
                    'price' => $item
                        ? (float) ($item->discount_price !== null && $item->discount_price < $item->price
                            ? $item->discount_price
                            : $item->price)
                        : $meal['price'],
                    'image' => $item?->image_url ?: '🍱',
                ]);
            })->values();

            return [
                'day' => $day['day'],
                'special' => $day['special'],
                'open' => $index === 0,
                'meals' => $meals,
            ];
        })->values();

        $reservedTables = CafeTableReservation::query()
            ->where('expires_at', '>', now())
            ->get(['floor', 'table_number'])
            ->groupBy('floor')
            ->map(fn ($reservations) => $reservations->pluck('table_number')->values());

        $isCafeAdmin = Auth::check() && Auth::user()->role === 'cafe_admin';
        $staff = PortalStaff::query()->where('unit', 'cafe')->orderBy('name')->get();
        $ordersQuery = CafeOrder::with('items')->latest();
        $cafeSales = null;

        if ($isCafeAdmin) {
            $orders = $ordersQuery->limit(50)->get();
            $activeSalesOrders = CafeOrder::query()->where('status', '!=', 'cancelled');
            $todaySalesOrders = (clone $activeSalesOrders)->whereDate('created_at', today());

            $cafeSales = [
                'total_income' => (clone $activeSalesOrders)->sum('total_amount'),
                'today_income' => (clone $todaySalesOrders)->sum('total_amount'),
                'order_count' => (clone $activeSalesOrders)->count(),
                'today_order_count' => (clone $todaySalesOrders)->count(),
                'items_sold' => CafeOrderItem::query()
                    ->whereHas('order', fn ($query) => $query->where('status', '!=', 'cancelled'))
                    ->sum('quantity'),
                'top_items' => DB::table('cafe_order_items')
                    ->join('cafe_orders', 'cafe_orders.id', '=', 'cafe_order_items.cafe_order_id')
                    ->where('cafe_orders.status', '!=', 'cancelled')
                    ->select(
                        'cafe_order_items.name',
                        DB::raw('SUM(cafe_order_items.quantity) as quantity_sold'),
                        DB::raw('SUM(cafe_order_items.subtotal) as item_sales')
                    )
                    ->groupBy('cafe_order_items.name')
                    ->orderByDesc('quantity_sold')
                    ->orderBy('cafe_order_items.name')
                    ->limit(5)
                    ->get(),
            ];
        } elseif (Auth::check()) {
            $orders = $ordersQuery->where('user_id', Auth::id())->limit(20)->get();
        } else {
            $orders = $ordersQuery->where('guest_session_id', $request->session()->getId())->limit(20)->get();
        }

        return view('cafe.index', compact('items', 'menuItems', 'allMenuItems', 'weeklyMenu', 'categories', 'menuCategories', 'orders', 'isCafeAdmin', 'reservedTables', 'staff', 'cafeSales'));
    }

    public function tableAvailability(Request $request)
    {
        $validated = $request->validate([
            'floor' => 'required|in:Ground Floor,Outdoor Seating',
        ]);

        $reserved = CafeTableReservation::query()
            ->where('floor', $validated['floor'])
            ->where('expires_at', '>', now())
            ->pluck('table_number')
            ->values();

        return response()->json(['reserved' => $reserved]);
    }

    public function store(Request $request)
    {
        if (! Auth::check() || Auth::user()->role !== 'cafe_admin') {
            abort(403, 'Only the Cafe Admin can manage cafeteria items.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'is_available' => 'nullable|boolean',
            'is_special_offer' => 'nullable|boolean',
        ]);

        $validated['is_available'] = $request->has('is_available');
        $validated['is_special_offer'] = $request->has('is_special_offer');
        $validated['image_url'] = '/images/cafeteria.jpeg';

        CafeItem::create($validated);

        return back()->with('success', 'Cafe menu item added!');
    }

    public function toggleAvailability(CafeItem $item)
    {
        if (! Auth::check() || Auth::user()->role !== 'cafe_admin') {
            abort(403);
        }

        $item->update(['is_available' => ! $item->is_available]);

        return back()->with('success', 'Menu item availability updated.');
    }

    public function destroyItem(CafeItem $item)
    {
        if (! Auth::check() || Auth::user()->role !== 'cafe_admin') {
            abort(403, 'Only the Cafe Admin can remove cafeteria items.');
        }

        $item->delete();

        return back()->with('success', 'Cafe menu item removed from inventory.');
    }

    public function placeOrder(Request $request)
    {
        if (Auth::check() && Auth::user()->isGuest()) {
            abort(403, 'Guest accounts cannot place orders. Continue in public Guest Mode instead.');
        }

        $validated = $request->validate([
            'items' => 'required|array|min:1|max:20',
            'items.*.id' => 'required|integer|distinct',
            'items.*.quantity' => 'required|integer|min:1|max:20',
            'delivery_option' => 'required|in:dine-in,takeaway',
            'floor' => 'nullable|required_if:delivery_option,dine-in|in:Ground Floor,Outdoor Seating',
            'table_number' => ['nullable', 'required_if:delivery_option,dine-in', 'regex:/\ATable (?:[1-9]|1[0-2])\z/'],
            'payment_method' => 'required|in:cash,bkash,nagad',
            'payment_phone' => ['required_if:payment_method,bkash,nagad', 'nullable', 'regex:/\A01[3-9]\d{8}\z/'],
            'payment_transaction_id' => 'required_if:payment_method,bkash,nagad|nullable|string|max:100',
            'special_instructions' => 'nullable|string|max:500',
            'coupon_code' => 'nullable|in:SAVE10,save10,Save10',
        ]);

        $quantities = collect($validated['items'])->keyBy('id');
        $cafeItems = CafeItem::query()
            ->whereIn('id', $quantities->keys())
            ->where('is_available', true)
            ->get()
            ->keyBy('id');

        if ($cafeItems->count() !== $quantities->count()) {
            throw ValidationException::withMessages([
                'items' => 'One or more menu items are no longer available. Refresh the menu and try again.',
            ]);
        }

        $lineItems = $cafeItems->map(function (CafeItem $item) use ($quantities): array {
            $quantity = (int) $quantities[$item->id]['quantity'];
            $discounted = $item->discount_price !== null && $item->discount_price < $item->price;
            $unitPrice = (int) round(((float) ($discounted ? $item->discount_price : $item->price)) * 100);

            return [
                'cafe_item_id' => $item->id,
                'name' => $item->name,
                'unit_price' => $unitPrice,
                'quantity' => $quantity,
                'subtotal' => $unitPrice * $quantity,
            ];
        })->values();

        $subtotal = $lineItems->sum('subtotal');
        $vat = (int) round($subtotal * 0.05);
        $serviceCharge = (int) round($subtotal * 0.10);
        $discount = $request->input('coupon_code') ? (int) round(($subtotal + $vat + $serviceCharge) * 0.10) : 0;
        $total = $subtotal + $vat + $serviceCharge - $discount;

        $order = DB::transaction(function () use ($request, $validated, $lineItems, $subtotal, $vat, $serviceCharge, $discount, $total): CafeOrder {
            $reservation = null;

            if ($validated['delivery_option'] === 'dine-in') {
                $expiresAt = now()->addMinutes(30);
                $reserved = CafeTableReservation::query()
                    ->where('floor', $validated['floor'])
                    ->where('table_number', $validated['table_number'])
                    ->where('expires_at', '<=', now())
                    ->update([
                        'expires_at' => $expiresAt,
                        'updated_at' => now(),
                    ]);

                if ($reserved !== 1) {
                    throw ValidationException::withMessages([
                        'table_number' => 'This table is booked for another order for 30 minutes. Please choose another table.',
                    ]);
                }

                $reservation = CafeTableReservation::query()
                    ->where('floor', $validated['floor'])
                    ->where('table_number', $validated['table_number'])
                    ->firstOrFail();
            }

            $order = CafeOrder::create([
                'user_id' => Auth::id(),
                'guest_session_id' => Auth::check() ? null : $request->session()->getId(),
                'guest_name' => Auth::user()?->name ?? 'Guest',
                'delivery_option' => $validated['delivery_option'],
                'floor' => $validated['delivery_option'] === 'dine-in' ? $validated['floor'] : null,
                'table_number' => $validated['delivery_option'] === 'dine-in' ? $validated['table_number'] : null,
                'payment_method' => $validated['payment_method'],
                'payment_phone' => in_array($validated['payment_method'], ['bkash', 'nagad'], true) ? $validated['payment_phone'] : null,
                'payment_transaction_id' => in_array($validated['payment_method'], ['bkash', 'nagad'], true) ? $validated['payment_transaction_id'] : null,
                'special_instructions' => $validated['special_instructions'] ?? null,
                'coupon_code' => isset($validated['coupon_code']) ? strtoupper($validated['coupon_code']) : null,
                'subtotal' => $subtotal / 100,
                'vat' => $vat / 100,
                'service_charge' => $serviceCharge / 100,
                'discount' => $discount / 100,
                'total_amount' => $total / 100,
                'status' => 'pending',
            ]);

            $orderToken = 'CAF'.str_pad((string) $order->id, 8, '0', STR_PAD_LEFT);
            $order->update(['order_token' => $orderToken]);

            if ($reservation) {
                $reservation->update([
                    'cafe_order_id' => $order->id,
                    'expires_at' => now()->addMinutes(30),
                ]);
            }

            foreach ($lineItems as $index => $lineItem) {
                CafeOrderItem::create([
                    'cafe_order_id' => $order->id,
                    'cafe_item_id' => $lineItem['cafe_item_id'],
                    'name' => $lineItem['name'],
                    'unit_price' => $lineItem['unit_price'] / 100,
                    'quantity' => $lineItem['quantity'],
                    'subtotal' => $lineItem['subtotal'] / 100,
                    'item_token' => $orderToken.'-'.str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT),
                ]);
            }

            return $order->load('items');
        });

        return response()->json([
            'message' => 'Order placed successfully. Show your token at the cafeteria counter.',
            'orderToken' => $order->order_token,
            'total' => (float) $order->total_amount,
            'items' => $order->items->map(fn (CafeOrderItem $item) => [
                'id' => $item->cafe_item_id,
                'name' => $item->name,
                'qty' => $item->quantity,
                'price' => (float) $item->unit_price,
                'token' => $item->item_token,
            ])->values(),
        ], 201);
    }

    public function updateOrderStatus(Request $request, CafeOrder $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:preparing,ready,completed,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        if ($validated['status'] === 'cancelled') {
            CafeTableReservation::where('cafe_order_id', $order->id)->update([
                'expires_at' => now(),
            ]);
        }

        return redirect()
            ->route('cafe.index', ['tab' => 'orders'])
            ->with('success', 'Order status updated.');
    }
}
