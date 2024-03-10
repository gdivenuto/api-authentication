<?php

namespace App\Http\Controllers;

use App\Models\ServiceLog;
use App\Models\FavoriteGif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class GifController extends Controller
{
    public function search(Request $request)
    {
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

        $this->logServiceInteraction($request, $response);

        return $response->json();
    }

    public function getById(Request $request, $id)
    {
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

        $this->logServiceInteraction($request, $response);

        return $response->json();
    }

    public function store(Request $request)
    {
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

        return response()->json(['message' => 'Gif favorito almacenado correctamente'], 201);
    }

    private function logServiceInteraction(Request $request, $response)
    {
        $user = $request->user();
        $serviceName = 'Giphy API';
        $requestBody = $request->all();
        $responseStatusCode = $response->status();
        $responseBody = $response->json();
        $sourceIp = $request->ip();

        $log = new ServiceLog();
        $log->user_id = $user ? $user->id : null;
        $log->service_name = $serviceName;
        $log->request_body = json_encode($requestBody);
        $log->response_status_code = $responseStatusCode;
        $log->response_body = json_encode($responseBody);
        $log->source_ip = $sourceIp;
        $log->save();
    }

}

