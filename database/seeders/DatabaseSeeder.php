<?php

namespace Database\Seeders;

use App\Enums\GotenbergEngine;
use App\Models\DocumentTemplate;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // seeds some test templates

        // 1. Sample HTML template
        DocumentTemplate::create([
            'key' => 'easy-template',
            'category' => 'sample',
            'title' => 'Easy Template',
            'template' => 'Hello {{ $name }}, how are you today? <br /> I hope you are doing well.',
            'default_variables' => [
                'name' => 'Seth Phat',
            ],
            'metadata' => [
                'driver' => 'gotenberg',
                'engine' => GotenbergEngine::CHROMIUM->value,
            ],
        ]);

        // 2. Invoice Template
        DocumentTemplate::create([
            'key' => 'invoice-template',
            'category' => 'invoice-domain',
            'title' => 'Invoice Template',
            'template' => file_get_contents(
                __DIR__ . '/../../tests/Integration/__fixtures__/invoice.html'
            ),
            'default_variables' => json_decode(
                file_get_contents(
                    __DIR__ . '/../../tests/Integration/__fixtures__/invoice.json'
                ),
                true
            ),
            'metadata' => [
                'driver' => 'gotenberg',
                'engine' => GotenbergEngine::CHROMIUM->value,
            ],
        ]);

        // 3. Thank you receipt
        DocumentTemplate::create([
            'key' => 'thank-you-receipt-template',
            'category' => 'invoice-domain',
            'title' => 'Thank You Receipt Template',
            'template' => file_get_contents(
                __DIR__ . '/../../tests/Integration/__fixtures__/thank-you-receipt.html'
            ),
            'default_variables' => json_decode(
                file_get_contents(
                    __DIR__ . '/../../tests/Integration/__fixtures__/thank-you-receipt.json'
                ),
                true
            ),
            'metadata' => [
                'driver' => 'gotenberg',
                'engine' => GotenbergEngine::CHROMIUM->value,
            ],
        ]);
    }
}
