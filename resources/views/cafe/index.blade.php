@extends('layouts.app')

@section('title', 'BAUST Campus Cafe')

@section('content')
<div x-data="cafeApp(@js($menuItems), @js($weeklyMenu), @js($allMenuItems), @js($reservedTables))"
     x-init="loadReservedTables(); setInterval(() => loadReservedTables(), 30000)"
     class="space-y-6">

    <div class="relative isolate overflow-hidden rounded-3xl border border-blue-200 bg-gradient-to-br from-blue-50 via-white to-sky-100 p-5 shadow-xl shadow-blue-900/5 dark:border-blue-500/20 dark:from-slate-950 dark:via-slate-900 dark:to-blue-950/50 md:p-8">
        <div class="pointer-events-none absolute -right-8 -top-12 h-48 w-48 rounded-full bg-blue-300/30 blur-3xl dark:bg-blue-500/10"></div>
        <div class="relative grid items-center gap-6 md:grid-cols-2">
            <div class="space-y-3">
                <span class="inline-flex items-center gap-2 rounded-full border border-blue-300 bg-blue-100 px-3.5 py-1 text-xs font-bold uppercase tracking-wider text-blue-900 dark:border-blue-500/30 dark:bg-blue-500/10 dark:text-blue-300">
                    <i class="fa-solid fa-utensils"></i> BAUST Campus Cafe
                </span>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-950 dark:text-white md:text-4xl">Good food. Campus prices.</h1>
                <p class="max-w-xl text-sm font-medium text-slate-600 dark:text-slate-300">Fresh favourites, affordable combos and a weekly menu made for BAUST students.</p>
                <div class="flex flex-wrap items-center gap-4 pt-1 text-xs font-semibold text-slate-500 dark:text-slate-400">
                    <span><i class="fa-regular fa-clock mr-1 text-blue-500"></i> 8:00 AM - 8:00 PM</span>
                    <span><i class="fa-solid fa-location-dot mr-1 text-blue-500"></i> Ground Floor & Outdoor Seating</span>
                </div>
            </div>

            <div class="space-y-3">
                <div class="group relative h-44 overflow-hidden rounded-2xl border border-blue-200 shadow-lg shadow-blue-950/10 dark:border-blue-400/20 sm:h-52">
                    <img src="/images/cafeteria.jpeg" alt="BAUST campus cafeteria" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                    <div class="absolute inset-0 bg-gradient-to-t from-blue-950/80 via-blue-950/10 to-transparent"></div>
                    <span class="absolute bottom-3 left-4 inline-flex items-center gap-2 text-xs font-bold text-white">
                        <i class="fa-solid fa-location-dot"></i> Your campus, your cafe
                    </span>
                </div>
                <div class="flex flex-col gap-2 sm:flex-row">
                    <button @click="activeTab = 'cart'" class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-blue-700 px-5 py-3 text-xs font-bold text-white shadow-lg shadow-blue-900/20 transition hover:-translate-y-0.5 hover:bg-blue-800 dark:bg-blue-500 dark:text-slate-950 dark:hover:bg-blue-400">
                        <i class="fa-solid fa-cart-shopping"></i> View Cart & Checkout
                        <span x-show="cartItemCount > 0" x-text="cartItemCount" class="rounded-full bg-white px-2 py-0.5 text-[11px] font-extrabold text-blue-700">0</span>
                    </button>

                    @auth
                        @if(Auth::user()->role === 'cafe_admin')
                            <button onclick="document.getElementById('cafeModal').classList.remove('hidden')" class="flex items-center justify-center gap-2 rounded-xl border border-blue-200 bg-white/80 px-4 py-3 text-xs font-bold text-blue-800 transition hover:bg-blue-50 dark:border-blue-400/30 dark:bg-slate-800 dark:text-blue-200 dark:hover:bg-slate-700">
                                <i class="fa-solid fa-plus"></i> Add Item
                            </button>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>

    @if($isCafeAdmin)
        <section class="space-y-4 rounded-3xl border border-emerald-200 bg-gradient-to-br from-emerald-50 via-white to-teal-50 p-5 shadow-lg shadow-emerald-950/5 dark:border-emerald-500/20 dark:from-slate-950 dark:via-slate-900 dark:to-emerald-950/20 md:p-6" aria-labelledby="cafe-sales-overview-title">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-white/80 px-3 py-1 text-[10px] font-extrabold uppercase tracking-[0.16em] text-emerald-800 dark:border-emerald-500/20 dark:bg-slate-900/70 dark:text-emerald-300">
                        <i class="fa-solid fa-chart-line"></i> Cafe business snapshot
                    </span>
                    <h2 id="cafe-sales-overview-title" class="mt-2 text-xl font-extrabold text-slate-950 dark:text-white md:text-2xl">Sales & popular items</h2>
                    <p class="mt-1 text-xs font-medium text-slate-600 dark:text-slate-400">Income is calculated from all non-cancelled orders; cancelled orders are excluded.</p>
                </div>
                <span class="inline-flex w-fit items-center gap-2 rounded-full bg-emerald-100 px-3 py-1.5 text-[11px] font-bold text-emerald-800 dark:bg-emerald-500/10 dark:text-emerald-300">
                    <i class="fa-regular fa-calendar"></i> Today: {{ now()->format('M j, Y') }}
                </span>
            </div>

            <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                <article class="rounded-2xl border border-emerald-200 bg-white/90 p-4 shadow-sm dark:border-emerald-500/20 dark:bg-slate-900/80">
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-xs font-bold text-slate-600 dark:text-slate-300">Total cafe income</span>
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700 dark:bg-emerald-500/15 dark:text-emerald-300"><i class="fa-solid fa-sack-dollar"></i></span>
                    </div>
                    <p class="mt-3 text-2xl font-black tracking-tight text-slate-950 dark:text-white">৳{{ number_format((float) $cafeSales['total_income'], 2) }}</p>
                    <p class="mt-1 text-[11px] font-semibold text-slate-500 dark:text-slate-400">{{ number_format($cafeSales['order_count']) }} non-cancelled {{ $cafeSales['order_count'] === 1 ? 'order' : 'orders' }}</p>
                </article>

                <article class="rounded-2xl border border-blue-200 bg-white/90 p-4 shadow-sm dark:border-blue-500/20 dark:bg-slate-900/80">
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-xs font-bold text-slate-600 dark:text-slate-300">Today's income</span>
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-100 text-blue-700 dark:bg-blue-500/15 dark:text-blue-300"><i class="fa-solid fa-calendar-day"></i></span>
                    </div>
                    <p class="mt-3 text-2xl font-black tracking-tight text-slate-950 dark:text-white">৳{{ number_format((float) $cafeSales['today_income'], 2) }}</p>
                    <p class="mt-1 text-[11px] font-semibold text-slate-500 dark:text-slate-400">{{ number_format($cafeSales['today_order_count']) }} {{ $cafeSales['today_order_count'] === 1 ? 'order' : 'orders' }} today</p>
                </article>

                <article class="rounded-2xl border border-violet-200 bg-white/90 p-4 shadow-sm dark:border-violet-500/20 dark:bg-slate-900/80">
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-xs font-bold text-slate-600 dark:text-slate-300">Items sold</span>
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-violet-100 text-violet-700 dark:bg-violet-500/15 dark:text-violet-300"><i class="fa-solid fa-basket-shopping"></i></span>
                    </div>
                    <p class="mt-3 text-2xl font-black tracking-tight text-slate-950 dark:text-white">{{ number_format($cafeSales['items_sold']) }}</p>
                    <p class="mt-1 text-[11px] font-semibold text-slate-500 dark:text-slate-400">Across all non-cancelled orders</p>
                </article>

                <article class="rounded-2xl border border-amber-200 bg-white/90 p-4 shadow-sm dark:border-amber-500/20 dark:bg-slate-900/80">
                    <div class="flex items-center justify-between gap-3">
                        <span class="text-xs font-bold text-slate-600 dark:text-slate-300">Best seller</span>
                        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-300"><i class="fa-solid fa-trophy"></i></span>
                    </div>
                    @if($cafeSales['top_items']->isNotEmpty())
                        <p class="mt-3 truncate text-lg font-black text-slate-950 dark:text-white" title="{{ $cafeSales['top_items']->first()->name }}">{{ $cafeSales['top_items']->first()->name }}</p>
                        <p class="mt-1 text-[11px] font-semibold text-slate-500 dark:text-slate-400">{{ number_format($cafeSales['top_items']->first()->quantity_sold) }} sold · ৳{{ number_format((float) $cafeSales['top_items']->first()->item_sales, 2) }} item sales</p>
                    @else
                        <p class="mt-3 text-lg font-black text-slate-500 dark:text-slate-400">No sales yet</p>
                        <p class="mt-1 text-[11px] font-semibold text-slate-500 dark:text-slate-400">Popular items will appear here.</p>
                    @endif
                </article>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white/90 dark:border-slate-700 dark:bg-slate-900/80">
                <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 px-4 py-3 dark:border-slate-800">
                    <h3 class="text-sm font-extrabold text-slate-900 dark:text-white"><i class="fa-solid fa-fire mr-2 text-orange-500"></i>Top-selling items</h3>
                    <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400">Ranked by quantity sold</span>
                </div>
                @forelse($cafeSales['top_items'] as $index => $soldItem)
                    <div class="flex items-center gap-3 border-b border-slate-100 px-4 py-3 last:border-b-0 dark:border-slate-800">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl {{ $index === 0 ? 'bg-amber-100 text-amber-700 dark:bg-amber-500/15 dark:text-amber-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300' }} text-xs font-black">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-xs font-extrabold text-slate-900 dark:text-white">{{ $soldItem->name }}</p>
                            <p class="mt-0.5 text-[10px] font-semibold text-slate-500 dark:text-slate-400">{{ number_format($soldItem->quantity_sold) }} sold</p>
                        </div>
                        <p class="shrink-0 text-xs font-extrabold text-emerald-700 dark:text-emerald-300">৳{{ number_format((float) $soldItem->item_sales, 2) }}</p>
                    </div>
                @empty
                    <p class="px-4 py-5 text-center text-xs font-semibold text-slate-500 dark:text-slate-400">Sales for popular items will appear here after orders are placed.</p>
                @endforelse
            </div>
        </section>
    @endif

    <!-- Navigation Tabs -->
    <div class="flex items-center gap-2 overflow-x-auto border-b border-slate-200 pb-2 dark:border-slate-800">
        @if($isCafeAdmin)
            <button @click="activeTab = 'orders'" :class="activeTab === 'orders' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'" class="flex shrink-0 items-center gap-2 rounded-xl px-5 py-2.5 text-xs font-bold transition">
                <i class="fa-solid fa-receipt"></i> Cafe Management
            </button>
            <button @click="activeTab = 'inventory'" :class="activeTab === 'inventory' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'" class="flex shrink-0 items-center gap-2 rounded-xl px-5 py-2.5 text-xs font-bold transition">
                <i class="fa-solid fa-boxes-stacked"></i> Inventory Management
            </button>
            <button @click="activeTab = 'staff'" :class="activeTab === 'staff' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'" class="flex shrink-0 items-center gap-2 rounded-xl px-5 py-2.5 text-xs font-bold transition">
                <i class="fa-solid fa-user-group"></i> Staff Management
            </button>
            <a href="{{ route('queries.index') }}" class="flex shrink-0 items-center gap-2 rounded-xl bg-slate-100 px-5 py-2.5 text-xs font-bold text-slate-700 transition hover:text-slate-900 dark:bg-slate-900 dark:text-slate-400 dark:hover:text-white">
                <i class="fa-solid fa-comments"></i> Queries
            </a>
        @endif
        <button @click="activeTab = 'menu'" :class="activeTab === 'menu' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'" class="flex shrink-0 items-center gap-2 rounded-xl px-5 py-2.5 text-xs font-bold transition">
            <i class="fa-solid fa-utensils"></i> Full Menu
        </button>
        <button @click="activeTab = 'weekly'" :class="activeTab === 'weekly' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'" class="flex shrink-0 items-center gap-2 rounded-xl px-5 py-2.5 text-xs font-bold transition">
            <i class="fa-solid fa-calendar-days text-rose-500"></i> Weekly Special Menu
        </button>
        <button @click="activeTab = 'cart'" :class="activeTab === 'cart' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'" class="flex shrink-0 items-center gap-2 rounded-xl px-5 py-2.5 text-xs font-bold transition">
            <i class="fa-solid fa-cart-shopping text-emerald-500"></i> Cart & Checkout
            <span x-show="cartItemCount > 0" x-text="'(' + cartItemCount + ')'" class="text-blue-300 font-extrabold"></span>
        </button>
        @if(!$isCafeAdmin)
            <button @click="activeTab = 'orders'" :class="activeTab === 'orders' ? 'bg-blue-600 text-white shadow-sm' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'" class="flex shrink-0 items-center gap-2 rounded-xl px-5 py-2.5 text-xs font-bold transition">
                <i class="fa-solid fa-receipt"></i> My Orders
            </button>
        @endif
    </div>

    @if($isCafeAdmin)
        <section x-show="activeTab === 'inventory'" x-cloak class="space-y-5">
            <div class="flex flex-col gap-4 rounded-3xl border border-amber-200 bg-gradient-to-br from-amber-50 via-white to-orange-50 p-5 shadow-sm dark:border-amber-500/20 dark:from-slate-950 dark:via-slate-900 dark:to-amber-950/20 sm:flex-row sm:items-center sm:justify-between sm:p-6">
                <div>
                    <span class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-amber-700 dark:text-amber-300">Cafe stockroom</span>
                    <h2 class="mt-1 text-xl font-extrabold text-slate-900 dark:text-white">Inventory Management</h2>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">View every menu item, update availability or remove an item from the catalog.</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="rounded-full bg-white px-3 py-1.5 text-xs font-extrabold text-amber-800 shadow-sm dark:bg-slate-800 dark:text-amber-200">{{ $items->count() }} items</span>
                    <button type="button" onclick="document.getElementById('cafeModal').classList.remove('hidden')" class="inline-flex items-center gap-2 rounded-xl bg-blue-700 px-4 py-3 text-xs font-extrabold text-white shadow-lg shadow-blue-900/15 transition hover:bg-blue-800">
                        <i class="fa-solid fa-plus"></i> Add Item
                    </button>
                </div>
            </div>

            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[760px] text-left text-sm">
                        <thead class="bg-slate-50 text-[11px] font-extrabold uppercase tracking-wide text-slate-500 dark:bg-slate-950 dark:text-slate-400">
                            <tr>
                                <th class="px-5 py-4">Menu item</th>
                                <th class="px-5 py-4">Category</th>
                                <th class="px-5 py-4">Price</th>
                                <th class="px-5 py-4">Availability</th>
                                <th class="px-5 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($items as $item)
                                <tr class="transition hover:bg-blue-50/50 dark:hover:bg-slate-800/60">
                                    <td class="px-5 py-4">
                                        <div class="font-extrabold text-slate-900 dark:text-white">{{ $item->name }}</div>
                                        <div class="mt-1 max-w-sm truncate text-xs text-slate-500 dark:text-slate-400">{{ $item->description ?: 'No description added' }}</div>
                                    </td>
                                    <td class="px-5 py-4"><span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-800 dark:bg-blue-500/10 dark:text-blue-200">{{ $categories[$item->category] ?? ucfirst($item->category) }}</span></td>
                                    <td class="px-5 py-4 font-extrabold text-slate-800 dark:text-slate-200">
                                        @if($item->discount_price && $item->discount_price < $item->price)
                                            ৳{{ number_format((float) $item->discount_price, 2) }} <span class="ml-1 text-xs font-medium text-slate-400 line-through">৳{{ number_format((float) $item->price, 2) }}</span>
                                        @else
                                            ৳{{ number_format((float) $item->price, 2) }}
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        <form action="{{ route('cafe.toggle', $item) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="rounded-full px-3 py-1.5 text-xs font-extrabold transition {{ $item->is_available ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200 dark:bg-emerald-500/15 dark:text-emerald-300' : 'bg-rose-100 text-rose-800 hover:bg-rose-200 dark:bg-rose-500/15 dark:text-rose-300' }}">
                                                {{ $item->is_available ? 'Available' : 'Unavailable' }}
                                            </button>
                                        </form>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <form action="{{ route('cafe.items.destroy', $item) }}" method="POST" onsubmit="return confirm('Remove {{ addslashes($item->name) }} from the cafe inventory?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-rose-200 px-3 py-2 text-xs font-bold text-rose-700 transition hover:bg-rose-50 dark:border-rose-500/30 dark:text-rose-300 dark:hover:bg-rose-500/10">
                                                <i class="fa-solid fa-trash-can"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="px-5 py-12 text-center text-sm font-semibold text-slate-500 dark:text-slate-400">No inventory items yet. Add a menu item to get started.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section x-show="activeTab === 'staff'" x-cloak class="space-y-5">
            <div class="rounded-3xl border border-violet-200 bg-gradient-to-br from-violet-50 via-white to-blue-50 p-5 shadow-sm dark:border-violet-500/20 dark:from-slate-950 dark:via-slate-900 dark:to-violet-950/20 sm:p-6">
                <span class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-violet-700 dark:text-violet-300">Cafe team</span>
                <h2 class="mt-1 text-xl font-extrabold text-slate-900 dark:text-white">Staff Management</h2>
                <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Add staff details and duty hours for the cafe team.</p>
            </div>

            <form action="{{ route('cafe.staff.store') }}" method="POST" class="grid gap-3 rounded-3xl border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:grid-cols-2 lg:grid-cols-4">
                @csrf
                <label class="space-y-1.5">
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Staff name</span>
                    <input type="text" name="name" value="{{ old('name') }}" required maxlength="255" placeholder="Enter staff name" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                </label>
                <label class="space-y-1.5">
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Position</span>
                    <input type="text" name="position" value="{{ old('position') }}" maxlength="100" placeholder="e.g. Cook, Cashier" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                </label>
                <label class="space-y-1.5">
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Phone (optional)</span>
                    <input type="tel" name="phone" value="{{ old('phone') }}" maxlength="20" placeholder="Staff contact number" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                </label>
                <label class="space-y-1.5">
                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300">Duty hours</span>
                    <input type="text" name="duty_hours" value="{{ old('duty_hours') }}" required maxlength="100" placeholder="e.g. 8:00 AM - 4:00 PM" class="w-full rounded-xl border border-slate-300 bg-slate-50 px-3 py-2.5 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                </label>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-xl bg-violet-700 px-4 py-3 text-xs font-extrabold text-white transition hover:bg-violet-800 sm:col-span-2 lg:col-span-4">
                    <i class="fa-solid fa-user-plus"></i> Add Cafe Staff
                </button>
            </form>

            <div class="grid gap-3 md:grid-cols-2">
                @forelse($staff as $member)
                    <article class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-violet-100 text-lg font-extrabold text-violet-700 dark:bg-violet-500/15 dark:text-violet-300">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                        <div class="min-w-0 flex-1">
                            <h3 class="truncate text-sm font-extrabold text-slate-900 dark:text-white">{{ $member->name }}</h3>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $member->position ?: 'Cafe Staff' }} · {{ $member->duty_hours }}</p>
                            @if($member->phone)<p class="mt-1 text-xs font-semibold text-slate-600 dark:text-slate-300">{{ $member->phone }}</p>@endif
                        </div>
                        <form action="{{ route('cafe.staff.destroy', $member) }}" method="POST" onsubmit="return confirm('Remove {{ addslashes($member->name) }} from cafe staff?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" aria-label="Remove {{ $member->name }}" class="rounded-xl border border-rose-200 p-2.5 text-rose-700 transition hover:bg-rose-50 dark:border-rose-500/30 dark:text-rose-300 dark:hover:bg-rose-500/10"><i class="fa-solid fa-trash-can"></i></button>
                        </form>
                    </article>
                @empty
                    <div class="rounded-2xl border border-dashed border-slate-300 p-8 text-center text-sm font-semibold text-slate-500 dark:border-slate-700 dark:text-slate-400 md:col-span-2">No cafe staff have been added yet.</div>
                @endforelse
            </div>
        </section>
    @endif

    <!-- MENU TAB -->
    <div x-show="activeTab === 'menu'" class="space-y-6">

        <!-- Category Tabs & Search Filter -->
        <div class="glass-card p-5 rounded-2xl border border-slate-200 dark:border-slate-800 space-y-4">
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                <div class="relative flex-1">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                    <input type="text" x-model="searchQuery" placeholder="Search menu items (e.g. Biryani, Tea, Roll)..." class="w-full pl-9 pr-4 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white font-semibold placeholder-slate-400 focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex flex-wrap items-center gap-3 text-xs font-semibold text-slate-700 dark:text-slate-300">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" x-model="showAvailableOnly" class="rounded bg-slate-50 dark:bg-slate-900 border-slate-300 dark:border-slate-700 text-blue-600 focus:ring-blue-500">
                        <span>Available only</span>
                    </label>

                    <select x-model="selectedCategory" class="px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white font-bold">
                        <option value="All">All categories</option>
                        @foreach($menuCategories as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex flex-col gap-2 border-t border-slate-100 pt-3 dark:border-slate-800 sm:flex-row sm:items-center sm:gap-4">
                <label for="cafe-price-range" class="shrink-0 text-xs font-bold text-slate-700 dark:text-slate-300">Price range</label>
                <input id="cafe-price-range" type="range" min="10" max="100" step="10" x-model.number="maxPrice"
                       class="h-2 w-full cursor-pointer accent-blue-600">
                <span class="shrink-0 text-xs font-extrabold text-blue-700 dark:text-blue-300">Up to ৳<span x-text="maxPrice"></span></span>
            </div>
        </div>

        <!-- Food Items Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <template x-for="item in filteredMenuItems" :key="item.id">
                <div class="glass-card p-5 rounded-2xl border border-slate-200 dark:border-slate-800 flex flex-col justify-between hover-lift shadow-sm bg-slate-50/60 dark:bg-slate-900/60">
                    <div>
                        <div class="mb-4 flex h-24 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-100 via-sky-50 to-rose-100 text-6xl shadow-inner dark:from-blue-500/10 dark:via-slate-800 dark:to-rose-500/10" x-text="item.image"></div>
                        <!-- Header Badges -->
                        <div class="flex items-center justify-between mb-3">
                            <span class="px-2.5 py-0.5 rounded-md text-[10px] font-extrabold uppercase bg-blue-100 dark:bg-blue-500/20 text-blue-800 dark:text-blue-300 border border-blue-300 dark:border-blue-500/30" x-text="item.categoryLabel"></span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold" :class="item.available ? 'bg-emerald-100 dark:bg-emerald-500/20 text-emerald-800 dark:text-emerald-300' : 'bg-rose-100 dark:bg-rose-500/20 text-rose-800 dark:text-rose-300'" x-text="item.available ? 'In Stock' : 'Sold Out'"></span>
                        </div>

                        <!-- Title & Description -->
                        <h3 class="text-sm font-extrabold text-slate-900 dark:text-white leading-snug" x-text="item.name"></h3>
                        <p class="text-[11px] text-slate-600 dark:text-slate-400 mt-1 line-clamp-2" x-text="item.description || 'Freshly prepared at BAUST Central Cafeteria.'"></p>
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-200 dark:border-slate-800 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Price</span>
                            <span class="text-base font-extrabold text-blue-600 dark:text-blue-400" x-text="'৳' + item.price"></span>
                            <span x-show="item.originalPrice" class="text-xs text-slate-400 line-through" x-text="'৳' + item.originalPrice"></span>
                        </div>

                        <!-- Add to Cart Button -->
                        <button @click="addToCart(item)" :disabled="!item.available" :class="item.available ? 'bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-500 text-white' : 'bg-slate-300 dark:bg-slate-800 text-slate-500 cursor-not-allowed'" class="w-full py-2.5 rounded-xl text-xs font-extrabold transition shadow-sm flex items-center justify-center gap-2">
                            <i class="fa-solid fa-cart-plus"></i> ADD TO CART
                        </button>

                        <div x-show="cart[item.id]" class="text-center">
                            <span class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400" x-text="cart[item.id] + ' in cart'"></span>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        <div x-show="filteredMenuItems.length === 0" x-cloak class="glass-card rounded-2xl border border-dashed border-slate-300 p-10 text-center dark:border-slate-700">
            <i class="fa-solid fa-utensils text-3xl text-blue-500"></i>
            <p class="mt-3 text-sm font-bold text-slate-800 dark:text-white">No cafe items are available right now.</p>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Please check back later or contact the Cafe Admin.</p>
        </div>
    </div>

    <!-- WEEKLY SPECIAL MENU TAB -->
    <div x-show="activeTab === 'weekly'" class="space-y-4">
        <div class="glass-card p-6 rounded-3xl border border-slate-200 dark:border-slate-800">
            <h3 class="text-lg font-extrabold text-slate-900 dark:text-white flex items-center gap-2 mb-4">
                <i class="fa-solid fa-calendar-days text-rose-500"></i> Weekly Special Menu & Timings
            </h3>

            <div class="space-y-3">
                <template x-for="dayData in weeklyMenu" :key="dayData.day">
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:border-blue-300 dark:border-slate-800 dark:bg-slate-900">
                        <button @click="dayData.open = !dayData.open" class="w-full p-4 flex items-center justify-between font-extrabold text-slate-900 dark:text-white text-sm hover:bg-slate-100 dark:hover:bg-slate-800/60 transition">
                            <div class="flex items-center gap-3">
                                <span class="w-9 h-9 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center">
                                    <i class="fa-solid fa-sun"></i>
                                </span>
                                <span x-text="dayData.day + (dayData.special ? ' · Special Menu' : '')"></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full bg-slate-200 dark:bg-slate-800 text-slate-700 dark:text-slate-400 text-xs font-semibold" x-text="dayData.meals.length + ' meals'"></span>
                                <i class="fa-solid" :class="dayData.open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                            </div>
                        </button>

                        <div x-show="dayData.open" class="p-4 border-t border-slate-200 dark:border-slate-800 divide-y divide-slate-200 dark:divide-slate-800">
                            <template x-for="meal in dayData.meals" :key="meal.type">
                                <div class="py-3 flex items-center justify-between gap-4">
                                    <div>
                                        <span class="rounded bg-blue-500/20 px-2 py-0.5 text-[10px] font-extrabold uppercase text-blue-800 dark:text-blue-300" x-text="meal.type"></span>
                                        <template x-if="meal.products">
                                            <div class="mt-2 flex flex-wrap gap-2">
                                                <template x-for="product in meal.products" :key="product.id">
                                                    <button @click="addWeeklyMealToCart(product)" :disabled="!product.available"
                                                            class="inline-flex items-center gap-1.5 rounded-xl border border-blue-200 bg-blue-50 px-3 py-2 text-[11px] font-bold text-slate-800 transition hover:-translate-y-0.5 hover:border-blue-400 disabled:cursor-not-allowed disabled:opacity-50 dark:border-blue-500/20 dark:bg-blue-500/10 dark:text-slate-100">
                                                        <span x-text="product.image"></span><span x-text="product.name"></span>
                                                        <span class="text-blue-700 dark:text-blue-300" x-text="'৳' + product.price"></span>
                                                        <i class="fa-solid fa-cart-plus text-blue-600 dark:text-blue-300"></i>
                                                    </button>
                                                </template>
                                            </div>
                                        </template>
                                        <template x-if="!meal.products">
                                            <div>
                                                <h5 class="mt-1 text-xs font-bold text-slate-900 dark:text-white" x-text="meal.name"></h5>
                                                <p class="text-[11px] text-slate-600 dark:text-slate-400" x-text="meal.items"></p>
                                            </div>
                                        </template>
                                    </div>
                                    <div x-show="!meal.products" class="space-y-1 text-right">
                                        <div class="text-xs font-extrabold text-blue-600 dark:text-blue-400" x-text="'৳' + meal.price"></div>
                                        <button @click="addWeeklyMealToCart(meal)" :disabled="!meal.available" class="rounded-lg bg-blue-600 px-3 py-1 text-[11px] font-bold text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50 dark:bg-blue-600 dark:hover:bg-blue-500">
                                            + ADD TO CART
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- CART & CHECKOUT TAB -->
    <div x-show="activeTab === 'cart'" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Cart Items List -->
            <div class="lg:col-span-2 glass-card p-6 rounded-3xl border border-slate-200 dark:border-slate-800 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                    <h3 class="text-lg font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-cart-shopping text-emerald-500"></i> Your Cart Items
                    </h3>
                    <button @click="clearCart()" x-show="cartList.length > 0" class="text-xs font-bold text-rose-600 hover:text-rose-500 flex items-center gap-1">
                        <i class="fa-solid fa-trash"></i> Clear All
                    </button>
                </div>

                <div x-show="cartList.length === 0" class="py-12 text-center text-slate-400 space-y-3">
                    <i class="fa-solid fa-basket-shopping text-5xl text-slate-300 dark:text-slate-700"></i>
                    <p class="text-sm font-semibold">Your cafeteria cart is empty.</p>
                    <button @click="activeTab = 'menu'" class="px-4 py-2 rounded-xl bg-blue-600 text-white font-bold text-xs">Browse Menu Items</button>
                </div>

                <div class="space-y-3">
                    <template x-for="item in cartList" :key="item.id">
                        <div class="p-3.5 rounded-2xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/60 flex items-center justify-between gap-3">
                            <div>
                                <h4 class="text-xs font-extrabold text-slate-900 dark:text-white" x-text="item.name"></h4>
                                <span class="text-[11px] text-slate-500" x-text="'৳' + item.price + ' each'"></span>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="flex items-center bg-slate-200 dark:bg-slate-800 rounded-lg p-1 text-xs">
                                    <button @click="decreaseQty(item.id)" class="w-6 h-6 rounded bg-white dark:bg-slate-700 font-bold text-slate-900 dark:text-white flex items-center justify-center">-</button>
                                    <span class="px-3 font-extrabold text-slate-900 dark:text-white" x-text="item.qty"></span>
                                    <button @click="increaseQty(item.id)" class="w-6 h-6 rounded bg-white dark:bg-slate-700 font-bold text-slate-900 dark:text-white flex items-center justify-center">+</button>
                                </div>

                                <div class="text-right min-w-[60px]">
                                    <span class="text-xs font-extrabold text-blue-600 dark:text-blue-400" x-text="'৳' + (item.price * item.qty)"></span>
                                </div>

                                <button @click="removeItem(item.id)" class="text-slate-400 hover:text-rose-500 transition">
                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Checkout Details & Bill Breakdown -->
            <div class="glass-card p-6 rounded-3xl border border-slate-200 dark:border-slate-800 space-y-5">
                <h3 class="text-base font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-receipt text-blue-500"></i> Order Details & Payment
                </h3>

                <!-- Delivery Option (Dine In vs Takeaway) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1.5">Delivery Option</label>
                    <div class="grid grid-cols-2 gap-2">
                        <button @click="deliveryOption = 'dine-in'" :class="deliveryOption === 'dine-in' ? 'bg-blue-600 text-white border-blue-600' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700'" class="py-2.5 rounded-xl border text-xs font-bold transition flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-utensils"></i> Dine In
                        </button>
                        <button @click="deliveryOption = 'takeaway'" :class="deliveryOption === 'takeaway' ? 'bg-blue-600 text-white border-blue-600' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700'" class="py-2.5 rounded-xl border text-xs font-bold transition flex items-center justify-center gap-1.5">
                            <i class="fa-solid fa-bag-shopping"></i> Take Away
                        </button>
                    </div>
                </div>

                <!-- Table Selection if Dine In -->
                <div x-show="deliveryOption === 'dine-in'" class="p-3 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl space-y-2">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase">Floor</label>
                            <select x-model="selectedFloor" @change="loadReservedTables()" class="w-full rounded-lg border border-slate-300 bg-white px-2 py-1.5 text-xs font-bold text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                <option value="Ground Floor">Ground Floor</option>
                                <option value="Outdoor Seating">Outdoor Seating</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-500 uppercase">Table No.</label>
                            <select x-model="selectedTable" class="w-full rounded-lg border border-slate-300 bg-white px-2 py-1.5 text-xs font-bold text-slate-900 dark:border-slate-700 dark:bg-slate-800 dark:text-white">
                                <option value="" x-show="availableTables.length === 0" disabled>All tables are booked</option>
                                <template x-for="tbl in tables" :key="tbl">
                                    <option :value="tbl" :disabled="isTableReserved(tbl)" x-text="tbl + (isTableReserved(tbl) ? ' · Booked' : ' · Available')"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                    <p class="text-[11px] font-semibold text-slate-500 dark:text-slate-400" role="status">
                        <i class="fa-regular fa-clock mr-1 text-blue-500"></i>
                        <span x-text="reservedTables[selectedFloor]?.length ? reservedTables[selectedFloor].length + ' table(s) reserved. Reservations expire after 30 minutes.' : 'Tables are available for 30 minutes after an order.'"></span>
                    </p>
                </div>

                <!-- Special Instructions & Coupon Code -->
                <div class="space-y-2">
                    <input type="text" x-model="specialInstructions" placeholder="📝 Special Instructions (Optional...)" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white font-semibold">

                    <div class="flex gap-1">
                        <input type="text" x-model="couponCode" placeholder="🎟️ Coupon code (SAVE10)" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-xs text-slate-900 dark:text-white font-semibold">
                        <button @click="applyCoupon()" class="px-3 py-2 bg-slate-900 dark:bg-slate-800 text-white rounded-xl text-xs font-bold">Apply</button>
                    </div>
                    <p x-show="couponApplied" class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400">✓ SAVE10 applied! 10% discount</p>
                </div>

                <!-- Bill Calculation -->
                <div class="p-4 bg-blue-500/10 border border-blue-500/20 rounded-2xl space-y-2 text-xs">
                    <div class="flex justify-between text-slate-700 dark:text-slate-300 font-semibold">
                        <span>Subtotal</span>
                        <span x-text="'৳' + subtotal"></span>
                    </div>
                    <div class="flex justify-between text-slate-700 dark:text-slate-300 font-semibold">
                        <span>VAT (5%)</span>
                        <span x-text="'৳' + vat"></span>
                    </div>
                    <div class="flex justify-between text-slate-700 dark:text-slate-300 font-semibold">
                        <span>Service Charge (10%)</span>
                        <span x-text="'৳' + serviceCharge"></span>
                    </div>
                    <div x-show="couponApplied" class="flex justify-between text-emerald-600 dark:text-emerald-400 font-bold">
                        <span>Coupon Discount</span>
                        <span x-text="'-৳' + discountAmount"></span>
                    </div>
                    <div class="border-t border-blue-500/30 pt-2 flex justify-between text-slate-900 dark:text-white font-extrabold text-sm">
                        <span>Total Payable</span>
                        <span class="text-blue-600 dark:text-blue-400" x-text="'৳' + grandTotal"></span>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1.5">Payment Method</label>
                    <div class="grid grid-cols-3 gap-2 text-xs">
                        <button @click="paymentMethod = 'cash'" :class="paymentMethod === 'cash' ? 'bg-emerald-600 text-white' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300'" class="py-2 rounded-xl font-bold transition">💵 Cash</button>
                        <button @click="paymentMethod = 'bkash'" :class="paymentMethod === 'bkash' ? 'bg-pink-600 text-white' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300'" class="py-2 rounded-xl font-bold transition">📱 bKash</button>
                        <button @click="paymentMethod = 'nagad'" :class="paymentMethod === 'nagad' ? 'bg-rose-600 text-white' : 'bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300'" class="py-2 rounded-xl font-bold transition">📱 Nagad</button>
                    </div>
                    <div x-show="paymentMethod !== 'cash'" x-cloak class="mt-3 space-y-3 rounded-2xl border border-blue-200 bg-blue-50/80 p-4 dark:border-blue-500/30 dark:bg-blue-500/10">
                        <div class="rounded-xl border border-blue-200 bg-white p-3 dark:border-blue-400/20 dark:bg-slate-900">
                            <p class="text-[10px] font-bold uppercase tracking-wide text-slate-500 dark:text-slate-400">Send money to this BAUST Cafe number</p>
                            <p class="mt-1 flex items-center justify-between gap-3">
                                <span class="font-mono text-lg font-extrabold text-blue-800 dark:text-blue-200" x-text="paymentMethod === 'bkash' ? @js(config('cafe.payments.bkash_number')) : @js(config('cafe.payments.nagad_number'))"></span>
                                <span class="rounded-full bg-blue-100 px-2.5 py-1 text-[10px] font-extrabold uppercase text-blue-800 dark:bg-blue-500/20 dark:text-blue-200" x-text="paymentMethod"></span>
                            </p>
                        </div>
                        <p class="text-xs leading-5 text-slate-700 dark:text-slate-200">
                            Send ৳<span class="font-extrabold" x-text="grandTotal"></span> to the number above. Then enter the number you paid from and the transaction ID so Cafe Admin can verify your payment. Never share your PIN or OTP.
                        </p>
                        <label class="block space-y-1.5">
                            <span class="text-[11px] font-bold uppercase tracking-wide text-slate-700 dark:text-slate-300">bKash/Nagad number used to pay</span>
                            <input type="tel" x-model="paymentPhone" inputmode="numeric" autocomplete="tel" placeholder="01XXXXXXXXX" maxlength="11" pattern="01[3-9][0-9]{8}" :required="paymentMethod !== 'cash'" :disabled="paymentMethod === 'cash'"
                                   class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm font-semibold text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:outline-none dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                        </label>
                        <label class="block space-y-1.5">
                            <span class="text-[11px] font-bold uppercase tracking-wide text-slate-700 dark:text-slate-300">Transaction ID</span>
                            <input type="text" x-model="paymentTransactionId" autocomplete="off" placeholder="Enter payment TrxID" maxlength="100" :required="paymentMethod !== 'cash'" :disabled="paymentMethod === 'cash'"
                                   class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 text-sm font-semibold text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:outline-none dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                        </label>
                    </div>
                </div>

                <!-- Place Order Button -->
                <p x-show="orderError" x-text="orderError" class="rounded-xl border border-rose-300 bg-rose-50 p-3 text-xs font-bold text-rose-700 dark:border-rose-500/30 dark:bg-rose-500/10 dark:text-rose-300" role="alert"></p>
                <button @click="placeOrder()" :disabled="cartList.length === 0 || ordering" :class="cartList.length > 0 && !ordering ? 'bg-emerald-600 hover:bg-emerald-500 text-white shadow-lg' : 'bg-slate-300 dark:bg-slate-800 text-slate-500 cursor-not-allowed'" class="w-full py-3.5 rounded-2xl font-extrabold text-xs uppercase tracking-wider transition">
                    <span x-show="!ordering">PLACE ORDER (৳<span x-text="grandTotal"></span>)</span>
                    <span x-show="ordering">PLACING ORDER...</span>
                </button>
            </div>
        </div>
    </div>

    <!-- ORDER HISTORY TAB -->
    <div x-show="activeTab === 'orders'" class="space-y-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900 dark:text-white">{{ $isCafeAdmin ? 'Cafe Orders' : 'Your Cafe Orders' }}</h2>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Show your order token at the counter when collecting your food.</p>
        </div>
        @forelse($orders as $order)
            <article class="glass-card space-y-4 rounded-2xl border border-slate-200 p-5 dark:border-slate-800">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold text-slate-500 dark:text-slate-400">{{ $order->guest_name }} · {{ $order->created_at->format('M j, Y g:i A') }}</p>
                        <p class="mt-1 text-lg font-extrabold tracking-wide text-emerald-700 dark:text-emerald-300">{{ $order->order_token }}</p>
                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-400">{{ ucfirst($order->delivery_option) }}@if($order->delivery_option === 'dine-in') · {{ $order->floor }}, {{ $order->table_number }}@endif · ৳{{ number_format((float) $order->total_amount, 2) }}</p>
                        <p class="mt-1 text-xs font-bold text-slate-600 dark:text-slate-300">
                            Payment: {{ ucfirst($order->payment_method) }}
                            @if($order->payment_phone)
                                · From {{ $order->payment_phone }} · TrxID {{ $order->payment_transaction_id }}
                            @endif
                        </p>
                    </div>
                    <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-extrabold capitalize text-blue-900 dark:bg-blue-500/15 dark:text-blue-200">{{ $order->status }}</span>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach($order->items as $orderItem)
                        <span class="rounded-xl bg-slate-100 px-3 py-2 text-xs font-semibold text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                            {{ $orderItem->name }} × {{ $orderItem->quantity }} · {{ $orderItem->item_token }}
                        </span>
                    @endforeach
                </div>
                @if($isCafeAdmin)
                    <form action="{{ route('cafe.orders.status', $order) }}" method="POST" class="flex flex-wrap gap-2">
                        @csrf
                        <select name="status" class="rounded-xl border border-slate-300 bg-slate-50 px-3 py-2 text-xs font-bold dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                            @foreach(['preparing', 'ready', 'completed', 'cancelled'] as $status)
                                <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2 text-xs font-bold text-white hover:bg-blue-500">Update status</button>
                    </form>
                @endif
            </article>
        @empty
            <div class="glass-card rounded-2xl border border-dashed border-slate-300 p-10 text-center dark:border-slate-700">
                <i class="fa-solid fa-receipt text-3xl text-blue-500"></i>
                <p class="mt-3 text-sm font-bold text-slate-800 dark:text-white">No orders yet</p>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Choose something from the menu to place your first order.</p>
            </div>
        @endforelse
    </div>

    <!-- INDIVIDUAL FOOD ITEM TOKENS SUCCESS MODAL -->
    <div x-cloak x-show="showOrderModal" x-transition.opacity role="presentation"
         class="fixed inset-0 z-[70] flex items-center justify-center overflow-y-auto bg-slate-950/80 p-4 backdrop-blur-md"
         @click.self="showOrderModal = false" @keydown.escape.window="showOrderModal = false">
        <section role="dialog" aria-modal="true" aria-labelledby="cafe-order-success-title"
                 class="glass-card my-auto max-h-[90vh] w-full max-w-md space-y-4 overflow-y-auto rounded-3xl border border-slate-200 bg-white p-6 text-left shadow-2xl dark:border-slate-700 dark:bg-slate-900">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <h3 id="cafe-order-success-title" class="text-lg font-extrabold text-slate-900 dark:text-white">Order Placed Successfully!</h3>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">Your order tokens are ready.</p>
                </div>
                <button type="button" @click="showOrderModal = false" aria-label="Close order confirmation"
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl text-slate-500 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- List of Tokens for Each Food Item -->
            <div class="space-y-2.5 max-h-64 overflow-y-auto pr-1">
                <template x-for="item in placedTokensList" :key="item.token">
                    <div class="p-3.5 bg-emerald-500/10 border border-emerald-500/30 rounded-2xl flex items-center justify-between gap-3">
                        <div>
                            <span class="text-xs font-extrabold text-slate-900 dark:text-white block" x-text="item.name + ' (x' + item.qty + ')'"></span>
                            <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400" x-text="'৳' + (item.price * item.qty)"></span>
                        </div>
                        <div class="px-3.5 py-1.5 bg-emerald-600 text-white rounded-xl text-xs font-extrabold tracking-wider shadow-sm" x-text="item.token">
                        </div>
                    </div>
                </template>
            </div>

            <div class="rounded-xl border border-emerald-500/30 bg-emerald-500/10 p-3 text-center">
                <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Order Token</span>
                <span class="text-lg font-extrabold tracking-widest text-emerald-700 dark:text-emerald-300" x-text="orderToken"></span>
                <span class="mt-1 block text-xs font-semibold text-slate-600 dark:text-slate-400" x-text="'Total: ৳' + orderedTotal"></span>
            </div>

            <div class="pt-2 space-y-1 text-center border-t border-slate-100 dark:border-slate-800">
                <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400">💰 Token diye khabar nin!</p>
                <p x-show="deliveryOption === 'dine-in'" class="text-xs font-semibold text-slate-600 dark:text-slate-400" x-text="'📍 ' + selectedFloor + ', 🪑 ' + selectedTable"></p>
            </div>

            <a href="{{ route('cafe.index', ['tab' => 'orders']) }}" class="block w-full rounded-xl bg-emerald-600 py-3 text-center text-xs font-extrabold uppercase tracking-wider text-white shadow-md hover:bg-emerald-500">
                View My Orders
            </a>
        </section>
    </div>

</div>

<!-- Cafe Admin Modal -->
@auth
    @if(Auth::user()->role === 'cafe_admin')
        <div id="cafeModal" class="hidden fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
            <div class="glass-card max-w-md w-full rounded-3xl p-6 border border-slate-200 dark:border-slate-700 shadow-2xl relative bg-white dark:bg-slate-900">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="fa-solid fa-utensils text-blue-500"></i> Add Cafe Menu Item
                    </h3>
                    <button onclick="document.getElementById('cafeModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-700 dark:hover:text-white">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form action="{{ route('cafe.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Item Name</label>
                        <input type="text" name="name" required placeholder="e.g. Chicken Biryani" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs font-semibold">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Category</label>
                        <select name="category" required class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs font-semibold">
                            @foreach($categories as $k => $l)
                                <option value="{{ $k }}">{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Regular Price (৳)</label>
                            <input type="number" step="0.01" name="price" required placeholder="100.00" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs font-semibold">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Discount Price (৳)</label>
                            <input type="number" step="0.01" name="discount_price" placeholder="85.00" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs font-semibold">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">Description</label>
                        <textarea name="description" rows="2" placeholder="Ingredients or serving details..." class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-300 dark:border-slate-700 rounded-xl text-slate-900 dark:text-white text-xs font-semibold"></textarea>
                    </div>

                    <button type="submit" class="w-full py-3 rounded-xl font-bold bg-blue-600 hover:bg-blue-500 text-white text-xs uppercase shadow-md">
                        Save Cafe Item
                    </button>
                </form>
            </div>
        </div>
    @endif
@endauth

<script>
function cafeApp(menuItems, weeklyMenu, allMenuItems, initialReservations) {
    const requestedTab = new URLSearchParams(window.location.search).get('tab');

    return {
        activeTab: @js($isCafeAdmin)
            ? (['orders', 'inventory', 'staff'].includes(requestedTab) ? requestedTab : 'orders')
            : (requestedTab === 'orders' ? 'orders' : 'menu'),
        searchQuery: '',
        showAvailableOnly: false,
        selectedCategory: 'All',
        deliveryOption: 'dine-in',
        selectedFloor: 'Ground Floor',
        selectedTable: 'Table 1',
        reservedTables: initialReservations || {},
        maxPrice: 100,
        specialInstructions: '',
        couponCode: '',
        couponApplied: false,
        paymentMethod: 'cash',
        paymentPhone: '',
        paymentTransactionId: '',
        showOrderModal: false,
        placedTokensList: [],
        ordering: false,
        orderError: '',
        orderToken: '',
        orderedTotal: 0,

        tables: ['Table 1', 'Table 2', 'Table 3', 'Table 4', 'Table 5', 'Table 6', 'Table 7', 'Table 8', 'Table 9', 'Table 10', 'Table 11', 'Table 12'],

        cart: {},

        menuItems: menuItems,
        allMenuItems: allMenuItems,
        weeklyMenu: weeklyMenu,

        get filteredMenuItems() {
            return this.menuItems.filter(item => {
                const matchCat = this.selectedCategory === 'All' || item.category === this.selectedCategory;
                const matchSearch = !this.searchQuery || item.name.toLowerCase().includes(this.searchQuery.toLowerCase());
                const matchAvail = !this.showAvailableOnly || item.available;
                const matchPrice = item.price <= this.maxPrice;
                return matchCat && matchSearch && matchAvail && matchPrice;
            });
        },

        isTableReserved(table) {
            return (this.reservedTables[this.selectedFloor] || []).includes(table);
        },

        get availableTables() {
            return this.tables.filter(table => !this.isTableReserved(table));
        },

        async loadReservedTables() {
            const floor = this.selectedFloor;
            try {
                const response = await fetch(@json(route('cafe.tables.availability')) + '?floor=' + encodeURIComponent(floor), {
                    headers: { 'Accept': 'application/json' },
                });
                if (!response.ok) {
                    throw new Error('Unable to refresh cafe table availability.');
                }

                const result = await response.json();
                this.reservedTables = { ...this.reservedTables, [floor]: result.reserved };
                if (this.selectedFloor === floor && this.isTableReserved(this.selectedTable)) {
                    this.selectedTable = this.tables.find(table => !this.isTableReserved(table)) || '';
                } else if (this.selectedFloor === floor && !this.selectedTable) {
                    this.selectedTable = this.tables.find(table => !this.isTableReserved(table)) || '';
                }
                return true;
            } catch (error) {
                this.orderError = 'Unable to refresh table availability. Please reload the page before ordering.';
                return false;
            }
        },

        get cartList() {
            const list = [];
            for (const id in this.cart) {
                const found = this.allMenuItems.find(m => m.id == id);
                if (found && this.cart[id] > 0) {
                    list.push({ ...found, qty: this.cart[id] });
                }
            }
            return list;
        },

        get cartItemCount() {
            let count = 0;
            for (const id in this.cart) {
                count += this.cart[id];
            }
            return count;
        },

        get subtotal() {
            let sum = 0;
            for (const item of this.cartList) {
                sum += item.price * item.qty;
            }
            return sum;
        },

        get vat() { return Math.round(this.subtotal * 0.05); },
        get serviceCharge() { return Math.round(this.subtotal * 0.10); },
        get discountAmount() { return this.couponApplied ? Math.round((this.subtotal + this.vat + this.serviceCharge) * 0.10) : 0; },
        get grandTotal() { return (this.subtotal + this.vat + this.serviceCharge) - this.discountAmount; },

        addToCart(item) {
            if (!item.available) return;
            this.cart[item.id] = (this.cart[item.id] || 0) + 1;
        },

        addWeeklyMealToCart(meal) {
            const item = this.allMenuItems.find(menuItem => menuItem.id === meal.id);
            if (item) this.addToCart(item);
            this.activeTab = 'cart';
            this.orderError = '';
        },

        increaseQty(id) { this.cart[id] = (this.cart[id] || 0) + 1; },
        decreaseQty(id) {
            if (this.cart[id] > 1) {
                this.cart[id]--;
            } else {
                delete this.cart[id];
            }
        },
        removeItem(id) { delete this.cart[id]; },
        clearCart() { this.cart = {}; },

        applyCoupon() {
            if (this.couponCode.trim().toUpperCase() === 'SAVE10') {
                this.couponApplied = true;
            }
        },

        async placeOrder() {
            if (this.cartList.length === 0) return;
            this.ordering = true;
            this.orderError = '';

            try {
                if (this.deliveryOption === 'dine-in') {
                    const tablesLoaded = await this.loadReservedTables();
                    if (!tablesLoaded) return;
                    if (!this.selectedTable || this.isTableReserved(this.selectedTable)) {
                        this.orderError = 'Please choose an available table before placing your order.';
                        return;
                    }
                }

                const response = await fetch(@json(route('cafe.orders.store')), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': @json(csrf_token()),
                    },
                    body: JSON.stringify({
                        items: this.cartList.map(item => ({ id: item.id, quantity: item.qty })),
                        delivery_option: this.deliveryOption,
                        floor: this.deliveryOption === 'dine-in' ? this.selectedFloor : null,
                        table_number: this.deliveryOption === 'dine-in' ? this.selectedTable : null,
                        payment_method: this.paymentMethod,
                        payment_phone: this.paymentMethod === 'cash' ? null : this.paymentPhone,
                        payment_transaction_id: this.paymentMethod === 'cash' ? null : this.paymentTransactionId,
                        special_instructions: this.specialInstructions || null,
                        coupon_code: this.couponApplied ? 'SAVE10' : null,
                    }),
                });

                const result = await response.json();
                if (!response.ok) {
                    this.orderError = Object.values(result.errors || {})[0]?.[0] || result.message || 'Unable to place your order. Please try again.';
                    return;
                }

                this.placedTokensList = result.items;
                this.orderToken = result.orderToken;
                this.orderedTotal = result.total;
                this.showOrderModal = true;
                this.clearCart();
                this.specialInstructions = '';
                this.couponCode = '';
                this.couponApplied = false;
                this.paymentPhone = '';
                this.paymentTransactionId = '';
            } catch (error) {
                this.orderError = 'Could not reach the Cafe service. Please check your connection and try again.';
            } finally {
                this.ordering = false;
            }
        }
    };
}
</script>
@endsection
