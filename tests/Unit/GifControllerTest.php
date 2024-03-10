<?php

namespace Tests\Unit;

use App\Http\Controllers\GifController;
use App\Logging\LoggerInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GifControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testSearchMethod()
    {
        // Se crea un mock para LoggerInterface
        $loggerMock = $this->createMock(LoggerInterface::class);

        // Se crea una instancia de GifController pasando el mock de LoggerInterface al constructor
        $controller = new GifController($loggerMock);

        // Se crea una instancia de Request con datos de ejemplo
        $request = Request::create('/search', 'GET', ['query' => 'cat']);

        // Se configura el mock de HttpClient para devolver una respuesta simulada
        Http::fake([
            'http://api.giphy.com/v1/gifs/search' => Http::response(['data' => ['gif1', 'gif2']], 200),
        ]);

        // Se ejecuta el método search
        $response = $controller->search($request);

        // Se verifica que se haya llamado al método log en LoggerInterface con los parámetros correctos
        $loggerMock->expects($this->once())
                   ->method('log')
                   ->with('Giphy API', $request, $this->isInstanceOf(Response::class));

        // Se verifica que se haya devuelto una respuesta JSON con los datos correctos
        $response->assertJson(['data' => ['gif1', 'gif2']]);
    }

    public function testGetByIdMethod()
    {
        // Se crea un mock para LoggerInterface
        $loggerMock = $this->createMock(LoggerInterface::class);

        // Se crea una instancia de GifController pasando el mock de LoggerInterface al constructor
        $controller = new GifController($loggerMock);

        // Se crea una instancia de Request con datos de ejemplo
        $request = Request::create('/gifs/123', 'GET');

        // Se configura el mock de HttpClient para devolver una respuesta simulada
        Http::fake([
            'http://api.giphy.com/v1/gifs/123' => Http::response(['data' => 'gif123'], 200),
        ]);

        // Se ejecuta el método getById
        $response = $controller->getById($request, '123');

        // Se verifica que se haya llamado al método log en LoggerInterface con los parámetros correctos
        $loggerMock->expects($this->once())
                   ->method('log')
                   ->with('Giphy API', $request, $this->isInstanceOf(Response::class));

        // Se verifica que se haya devuelto una respuesta JSON con los datos correctos
        $response->assertJson(['data' => 'gif123']);
    }

    public function testStoreMethod()
    {
        // Se crea un mock para LoggerInterface
        $loggerMock = $this->createMock(LoggerInterface::class);

        // Se crea una instancia de GifController pasando el mock de LoggerInterface al constructor
        $controller = new GifController($loggerMock);

        // Se crea una instancia de Request con datos de ejemplo
        $request = Request::create('/store', 'POST', ['gif_id' => '123', 'alias' => 'cool-gif']);

        // Se configura el mock de FavoriteGif para simular el almacenamiento
        $favoriteGifMock = $this->getMockBuilder(\App\Models\FavoriteGif::class)
                                ->disableOriginalConstructor()
                                ->getMock();
        $favoriteGifMock->expects($this->once())
                        ->method('save');

        // Sobrescribir la implementación predeterminada de new FavoriteGif() en el controlador con el mock
        $controller->shouldReceive('newFavoriteGif')->andReturn($favoriteGifMock);

        // Se ejecuta el método store
        $response = $controller->store($request);

        // Se verifica que se haya llamado al método log en LoggerInterface con los parámetros correctos
        $loggerMock->expects($this->once())
                   ->method('log')
                   ->with('Giphy API', $request, $this->isNull());

        // Se verifica que se haya devuelto una respuesta JSON con el mensaje correcto
        $response->assertJson(['message' => 'Gif favorito almacenado correctamente']);
    }
}
