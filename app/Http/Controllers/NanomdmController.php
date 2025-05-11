<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class NanomdmController extends Controller
{
    protected readonly bool $filterHeaders;

    public function __construct()
    {
        $this->filterHeaders = false;
    }

    public function __invoke(Request $request, string $account, string $path = ''): Response
    {
        Log::debug('__invoke: ' . 'NanomdmController');
        $client = new Client([
            'base_uri' => config('nanomdm.url'),
            'timeout' => 10.0,
            'http_errors' => false,
        ]);

        $response = $client->request($request->method(), $path, [
            'headers' => $this->getHeaders($request->header()),
            'query' => $request->query(),
            'body' => $request->getContent(),
        ]);

        return response($response->getBody()->getContents(), $response->getStatusCode())
            ->withHeaders($this->getHeaders($response->getHeaders()));
    }

    private function getHeaders(array $headers): array
    {
        $allowedHeaders = ['accept', 'content-type'];

        return $this->filterHeaders ? array_filter($headers, function ($key) use ($allowedHeaders) {
            return in_array(strtolower($key), $allowedHeaders);
        }, ARRAY_FILTER_USE_KEY) : $headers;
    }
}
