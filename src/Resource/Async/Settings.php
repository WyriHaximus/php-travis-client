<?php
declare(strict_types=1);

namespace WyriHaximus\Travis\Resource\Async;

use Exception;
use React\Promise\PromiseInterface;
use WyriHaximus\Travis\Resource\Settings as BaseSettings;
use function React\Promise\reject;

class Settings extends BaseSettings
{
    public function refresh() : PromiseInterface
    {
        return reject(new Exception('Can\'t refresh as there is no reference to the relative repository'));
    }
}
