<?php

namespace App\Controllers;

use \Exception;
use App\Controllers\Controller;
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
            'uuid' => $store->getStored()->uuid
        ]);

        $response->getBody()->write($payload);

        return $response
                ->withHeader('Content-Type', 'application/json');
    }
}
