<?php

namespace App\Services;

use Stichoza\GoogleTranslate\GoogleTranslate;

class AutoTranslationService
{
    /**
     * Mengisi terjemahan yang kosong secara otomatis.
     * * @param array $data Array format ['id' => '...', 'en' => '...']
     * @return array
     */
    public function fillMissingTranslations(array $data): array
    {
        // Pastikan key ada untuk menghindari error undefined index
        $id = $data['id'] ?? null;
        $en = $data['en'] ?? null;

        // Skenario 1: Ada ID, tapi EN kosong -> Translate ID ke EN
        if (!empty($id) && empty($en)) {
            $data['en'] = GoogleTranslate::trans($id, 'en', 'id');
        }

        // Skenario 2: Ada EN, tapi ID kosong -> Translate EN ke ID
        elseif (!empty($en) && empty($id)) {
            $data['id'] = GoogleTranslate::trans($en, 'id', 'en');
        }

        return $data;
    }
}
