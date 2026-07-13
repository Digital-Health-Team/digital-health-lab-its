<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UniqueCodeGenerator
{
    /**
     * Generate a unique code for a material or tool.
     *
     * Format: IDIG-{TYPE}-{YYYYMMDD}-{6CHAR}
     *
     * @param  string  $type  'BAHAN' or 'ALAT'
     * @param  string  $table  DB table to check uniqueness against
     */
    public static function generate(string $type, \DateTimeInterface $date, string $table): string
    {
        $datePart = $date->format('Ymd');

        do {
            $suffix = strtoupper(Str::random(6));
            $code = "IDIG-{$type}-{$datePart}-{$suffix}";
        } while (DB::table($table)->where('unique_code', $code)->exists());

        return $code;
    }
}
