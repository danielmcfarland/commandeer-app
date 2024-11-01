<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

function filterHeaders($headers) {
//    $allowedHeaders = ['accept', 'content-type'];

//    return array_filter($headers, function($key) use ($allowedHeaders) {
//        return in_array(strtolower($key), $allowedHeaders);
//    }, ARRAY_FILTER_USE_KEY);
    return $headers;
}

Route::any('/{path}', function(Request $request, $path) {
    $client = new GuzzleHttp\Client([
        'base_uri' => 'http://127.0.0.1:9000',
        'timeout'  => 10.0,
        'http_errors' => false, // disable guzzle exception on 4xx or 5xx response code
    ]);

    // create request according to our needs. we could add
    // custom logic such as auth flow, caching mechanism, etc
    $resp = $client->request($request->method(), $path, [
        'headers' => filterHeaders($request->header()),
        'query' => $request->query(),
        'body' => $request->getContent(),
    ]);

    // recreate response object to be passed to actual caller
    // according to our needs.
    return response($resp->getBody()->getContents(), $resp->getStatusCode())
        ->withHeaders(filterHeaders($resp->getHeaders()));

})->where('path', '.*');
