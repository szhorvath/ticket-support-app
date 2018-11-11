<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION)) {
    session_start();
}

require __DIR__ . '/../vendor/autoload.php';



$app = new App\App;

$container = $app->getContainer();

$container['errorHandler'] = function () {
    return function ($response) {
        return $response->setBody('Page not found')->withStatus(404);
    };
};

$container['config'] = function () {
    return [
        'db_driver' => 'mysql',
        'db_host' => 'mysql',
        'db_name' => 'test',
        'db_user' => 'root',
        'db_pass' => 'root',
    ];
};

$container['db'] = function ($c) {
    return new PDO(
        $c->config['db_driver'] . ':host=' . $c->config['db_host'] . ';dbname=' . $c->config['db_name'],
        $c->config['db_user'],
        $c->config['db_pass']
    );
};

$app->addMiddleware('auth', new App\Middleware\AuthMiddleware);

$app->get('/', App\Controllers\HomeController::class . '@index');

$app->use(['auth'], function () use ($app) {
    $app->get('/users', App\Controllers\UserController::class . '@index');
});


$app->run();
