<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RawMaterial;
use App\Models\Tool;
use chillerlan\QRCode\Output\QRMarkupSVG;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Http\Response;

class PrintLabelController extends Controller
{
    public function __invoke(string $type, int $id): Response|\Illuminate\Contracts\View\View
    {
        [$item, $scanUrl] = match ($type) {
            'tool' => $this->resolveTool($id),
            'material' => $this->resolveMaterial($id),
            default => abort(404),
        };

        $svg = $this->generateSvg($scanUrl);

        return view('print.item-label', [
            'item' => $item,
            'type' => $type,
            'scanUrl' => $scanUrl,
            'qrSvg' => $svg,
        ]);
    }

    private function resolveTool(int $id): array
    {
        $tool = Tool::with('lab')->findOrFail($id);
        $scanUrl = route('scan.tool', $tool->unique_code);

        return [
            [
                'name' => $tool->name,
                'unique_code' => $tool->unique_code,
                'subtitle' => $tool->lab?->name,
            ],
            $scanUrl,
        ];
    }

    private function resolveMaterial(int $id): array
    {
        $material = RawMaterial::with('brand')->findOrFail($id);
        $scanUrl = route('scan.material', $material->unique_code);

        return [
            [
                'name' => $material->name,
                'unique_code' => $material->unique_code,
                'subtitle' => $material->brand?->name,
            ],
            $scanUrl,
        ];
    }

    private function generateSvg(string $data): string
    {
        $options = new QROptions;
        $options->outputType = QRMarkupSVG::class;
        $options->outputBase64 = false;
        $options->svgDefs = '<style>rect{fill:#000}</style>';

        return (new QRCode($options))->render($data);
    }
}
