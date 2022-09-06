<?php

namespace Tests\Feature;

use App\Models\Url;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UrlControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testUrlIndex()
    {
        Sanctum::actingAs(User::factory()->create());

        $this->json('get', 'api/v1/url')->assertOk();
    }

    public function testUrlStore()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $data = [
            'url' => 'https://www.soilconnect.com',
            'title' => 'Soil Connect',

        ];

        $result = $this->postJson('api/v1/url', $data);

        $result
            ->assertOk()
            ->assertJsonFragment(['url' => 'www.soilconnect.com']);

        $this->assertDatabaseHas(Url::class, [
            'url' => 'www.soilconnect.com',
            'title' => 'Soil Connect',
            'user_id' => $user->id,
        ]);
    }

    public function testUrlDeletes()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $url = Url::factory()->create(['user_id' => $user->id]);

        $response = $this->postJson('api/v1/url/' . $url->id, ['_method' => 'delete']);

        $response->assertOk();

        $this->assertDatabaseHas('urls', $url->toArray());
        $this->assertNotNull(($url->fresh())->deleted_at);
    }

    public function testListing()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $urls = Url::factory(25)->create(['user_id' => $user->id]);

        $response = $this->getJson('api/v1/url/', ['perPage' => 5]);

        $response->assertOk()
            ->assertSee('url');
    }
}
