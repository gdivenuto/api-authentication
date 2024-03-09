<?php

namespace Tests\Unit;

use App\Http\Controllers\GifController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GifControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testLogServiceInteraction()
    {
        $controller = new GifController();

        $request = Request::create('/test', 'GET', ['q' => 'test']);
        $response = new Response();

        $controller->logServiceInteraction($request, $response);

        $this->assertDatabaseHas('service_logs', [
            'service_name' => 'Giphy API',
            'response_status_code' => $response->status(),
        ]);
    }
}
