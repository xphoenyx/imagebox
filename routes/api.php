<?php

use App\Controllers\ImageController;

$app->post('/images', ImageController::class . ':store');
