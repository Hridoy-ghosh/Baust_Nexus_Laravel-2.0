<?php

namespace App\Http\Controllers;

use App\Models\PortalStaff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortalStaffController extends Controller
{
    public function storeCafeStaff(Request $request)
    {
        return $this->store($request, 'cafe');
    }

    public function storeLibraryStaff(Request $request)
    {
        return $this->store($request, 'library');
    }

    public function destroyCafeStaff(PortalStaff $staff)
    {
        return $this->destroy($staff, 'cafe');
    }

    public function destroyLibraryStaff(PortalStaff $staff)
    {
        return $this->destroy($staff, 'library');
    }

    private function store(Request $request, string $unit)
    {
        if (! $this->canManageUnit($unit)) {
            abort(403, 'You are not allowed to manage this staff list.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'duty_hours' => 'required|string|max:100',
        ]);
        $validated['unit'] = $unit;
        $validated['created_by'] = Auth::id();

        PortalStaff::create($validated);

        return back()->with('success', ucfirst($unit).' staff member added.');
    }

    private function destroy(PortalStaff $staff, string $unit)
    {
        if (! $this->canManageUnit($unit)) {
            abort(403, 'You are not allowed to manage this staff list.');
        }
        abort_unless($staff->unit === $unit, 404);

        $staff->delete();

        return back()->with('success', ucfirst($unit).' staff member removed.');
    }

    private function canManageUnit(string $unit): bool
    {
        if (! Auth::check()) {
            return false;
        }

        return $unit === 'cafe'
            ? Auth::user()->role === 'cafe_admin'
            : in_array(Auth::user()->role, ['library_admin', 'admin'], true);
    }
}
