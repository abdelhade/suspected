<?php

namespace App\Http\Controllers;

use App\Models\Weapon;
use App\Models\LookupOption;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WeaponController extends Controller
{
    private function lookups(): array
    {
        return [
            'weaponTypes'     => LookupOption::valuesFor('weapon_type'),
            'classifications' => LookupOption::valuesFor('weapon_classification'),
            'statuses'        => LookupOption::valuesFor('weapon_status'),
        ];
    }

    /**
     * قائمة الأسلحة مع الفلترة والبحث
     */
    public function index(Request $request): View
    {
        $query = Weapon::query()->latest();

        if ($request->filled('weapon_type')) {
            $query->where('weapon_type', $request->weapon_type);
        }

        if ($request->filled('classification')) {
            $query->where('classification', $request->classification);
        }

        if ($request->filled('current_status')) {
            $query->where('current_status', $request->current_status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('serial_number',          'like', "%{$search}%")
                  ->orWhere('brand_make',            'like', "%{$search}%")
                  ->orWhere('related_report_number', 'like', "%{$search}%")
                  ->orWhere('holder_info',           'like', "%{$search}%")
                  ->orWhere('notes',                 'like', "%{$search}%");
            });
        }

        $weapons = $query->paginate(15)->withQueryString();

        return view('weapons.index', array_merge(['weapons' => $weapons], $this->lookups()));
    }

    /**
     * نموذج إضافة سلاح جديد
     */
    public function create(): View
    {
        return view('weapons.create', $this->lookups());
    }

    /**
     * حفظ سلاح جديد
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'weapon_type'           => 'nullable|string|max:100',
            'caliber'               => 'nullable|string|max:50',
            'brand_make'            => 'nullable|string|max:150',
            'serial_number'         => 'nullable|string|max:100',
            'classification'        => 'nullable|string|max:100',
            'current_status'        => 'nullable|string|max:100',
            'related_report_number' => 'nullable|string|max:100',
            'holder_info'           => 'nullable|string',
            'capture_date_time'     => 'nullable|date',
            'weapon_condition'      => 'nullable|string',
            'notes'                 => 'nullable|string',
        ]);

        $weapon = Weapon::create($validated);

        return redirect()
            ->route('weapons.show', $weapon)
            ->with('success', 'تم إضافة السلاح بنجاح.');
    }

    /**
     * عرض تفاصيل سلاح
     */
    public function show(Weapon $weapon): View
    {
        return view('weapons.show', compact('weapon'));
    }

    /**
     * نموذج التعديل
     */
    public function edit(Weapon $weapon): View
    {
        return view('weapons.edit', array_merge(['weapon' => $weapon], $this->lookups()));
    }

    /**
     * تحديث بيانات السلاح
     */
    public function update(Request $request, Weapon $weapon): RedirectResponse
    {
        $validated = $request->validate([
            'weapon_type'           => 'nullable|string|max:100',
            'caliber'               => 'nullable|string|max:50',
            'brand_make'            => 'nullable|string|max:150',
            'serial_number'         => 'nullable|string|max:100',
            'classification'        => 'nullable|string|max:100',
            'current_status'        => 'nullable|string|max:100',
            'related_report_number' => 'nullable|string|max:100',
            'holder_info'           => 'nullable|string',
            'capture_date_time'     => 'nullable|date',
            'weapon_condition'      => 'nullable|string',
            'notes'                 => 'nullable|string',
        ]);

        $weapon->update($validated);

        return redirect()
            ->route('weapons.show', $weapon)
            ->with('success', 'تم تحديث بيانات السلاح بنجاح.');
    }

    /**
     * حذف السلاح (Soft Delete)
     */
    public function destroy(Weapon $weapon): RedirectResponse
    {
        $weapon->delete();

        return redirect()
            ->route('weapons.index')
            ->with('success', 'تم حذف السلاح بنجاح.');
    }
}
