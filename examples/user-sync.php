<?php

use WyriHaximus\Travis\Client;

require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

$client = new Client(require 'resolve_key.php');

$user = $client->user();
echo 'Previous sync: ', $user->syncedAt()->format(DATE_ISO8601), PHP_EOL;
$user = $user->sync();
if ($user->isSyncing()) {
    echo 'Syncing';
    do {
        echo '.';
        sleep(1);
        $user = $user->refresh();
    } while ($user->isSyncing());
}
echo 'Sync at: ', $user->syncedAt()->format(DATE_ISO8601), PHP_EOL;
