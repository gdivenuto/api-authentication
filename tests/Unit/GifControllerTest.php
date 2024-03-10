<?php

namespace Tests\Unit;

use App\Http\Controllers\GifController;
use App\Logging\LoggerInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GifControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testLogServiceInteraction()
    {
        // Creamos un mock de LoggerInterface
        $loggerMock = $this->createMock(LoggerInterface::class);

        // Creamos una instancia de GifController pasando el mock de LoggerInterface al constructor
        $controller = new GifController($loggerMock);

        // Creamos objetos Request y Response para simular una interacción con el servicio
        $request = Request::create('/test', 'GET', ['q' => 'test']);
        $response = new Response();

        // Ejecutamos el método logServiceInteraction
        $controller->logServiceInteraction($request, $response);

        // Verificamos que se haya registrado la interacción en la base de datos
        $this->assertDatabaseHas('service_logs', [
            'service_name' => 'Giphy API',
            'response_status_code' => $response->status(),
        ]);
    }
}
