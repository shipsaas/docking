<?php

namespace App\Services\Translations;

use App\Models\Translation;
use App\Models\TranslationGroup;
use Illuminate\Translation\ArrayLoader;

class DatabaseLoader extends ArrayLoader
{
    public function load($locale, $group, $namespace = null)
    {
        // Laravel uses these 2 indicators to load json files (en.json, vi.json), we don't do that here
        if ($group === '*' && $namespace === '*') {
            return [];
        }

        $translationGroup = TranslationGroup::where('key', $group)->first();
        if (!$translationGroup) {
            return [];
        }

        if (is_null($namespace) || $namespace === '*') {
            return $this->loadAllTranslationsFromGroup($translationGroup, $locale);
        }

        return [];
    }

    protected function loadAllTranslationsFromGroup(TranslationGroup $translationGroup, string $locale): array
    {
        return $translationGroup->translations->mapWithKeys(function (Translation $translation) use ($locale, $translationGroup) {
            $keyWithoutGroup = str_replace("{$translationGroup->key}.", '', $translation->key);

            return [
                $keyWithoutGroup => $translation->text[$locale] ?? '',
            ];
        })->toArray();
    }
}
