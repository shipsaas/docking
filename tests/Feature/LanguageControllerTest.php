<?php

namespace Tests\Feature;

use App\Models\Language;
use Tests\TestCase;

class LanguageControllerTest extends TestCase
{
    public function testIndexReturnsAllLanguages()
    {
        $enLang = Language::factory()->create([
            'code' => 'en',
            'name' => 'English',
        ]);
        $viLang = Language::factory()->create([
            'code' => 'vi',
            'name' => 'Vietnamese',
        ]);

        $response = $this->get('api/v1/languages');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'code' => 'en',
                'name' => 'English',
            ])
            ->assertJsonFragment([
                'code' => 'vi',
                'name' => 'Vietnamese',
            ]);
    }

    public function testShowReturnsAGivenLanguage()
    {
        $enLang = Language::factory()->create([
            'code' => 'en',
            'name' => 'English',
        ]);

        $response = $this->get("api/v1/languages/{$enLang->uuid}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'code' => 'en',
                'name' => 'English',
            ]);
    }

    public function testStoreCreatesANewLanguage()
    {
        $response = $this->json('POST', "api/v1/languages", [
            'code' => 'vi-VN',
            'name' => 'Vietnamese',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'created' => true,
            ]);

        $this->assertDatabaseHas((new Language())->getTable(), [
            'code' => 'vi-VN',
            'name' => 'Vietnamese',
        ]);
    }

    public function testStoreReturnsErrorOnDuplicatedLangCode()
    {
        $enLang = Language::factory()->create([
            'code' => 'en',
            'name' => 'English',
        ]);

        $response = $this->json('POST', "api/v1/languages", [
            'code' => 'en',
            'name' => 'English V2',
        ]);

        $response->assertUnprocessable();
    }

    public function testUpdateUpdatesAGivenLanguage()
    {
        $enLang = Language::factory()->create([
            'code' => 'en',
            'name' => 'English',
        ]);

        $response = $this->json('PUT', "api/v1/languages/{$enLang->uuid}", [
            'name' => 'English V2',
        ]);

        $response->assertOk()
            ->assertJsonFragment([
                'updated' => true,
            ]);

        $this->assertDatabaseHas((new Language())->getTable(), [
            'code' => 'en',
            'name' => 'English V2',
        ]);
    }

    public function testDestroyDeletesAGivenLanguage()
    {
        $esLang = Language::factory()->create([
            'code' => 'es',
            'name' => 'Espanol',
        ]);

        $response = $this->json('DELETE', "api/v1/languages/{$esLang->uuid}");

        $response->assertOk()
            ->assertJsonFragment([
                'deleted' => true,
            ]);

        $this->assertDatabaseMissing((new Language())->getTable(), [
            'code' => 'es',
        ]);
    }
}
