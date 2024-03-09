<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GifSearchTest extends TestCase
{
    use RefreshDatabase;

    public function testSearchGifs()
    {
        Http::fake([
            'http://api.giphy.com/v1/gifs/search' => Http::response(['data' => []], 200),
        ]);

        $response = $this->get('/gifs/search?query=test');

        $response->assertStatus(200)
            ->assertJson(['data' => []]);

        Http::assertSent(function ($request) {
            return $request->url() == 'http://api.giphy.com/v1/gifs/search' && $request['query'] == 'test';
        });
    }
}
