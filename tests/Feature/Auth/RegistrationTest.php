<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_canot_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(404);
    }

}
