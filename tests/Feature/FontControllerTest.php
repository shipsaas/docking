<?php

namespace Tests\Feature;

use App\Models\Font;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FontControllerTest extends TestCase
{
    public function testIndexReturnsAListOfFonts()
    {
        $files = Font::factory()->count(2)
            ->create();

        $uuids = $files->pluck('uuid');

        $response = $this->json('GET', 'api/v1/fonts')
            ->assertOk();

        $this->assertEquals(
            $uuids->toArray(),
            $response->collect('data')->pluck('uuid')->toArray()
        );
    }

    public function testStoreCreatesANewFont()
    {
        Storage::fake('local');

        $response = $this->json('POST', 'api/v1/fonts', [
            'key' => 'inter-sans',
            'name' => 'Inter Sans',
            'font' => UploadedFile::createFromBase(new File(
                'Inter-Thin.woff2',
                fopen(__DIR__ . '/../__fixtures__/Inter-Thin.woff2', 'r')
            )),
        ])
            ->assertOk()
            ->assertJsonStructure([
                'uuid',
                'created',
            ]);

        $uuid = $response->json('uuid');

        $this->assertDatabaseHas('fonts', [
            'uuid' => $uuid,
            'key' => 'inter-sans',
            'name' => 'Inter Sans',
        ]);

        $font = Font::find($uuid);

        Storage::disk('local')->assertExists($font->path);
    }

    public function testStoreRejectsWhenUsingInvalidFontFile()
    {
        $this->json('POST', 'api/v1/fonts', [
            'key' => 'inter-sans',
            'name' => 'Inter Sans',
            'font' => UploadedFile::fake()->createWithContent('test.txt', 'aaa'),
        ])->assertUnprocessable()
            ->assertJsonValidationErrorFor('font');
    }

    public function testStoreRejectsWhenStoringFileFailed()
    {
        Storage::fake('local');

        Storage::expects('disk')->once()->andReturnSelf();
        Storage::expects('putFileAs')->once()->andReturn(false);

        $this->json('POST', 'api/v1/fonts', [
            'key' => 'inter-sans',
            'name' => 'Inter Sans',
            'font' => UploadedFile::createFromBase(new File(
                'Inter-Thin.woff2',
                fopen(__DIR__ . '/../__fixtures__/Inter-Thin.woff2', 'r')
            )),
        ])->assertBadRequest();
    }

    public function testDestroyDeletesTheFontAndFile()
    {
        Storage::fake('local');
        $file = UploadedFile::createFromBase(new File(
            'Inter-Thin.woff2',
            fopen(__DIR__ . '/../__fixtures__/Inter-Thin.woff2', 'r')
        ));

        $font = Font::factory()->create([
            'path' => $file->store('fonts', ['disk' => 'local']),
        ]);

        $this->json('DELETE', 'api/v1/fonts/' . $font->uuid)
            ->assertOk();

        $this->assertDatabaseMissing('fonts', [
            'uuid' => $font->uuid,
        ]);

        Storage::disk('local')->assertMissing($font->path);
    }
}
