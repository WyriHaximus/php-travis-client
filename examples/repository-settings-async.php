<?php

use React\EventLoop\Factory;
use WyriHaximus\Travis\AsyncClient;
use WyriHaximus\Travis\Resource\RepositoryInterface;
use WyriHaximus\Travis\Resource\SettingsInterface;
use function ApiClients\Foundation\resource_pretty_print;

require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

$loop = Factory::create();
$client = new AsyncClient($loop, require 'resolve_key.php');

$repos = [
    'WyriHaximus/php-travis-client',
];

if (count($argv) > 1) {
    unset($argv[0]);
    foreach ($argv as $repo) {
        $repos[] = $repo;
    }
}

foreach ($repos as $repo) {
    $client->repository($repo)->then(function (RepositoryInterface $repo) {
        $repo->settings()->then(function (SettingsInterface $settings) use ($repo) {
            echo $repo->slug(), ': ', PHP_EOL;
            resource_pretty_print($settings, 1);
        });
    });
}

$loop->run();
