<?php

namespace Tests\Feature;

use App\Http\Controllers\GifController;
use App\Models\FavoriteGif;
use App\Logging\LoggerInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class GifStoreTest extends TestCase
{
    use RefreshDatabase;

    public function testStoreMethod()
    {
        // Se crea un mock para LoggerInterface
        $loggerMock = $this->createMock(LoggerInterface::class);

        // Se crea una instancia de GifController pasando el mock de LoggerInterface al constructor
        $controller = new GifController($loggerMock);

        // Se crea una instancia de Request con datos de ejemplo
        $requestData = ['gif_id' => '123', 'alias' => 'cool-gif'];
        $request = Request::create('/store', 'POST', $requestData);

        // Se configura el mock de Validator para simular la validación exitosa
        Validator::shouldReceive('make')
            ->once()
            ->with($requestData, [
                'gif_id' => 'required|string',
                'alias' => 'required|string'
            ])
            ->andReturn($this->createMock(\Illuminate\Contracts\Validation\Validator::class));

        // Se configura el mock de FavoriteGif para simular el almacenamiento
        $favoriteGifMock = $this->getMockBuilder(FavoriteGif::class)
                                ->disableOriginalConstructor()
                                ->getMock();
        $favoriteGifMock->expects($this->once())
                        ->method('save');

        // Se sobrescribe la implementación predeterminada de new FavoriteGif() en el controlador con el mock
        $controller->shouldReceive('newFavoriteGif')->andReturn($favoriteGifMock);

        // Se configura el mock de Response para simular una respuesta JSON exitosa
        $responseMock = $this->createMock(Response::class);
        $responseMock->expects($this->once())
                     ->method('json')
                     ->willReturn(['message' => 'Gif favorito almacenado correctamente']);
        Http::fake(['*' => $responseMock]);

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
