<?php

$app->get('/uploads/{category}/{folder}/{fileName}', function ($request, $response, $args) {
    // category có thể là travel-spots, provinces, etc.
    $baseDir = __DIR__ . '/../../uploads/';
    $filePath = $baseDir . $args['category'] . '/' . $args['folder'] . '/' . $args['fileName'];

    if (!file_exists($filePath)) {
        throw new \Slim\Exception\HttpNotFoundException($request);
    }

    $mimeType = mime_content_type($filePath);
    $stream = new \Slim\Psr7\Stream(fopen($filePath, 'rb'));

    return $response
        ->withHeader('Content-Type', $mimeType)
        ->withHeader('Content-Disposition', 'inline; filename="' . $args['fileName'] . '"')
        ->withBody($stream);
});

