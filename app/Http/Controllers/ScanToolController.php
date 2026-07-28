<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use Inertia\Inertia;
use Inertia\Response;

class ScanToolController extends Controller
{
    public function __invoke(string $unique_code): Response
    {
        $tool = Tool::with(['lab', 'creator', 'primaryAttachment'])
            ->where('unique_code', $unique_code)
            ->firstOrFail();

        return Inertia::render('Features/Scan/Pages/ScanToolPage', [
            'tool' => [
                'unique_code' => $tool->unique_code,
                'name' => $tool->name,
                'lab' => $tool->lab?->name,
                'added_by' => $tool->creator?->name,
                'added_on' => $tool->created_at->format('d M Y'),
                'image' => $tool->primaryAttachment?->file_url,
            ],
        ]);
    }
}
