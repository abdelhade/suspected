<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\ReportType;
use App\Models\ReportStatus;
use App\Models\LookupOption;

class LookupController extends Controller
{
    // =========================================================================
    // القديم — للمحاضر (report_type / report_status)
    // =========================================================================

    public function storeType(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:report_types,name|max:255'
        ]);

        $type = ReportType::create($validated);

        return response()->json(['success' => true, 'data' => $type]);
    }

    public function storeStatus(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:report_statuses,name|max:255'
        ]);

        $status = ReportStatus::create($validated);

        return response()->json(['success' => true, 'data' => $status]);
    }

    // =========================================================================
    // الجديد — endpoint عام لأي مجموعة
    // POST /lookups/options   { group: "weapon_type", value: "جديد" }
    // =========================================================================

    /**
     * المجموعات المسموح بها (whitelist)
     */
    private const ALLOWED_GROUPS = [
        'weapon_type',
        'weapon_classification',
        'weapon_status',
        'registration_category',
        'danger_level',
        'suspect_status',
        'body_build',
        'skin_color',
    ];

    public function storeOption(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'group' => ['required', 'string', 'in:' . implode(',', self::ALLOWED_GROUPS)],
            'value' => 'required|string|max:255',
        ]);

        // firstOrCreate — لو موجود بيرجعه بدل ما يطلع خطأ
        $option = LookupOption::firstOrCreate(
            ['group' => $validated['group'], 'value' => trim($validated['value'])],
            ['sort'  => LookupOption::where('group', $validated['group'])->max('sort') + 1],
        );

        return response()->json([
            'success'   => true,
            'data'      => ['value' => $option->value],
            'was_new'   => $option->wasRecentlyCreated,
        ]);
    }
}
