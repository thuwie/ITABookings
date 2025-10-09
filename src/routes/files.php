<?php

$app->get('/files/{folder}/{fileName}', function ($request, $response, $args) {
    $filePath = __DIR__ . '/../../uploads/provinces/' . $args['folder'] .'/'. $args['fileName'];

    if (!file_exists($filePath)) {
        throw new \Slim\Exception\HttpNotFoundException($request);
    }

    $mimeType = mime_content_type($filePath);
    $stream = new \Slim\Psr7\Stream(fopen($filePath, 'rb'));

    return $response
        ->withHeader('Content-Type', $mimeType)
        ->withBody($stream);
});
