<?php

use React\EventLoop\Factory;
use React\Promise\Deferred;
use WyriHaximus\Travis\AsyncClient;
use WyriHaximus\Travis\Resource\Async\User;
use WyriHaximus\Travis\Resource\UserInterface;
use function React\Promise\resolve;

require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

$loop = Factory::create();
$client = new AsyncClient($loop, require 'resolve_key.php');

$client->user()->then(function (UserInterface $user) {
    echo 'Previous sync: ', $user->syncedAt()->format(DATE_ISO8601), PHP_EOL;
    return $user->sync();
})->then(function (User $user) use ($loop) {
    if (!$user->isSyncing()) {
        return resolve($user);
    }

    echo 'Syncing';

    $deferred = new Deferred();

    $func = function () use ($loop, $user, $deferred, &$func) {
        $user->refresh()->then(function (UserInterface $user) use ($loop, $deferred, &$func) {
            if (!$user->isSyncing()) {
                echo PHP_EOL;
                return $deferred->resolve($user);
            }

            echo '.';
            $loop->addTimer(0.5, $func);
        });
    };
    $func();

    return $deferred->promise();
})->then(function (UserInterface $user) {
    echo 'Sync at: ', $user->syncedAt()->format(DATE_ISO8601), PHP_EOL;
});

$loop->run();
