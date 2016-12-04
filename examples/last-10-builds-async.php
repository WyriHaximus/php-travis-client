<?php

use React\EventLoop\Factory;
use Rx\Observer\CallbackObserver;
use WyriHaximus\Travis\AsyncClient;
use WyriHaximus\Travis\Resource\Async\Repository;
use WyriHaximus\Travis\Resource\BuildInterface;

require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

$loop   = Factory::create();
$client = new AsyncClient($loop);

$client->repository($argv[1] ?? 'WyriHaximus/php-travis-client')->then(function (Repository $repository) {
    echo 'Repository: ', PHP_EOL;
    echo 'id: ' . $repository->id(), PHP_EOL;
    echo 'slug: ' . $repository->slug(), PHP_EOL;
    echo 'description: ' . $repository->description(), PHP_EOL;
    echo 'Builds:', PHP_EOL;

    $repository->builds()->subscribe(new CallbackObserver(function (BuildInterface $build) {
        echo "\t", 'Build', PHP_EOL;
        echo "\t\t" . 'id: ' . $build->id(), PHP_EOL;
        echo "\t\t" . 'commit id: ' . $build->commitId(), PHP_EOL;
        echo "\t\t" . 'duration: ' . $build->duration(), PHP_EOL;
    }));
});


$loop->run();
