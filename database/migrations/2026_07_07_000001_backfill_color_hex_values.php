<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * eSUN filament catalog hex values for the seeded color names.
     * Translucent intentionally has no hex (rendered as a neutral swatch).
     *
     * @var array<string, string>
     */
    private const HEX_MAP = [
        'Black' => '#1C1C1C',
        'White' => '#F4F4F4',
        'Silver' => '#C0C0C0',
        'Cold White' => '#EDF2F7',
        'Blue' => '#2563EB',
        'Fire Engine Red' => '#CE2029',
        'Pink' => '#F472B6',
        'Light Grey' => '#D1D5DB',
        'Light Blue' => '#93C5FD',
        'Natural' => '#EDE0CE',
        'Grey' => '#808080',
        'Orange' => '#F97316',
        'Purple' => '#7C3AED',
        'Green' => '#16A34A',
        'Beige' => '#E8DCC8',
        'Bone White' => '#E3DAC9',
        'Grape Purple' => '#6B21A8',
        'Yellow' => '#FACC15',
        'Sky Blue' => '#38BDF8',
        'Dark Blue' => '#1E3A8A',
        'Brown' => '#8B5A2B',
        'Barbie Pink' => '#E0218A',
        'Red' => '#DC2626',
        'Peak Green' => '#00A86B',
        'Holly Green' => '#0B6E4F',
        'Olive Green' => '#708238',
        'Concrete Grey' => '#95A5A6',
        'Aqua' => '#00BCD4',
        'RGB Red' => '#EF0107',
    ];

    public function up(): void
    {
        foreach (self::HEX_MAP as $name => $hex) {
            DB::table('colors')
                ->where('name', $name)
                ->whereNull('hex')
                ->update(['hex' => $hex]);
        }
    }

    public function down(): void
    {
        foreach (self::HEX_MAP as $name => $hex) {
            DB::table('colors')
                ->where('name', $name)
                ->where('hex', $hex)
                ->update(['hex' => null]);
        }
    }
};
