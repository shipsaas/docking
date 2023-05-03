<?php

namespace Tests\Feature;

use App\Http\Middleware\ValidatePublicAccessKey;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    public function testCorrectPublicAccessKeyCanAccess()
    {
        config(['docking.public-access-key' => 'seth-tran-phat']);

        $this->json(
            'GET',
            'api/v1/access',
            headers: [
                ValidatePublicAccessKey::X_ACCESS_KEY_HEADER => 'seth-tran-phat',
            ],
        )->assertOk();
    }

    public function testCorrectConsolePasswordKeyCanAccess()
    {
        config(['docking.console-password' => 'S$ethTRRAN0011']);

        $this->json(
            'GET',
            'api/v1/access',
            headers: [
                ValidatePublicAccessKey::X_ACCESS_KEY_HEADER => 'S$ethTRRAN0011',
            ],
        )->assertOk();
    }

    public function testWrongBothAccessKeyAndConsolePasswordCannotAccess()
    {
        $this->json(
            'GET',
            'api/v1/access',
            headers: [
                ValidatePublicAccessKey::X_ACCESS_KEY_HEADER => 'some-wrong-key-here',
            ],
        )->assertUnauthorized();
    }
}
