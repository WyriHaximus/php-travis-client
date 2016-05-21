<?php

use WyriHaximus\Travis\Client;

require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

$client = new Client();

$repository = $client->repository($argv[1] ?? 'WyriHaximus/php-travis-client');
echo 'Repository: ', PHP_EOL;
echo 'id: ' . $repository->id(), PHP_EOL;
echo 'slug: ' . $repository->slug(), PHP_EOL;
echo 'description: ' . $repository->description(), PHP_EOL;
echo 'Commits:', PHP_EOL;
foreach ($repository->commits() as $commit) {
    echo "\t", 'Commit', PHP_EOL;
    echo "\t\t" . 'id: ' . $commit->id(), PHP_EOL;
    echo "\t\t" . 'sha: ' . $commit->sha(), PHP_EOL;
    echo "\t\t" . 'branch: ' . $commit->branch(), PHP_EOL;
    echo "\t\t" . 'message: ' . $commit->message(), PHP_EOL;
}
