<?php

namespace Tests\Unit\Models;

use App\Models\Translation;
use App\Models\TranslationGroup;
use Tests\TestCase;

class TranslationGroupTest extends TestCase
{
    public function testTranslationGroupHasManyTranslations()
    {
        /**
         * @var TranslationGroup $template
         */
        $group = TranslationGroup::factory()->create();
        $translations = Translation::factory()->count(2)
            ->create([
                'translation_group_id' => $group->uuid,
            ]);

        $foundTranslations = $group->translations()->get();

        $this->assertSame(
            $translations->pluck('uuid')->toArray(),
            $foundTranslations->pluck('uuid')->toArray()
        );
    }
}
