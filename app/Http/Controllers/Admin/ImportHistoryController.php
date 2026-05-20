<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LegacyImport;
use App\Exports\ImportResultExport;
use Maatwebsite\Excel\Facades\Excel;
use Inertia\Inertia;

class ImportHistoryController extends Controller
{
    public function index()
    {
        $imports = LegacyImport::with('user:id,username')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('Admin/Imports/History', [
            'imports' => $imports
        ]);
    }

    public function download(LegacyImport $import)
    {
        $details = json_decode($import->results_details, true);

        if (!$details) return back()->with('error', 'No hay detalles para esta importación.');

        return Excel::download(
            new ImportResultExport($details),
            "Resultado_{$import->import_type}_{$import->id}.xlsx"
        );
    }
}
