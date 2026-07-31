<?php

namespace App\Console\Commands;

use App\Models\RawMaterial;
use App\Models\Tool;
use App\Support\UniqueCodeGenerator;
use Illuminate\Console\Command;

class BackfillUniqueCodes extends Command
{
    protected $signature = 'app:backfill-unique-codes';

    protected $description = 'Generate unique codes for existing raw materials and tools that do not have one yet.';

    public function handle(): int
    {
        $this->backfillModel(RawMaterial::class, 'BAHAN', 'raw_materials');
        $this->backfillModel(Tool::class, 'ALAT', 'tools');

        return self::SUCCESS;
    }

    private function backfillModel(string $modelClass, string $type, string $table): void
    {
        $items = $modelClass::whereNull('unique_code')->get();

        if ($items->isEmpty()) {
            $this->line("  <fg=gray>No {$type} items need backfill.</>");

            return;
        }

        $bar = $this->output->createProgressBar($items->count());
        $bar->setFormat(" <fg=cyan>{$type}</> %current%/%max% [%bar%] %percent:3s%%");
        $bar->start();

        foreach ($items as $item) {
            $item->updateQuietly([
                'unique_code' => UniqueCodeGenerator::generate($type, $item->created_at, $table),
            ]);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("  Backfilled {$items->count()} {$type} items.");
    }
}
