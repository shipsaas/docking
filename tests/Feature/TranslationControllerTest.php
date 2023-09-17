<?php

namespace Tests\Feature;

use App\Models\Language;
use App\Models\Translation;
use App\Models\TranslationGroup;
use Tests\TestCase;

class TranslationControllerTest extends TestCase
{
    public function testIndexReturnsAllTranslationGroups()
    {
        $baseGroup = TranslationGroup::factory()->create([
            'key' => 'base',
            'name' => 'Base Group',
        ]);
        $loveTranslation = Translation::factory()->create([
            'translation_group_id' => $baseGroup->uuid,
            'key' => 'base.love',
            'name' => 'Love',
            'text' => [
                'en' => 'Love',
                'vi' => 'Yêu',
            ],
        ]);
        $youTranslation = Translation::factory()->create([
            'translation_group_id' => $baseGroup->uuid,
            'key' => 'base.you',
            'name' => 'You',
            'text' => [
                'en' => 'You',
                'vi' => 'Bạn',
            ],
        ]);

        $response = $this->get('api/v1/translations');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'key' => 'base.love',
                'name' => 'Love',
                'text' => [
                    'en' => 'Love',
                    'vi' => 'Yêu',
                ],
            ])
            ->assertJsonFragment([
                'key' => 'base.you',
                'name' => 'You',
                'text' => [
                    'en' => 'You',
                    'vi' => 'Bạn',
                ],
            ])
            ->assertJsonFragment([
                'key' => 'base',
                'name' => 'Base Group',
            ]);
    }

    public function testShowReturnsAGivenTranslation()
    {
        $youTranslation = Translation::factory()->create([
            'key' => 'base.you',
            'name' => 'You',
            'text' => [
                'en' => 'You',
                'vi' => 'Bạn',
            ],
        ]);

        $response = $this->get("api/v1/translations/{$youTranslation->uuid}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'key' => 'base.you',
                'name' => 'You',
                'text' => [
                    'en' => 'You',
                    'vi' => 'Bạn',
                ],
            ]);
    }

    public function testStoreCreatesANewTranslation()
    {
        $pricingGroup = TranslationGroup::factory()->create([
            'key' => 'pricing',
            'name' => 'Pricing Group',
        ]);
        $language = Language::factory()->create([
            'code' => 'en',
        ]);

        $this->json('POST', "api/v1/translations", [
            'translation_group_id' => $pricingGroup->uuid,
            'key' => 'expensive',
            'name' => 'Expensive',
            'text' => [
                'en' => 'Expensive (expensive)',
            ],
        ])->assertOk()
            ->assertJsonFragment([
                'created' => true,
            ]);

        $this->assertDatabaseHas((new Translation())->getTable(), [
            'key' => 'pricing.expensive',
            'name' => 'Expensive',
            'text->en' => 'Expensive (expensive)',
        ]);
    }

    public function testStoreReturnsErrorOnInvalidTextFormat()
    {
        $pricingGroup = TranslationGroup::factory()->create([
            'key' => 'pricing',
            'name' => 'Pricing Group',
        ]);
        $language = Language::factory()->create([
            'code' => 'en',
        ]);
        $language = Language::factory()->create([
            'code' => 'vi',
        ]);

        $this->json('POST', "api/v1/translations", [
            'translation_group_id' => $pricingGroup->uuid,
            'key' => 'inexpensive',
            'name' => 'Inexpensive',
            'text' => [
                'en' => 'Inexpensive',
                // missing vi
            ],
        ])->assertUnprocessable();

        $this->json('POST', "api/v1/translations", [
            'translation_group_id' => $pricingGroup->uuid,
            'key' => 'inexpensive',
            'name' => 'Inexpensive',
            'text' => [1, 2, 3], // wrong type
        ])->assertUnprocessable();

    }

    public function testUpdateUpdatesAGivenLanguage()
    {
        $oldGroup = TranslationGroup::factory()->create([
            'key' => 'old',
            'name' => 'Old Group',
        ]);
        $language = Language::factory()->create([
            'code' => 'en',
        ]);
        $translation = Translation::factory()->create([
            'translation_group_id' => $oldGroup->uuid,
            'key' => 'due-date',
            'text' => [
                'en' => 'Due date',
            ],
        ]);

        $invoiceGroup = TranslationGroup::factory()->create([
            'key' => 'invoice',
            'name' => 'Invoice Group',
        ]);

        $response = $this->json('PUT', "api/v1/translations/{$translation->uuid}", [
            'translation_group_id' => $invoiceGroup->uuid,
            'key' => 'due-date-v2',
            'name' => 'due date v2',
            'text' => [
                'en' => 'Due date ok',
            ],
        ]);

        $response->assertOk()
            ->assertJsonFragment([
                'updated' => true,
            ]);

        $this->assertDatabaseHas((new Translation())->getTable(), [
            'translation_group_id' => $invoiceGroup->uuid,
            'key' => 'invoice.due-date-v2',
            'name' => 'due date v2',
            'text->en' => 'Due date ok',
        ]);
    }

    public function testDestroyDeletesAGivenTranslation()
    {
        $translation = Translation::factory()->create([
            'key' => 'to-be-deleted',
        ]);

        $response = $this->json('DELETE', "api/v1/translations/{$translation->uuid}");

        $response->assertOk()
            ->assertJsonFragment([
                'deleted' => true,
            ]);

        $this->assertDatabaseMissing((new TranslationGroup())->getTable(), [
            'key' => 'to-be-deleted',
        ]);
    }
}
