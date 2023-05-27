<?php

namespace Tests;

use App\Http\Middleware\ValidatePublicAccessKey;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseMigrations;
    use DatabaseTruncation;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'filesystems.default' => 'public',
        ]);
    }

    public function json($method, $uri, array $data = [], array $headers = [], $options = 0)
    {
        return parent::json(
            $method,
            $uri,
            $data,
            [
                ValidatePublicAccessKey::X_ACCESS_KEY_HEADER => config('docking.public-access-key'),
                ...$headers,
            ],
            $options
        );
    }
}
