<?php

namespace App\Logging;

use Illuminate\Http\Request;
use Illuminate\Http\Client\Response;

interface LoggerInterface
{
    public function log(string $serviceName, Request $request, ?Response $response);
}