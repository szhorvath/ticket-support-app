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
    return function ($request, $response) {
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

$container['fractal'] = function ($container) {
    $manager = new \League\Fractal\Manager();
    $manager->setSerializer(new \League\Fractal\Serializer\ArraySerializer());
    return $manager;
};

$container['db'] = function ($container) {
    return new PDO(
        $container->config['db_driver'] . ':host=' . $container->config['db_host'] . ';dbname=' . $container->config['db_name'],
        $container->config['db_user'],
        $container->config['db_pass']
    );
};


$app->get('/', App\Controllers\HomeController::class . '@index');
$app->get('/users', App\Controllers\UserController::class . '@index');
$app->post('/users', App\Controllers\UserController::class . '@store');


$app->run();
