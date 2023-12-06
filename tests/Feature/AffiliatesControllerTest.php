<?php

namespace Tests\Feature;

use  App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AffiliatesControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the affiliates route returns a 200 status code
     */
    public function testAffiliatesReturns200Status(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard/list-affiliates');

        $response->assertStatus(200);
    }
}
