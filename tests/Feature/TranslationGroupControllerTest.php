<?php

namespace Tests\Feature;

use App\Models\Translation;
use App\Models\TranslationGroup;
use Tests\TestCase;

class TranslationGroupControllerTest extends TestCase
{
    public function testIndexReturnsAllTranslationGroups()
    {
        $baseGroup = TranslationGroup::factory()->create([
            'key' => 'base',
            'name' => 'Base Group',
        ]);
        $invoiceGroup = TranslationGroup::factory()->create([
            'key' => 'invoice',
            'name' => 'Invoice Group',
        ]);

        $response = $this->get('api/v1/translation-groups');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'key' => 'base',
                'name' => 'Base Group',
            ])
            ->assertJsonFragment([
                'key' => 'invoice',
                'name' => 'Invoice Group',
            ]);
    }

    public function testShowReturnsAGivenTranslationGroup()
    {
        $pricingGroup = TranslationGroup::factory()->create([
            'key' => 'pricing',
            'name' => 'Pricing Group',
        ]);

        $response = $this->get("api/v1/translation-groups/{$pricingGroup->uuid}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'key' => 'pricing',
                'name' => 'Pricing Group',
            ]);
    }

    public function testStoreCreatesANewTranslationGroup()
    {
        $response = $this->json('POST', "api/v1/translation-groups", [
            'key' => 'hasta',
            'name' => 'Hasta la vista',
            'description' => 'baby',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'created' => true,
            ]);

        $this->assertDatabaseHas((new TranslationGroup())->getTable(), [
            'key' => 'hasta',
            'name' => 'Hasta la vista',
            'description' => 'baby',
        ]);
    }

    public function testStoreReturnsErrorOnDuplicatedTranslationGroupCode()
    {
        $pricingGroup = TranslationGroup::factory()->create([
            'key' => 'pricing',
            'name' => 'Pricing Group',
        ]);

        $response = $this->json('POST', "api/v1/translation-groups", [
            'key' => 'pricing',
            'name' => 'Pricing Group V2',
            'description' => 'Pricing Group V2',
        ]);

        $response->assertUnprocessable();
    }

    public function testUpdateUpdatesAGivenTranslationGroup()
    {
        $invoiceGroup = TranslationGroup::factory()->create([
            'key' => 'invoice',
            'name' => 'Invoice Group',
        ]);

        $response = $this->json('PUT', "api/v1/translation-groups/{$invoiceGroup->uuid}", [
            'name' => 'Invoice Group PRO MAX',
            'description' => 'My name is invoicing group',
        ]);

        $response->assertOk()
            ->assertJsonFragment([
                'updated' => true,
            ]);

        $this->assertDatabaseHas((new TranslationGroup())->getTable(), [
            'key' => 'invoice',
            'name' => 'Invoice Group PRO MAX',
            'description' => 'My name is invoicing group',
        ]);
    }

    public function testDestroyDeletesAGivenTranslationGroup()
    {
        $railGroup = TranslationGroup::factory()->create([
            'key' => 'rail',
            'name' => 'Rail Group',
        ]);
        $translation = Translation::factory()->create([
            'translation_group_id' => $railGroup->uuid,
        ]);

        $response = $this->json('DELETE', "api/v1/translation-groups/{$railGroup->uuid}");

        $response->assertOk()
            ->assertJsonFragment([
                'deleted' => true,
            ]);

        $this->assertDatabaseMissing((new TranslationGroup())->getTable(), [
            'key' => 'rail',
        ]);
        $this->assertDatabaseMissing((new Translation())->getTable(), [
            'uuid' => $translation->uuid,
        ]);
    }
}
