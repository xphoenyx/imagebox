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

        if ($upload->getError()) {
            return $response->withStatus(422);
        }

        try {
            $this->c->get('image')->make(
                $upload->getStream()->getMetadata('uri')
            );
        } catch (Exception $e) {
            return $response->withStatus(415);
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
            return $response->withStatus(404);
        }

        $imageContent = $this->getProcessedImage($image, $request);

        $response->getBody()->write($imageContent);

        return $response->withHeader('Content-Type', 'image/png');
    }

    protected function getProcessedImage($image, $request)
    {
        return (string) $this->c->get('image')->cache(function ($builder) use ($image, $request) {
            $this->processImage(
                $builder->make(uploads_path($image->uuid)),
                $request
            );
        });
    }

    protected function processImage($bulder, $request)
    {
        return $bulder->resize(null, $this->getRequestedSize($request), function ($constraint) {
                $constraint->aspectRatio();
            })
            ->encode('png');
    }

    protected function getRequestedSize($request)
    {
        $size = $request->getQueryParams()['s'] ?? null;

        return max(min($size, 800) ?? 100, 10);
    }
}
