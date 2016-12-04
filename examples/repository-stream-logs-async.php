<?php

use React\EventLoop\Factory;
use Rx\Observable;
use Rx\Observer\CallbackObserver;
use Rx\React\Promise;
use WyriHaximus\Travis\AsyncClient;
use WyriHaximus\Travis\Resource\Async\Build;
use WyriHaximus\Travis\Resource\Async\Job;
use WyriHaximus\Travis\Resource\Async\LogLine;
use WyriHaximus\Travis\Resource\Async\Repository;
use function ApiClients\Foundation\resource_pretty_print;

require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

$loop   = Factory::create();
$client = new AsyncClient($loop);

$repos = [
    'WyriHaximus/php-travis-client',
];

if (count($argv) > 1) {
    unset($argv[0]);
    foreach ($argv as $repo) {
        $repos[] = $repo;
    }
}
Observable::fromArray($repos)
    ->flatMap(function ($repo) use ($client) {
        return Promise::toObservable($client->repository($repo));
    })
    ->flatMap(function (Repository $repo) {
        echo 'Listening on repository: ', $repo->slug(), PHP_EOL;
        return $repo->events();
    })
    ->filter(function (Repository $repo) {
        return $repo->lastBuildState() === 'started';
    })
    ->flatMap(function (Repository $repo) {
        echo 'Repo: ', $repo->slug(), PHP_EOL;
        return Promise::toObservable($repo->build($repo->lastBuildId()));
    })
    ->flatMap(function (Build $build) {
        echo 'Build ID: ', $build->id(), PHP_EOL;
        return $build->jobs();
    })
    ->filter(function (Job $job) {
        return $job->state() !== 'passed';
    })
    ->flatMap(function (Job $job) {
        echo 'Job ID: ', $job->id(), PHP_EOL;
        return $job->log();
    })
    ->subscribe(new CallbackObserver(function (LogLine $line) {
        resource_pretty_print($line);
    }));


$loop->run();
