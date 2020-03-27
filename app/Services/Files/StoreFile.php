<?php

namespace App\Services\Files;

use \Exception;
use App\Models\Image;
use Slim\Psr7\UploadedFile;

class StoreFile {
    protected $stored = null;

    public function getStored ()
    {
        return $this->stored;
    }

    public function upload (UploadedFile $file)
    {
        try {
            $model = $this->createModel($file);
            $file->moveTo(uploads_path($model->uuid . '.jpg'));
        } catch (Exception $e) {
            //
        }

        return $this;
    }

    protected function createModel (UploadedFile $file)
    {
        return $this->stored = Image::create();
    }
}