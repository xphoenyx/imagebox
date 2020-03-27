<?php

use Dotenv\Dotenv;
use Slim\Factory\AppFactory;
use Dotenv\Exception\InvalidPathException;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;

require_once __DIR__ . '/../vendor/autoload.php';

try {
    (new Dotenv(__DIR__ . '/../'))->load();
} catch (InvalidPathException $e) {
    //
}

$container = new DI\Container();

AppFactory::setContainer($container);

$app = AppFactory::create();

$container->set('settings', function () {
    return [
        'app' => [
            'name' => getenv('APP_NAME')
        ],

        'database' => [
            'driver' => 'mysql',
            'host' => getenv('DB_HOST') ?: '127.0.0.1',
            'port' => getenv('DB_PORT') ?: 3306,
            'database' => getenv('DB_DATABASE'),
            'username' => getenv('DB_USERNAME'),
            'password' => getenv('DB_PASSWORD') ?: '',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
        ],
    ];
});

$container = $app->getContainer();

$capsule = new Capsule;
$capsule->addConnection($container->get('settings')['database']);

$capsule->setEventDispatcher(new Dispatcher(new Container));
$capsule->setAsGlobal();
$capsule->bootEloquent();

require_once __DIR__ . '/../routes/api.php';
