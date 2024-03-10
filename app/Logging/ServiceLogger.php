<?php

namespace App\Logging;

use App\Models\ServiceLog;
use Illuminate\Http\Request;
use Illuminate\Http\Client\Response;

class ServiceLogger implements LoggerInterface
{
    public function log(string $serviceName, Request $request, ?Response $response)
    {
        $user = $request->user();
        $requestBody = $request->all();
        $responseStatusCode = optional($response)->status() ?? 0;
        $responseBody = optional($response)->json() ?? [];
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
