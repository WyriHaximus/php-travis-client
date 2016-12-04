<?php

use WyriHaximus\Travis\Client;
use function ApiClients\Foundation\resource_pretty_print;

require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

$client = new Client(require 'resolve_key.php');

$repo = $client->repository($argv[1] ?? 'WyriHaximus/php-travis-client');

echo $repo->slug(), ': ', PHP_EOL;
resource_pretty_print($repo->settings(), 1);
