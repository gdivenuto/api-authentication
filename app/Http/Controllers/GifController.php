<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\FavoriteGif;
use Illuminate\Http\Request;
use App\Logging\LoggerInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class GifController extends Controller
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function search(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'query' => 'required|string',
                'limit' => 'nullable|integer',
                'offset' => 'nullable|integer'
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $apiKey = env('GIPHY_API_KEY');
            $query = $request->input('query');
            $limit = $request->input('limit');
            $offset = $request->input('offset');
            
            $response = Http::get("http://api.giphy.com/v1/gifs/search", [
                'api_key' => $apiKey,
                'q' => $query,
                'limit' => $limit,
                'offset' => $offset,
            ]);

            $this->logger->log('Giphy API', $request, $response);

            return $response->json();
        } catch (Exception $e) {
            report($e);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getById(Request $request, $id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|string' // El ID es un string. FUENTE: https://developers.giphy.com/docs/api/endpoint#get-gif-by-id
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $apiKey = env('GIPHY_API_KEY');

            $response = Http::get("http://api.giphy.com/v1/gifs/{$id}", [
                'api_key' => $apiKey,
            ]);
            
            $this->logger->log('Giphy API', $request, $response);

            return $response->json();
        } catch (Exception $e) {
            report($e);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'gif_id' => 'required|string',
                'alias' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $user = $request->user();
            $gif_id = $request->input('gif_id');
            $alias = $request->input('alias');

            $favoriteGif = new FavoriteGif();
            $favoriteGif->user_id = $user->id;
            $favoriteGif->gif_id = $gif_id;
            $favoriteGif->alias = $alias;
            $favoriteGif->save();

            $this->logger->log('Giphy API', $request, null);

            return response()->json(['message' => 'Gif favorito almacenado correctamente'], 200);
        } catch (Exception $e) {
            report($e);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}

