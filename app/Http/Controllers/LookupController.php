<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReportType;
use App\Models\ReportStatus;

class LookupController extends Controller
{
    public function storeType(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:report_types,name|max:255'
        ]);

        $type = ReportType::create($validated);

        return response()->json([
            'success' => true,
            'data' => $type
        ]);
    }

    public function storeStatus(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:report_statuses,name|max:255'
        ]);

        $status = ReportStatus::create($validated);

        return response()->json([
            'success' => true,
            'data' => $status
        ]);
    }
}
