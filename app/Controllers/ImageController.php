<?php

namespace App\Controllers;

use \Exception;
use App\Controllers\Controller;
use App\Models\Image;
use App\Services\Files\StoreFile;
use Psr\Http\Message\{
    ServerRequestInterface as Request,
    ResponseInterface as Response
};

class ImageController extends Controller
{
    public function store(Request $request, Response $response, $args)
    {
        if (!$upload = $request->getUploadedFiles()['file'] ?? null) {
            return $response->withStatus(422);
        }

        try {
            $this->c->get('image')->make($upload->getStream()->getMetadata('uri'));
        } catch (Exception $e) {
            return $response->withStatus(422);
        }

        $store = (new StoreFile())->upload($upload);

        $payload = json_encode([
            'data' => [
                'uuid' => $store->getStored()->uuid,
            ]
        ]);

        $response->getBody()->write($payload);

        return $response
                ->withHeader('Content-Type', 'application/json');
    }

    public function show(Request $request, Response $response, $args)
    {
        extract($args);

        try {
            $image = Image::where('uuid', $uuid)->firstOrFail();
        } catch (Exception $e) {
            return $response->withStatus(422);
        }

        $imageContent = (string) $this->c->get('image')->make(uploads_path($image->uuid))->encode('png');

        $response->getBody()->write($imageContent);

        return $response->withHeader('Content-Type', 'image/png');
    }
}
