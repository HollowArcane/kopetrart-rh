<?php

namespace App\Http\Controllers\Test;

use App\Models\Test\Test;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TestCandidateFileController
{
    public function download_test($id)
    {
        $test = DB::table('test_candidate')->where('id', $id)->get();

        if (!$test || !Storage::exists($test->file))
        { abort(404, 'File not found'); }

        return Storage::download($test->file);
    }

    public function download_attachment($id)
    {
        $test = DB::table('test')->where('id', $id)->first();

        if (!isset($test) || !Storage::exists('public/'.$test->file))
        { abort(404, 'File not found'); }

        return Storage::download('public/'.$test->file);
    }

    public function download_cv($id)
    {
        $test = DB::table('test_candidate')->where('id', $id)->first();

        if (!isset($test) || !Storage::exists('test-candidate/'.$test->file))
        { abort(404, 'File not found'); }

        return Storage::download('test-candidate/'.$test->file);
    }

    public function download_pdf($id)
    {
        $test = Test::with(['parts', 'criteria', 'points.importance'])
                        ->findOrFail($id);

        // Calculate total duration
        $totalDuration = $test->parts->sum('duration');

        // Calculate total coefficient
        $totalCoefficient = $test->criteria->sum('coefficient');

        // Get importance colors
        $importanceColors = [
            'Blocant' => '#FF0000',   // Red
            'Important' => '#FFD700',  // Yellow
            'Bonus' => '#0000FF'       // Blue
        ];

        // Generate PDF
        $pdf = FacadePdf::loadView('pages.test.pdf', compact(
            'test',
            'totalDuration',
            'totalCoefficient',
            'importanceColors'
        ));

        return $pdf->stream("test-{$id}.pdf");
    }
}
