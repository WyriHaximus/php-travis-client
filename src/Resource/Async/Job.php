<?php
declare(strict_types=1);

namespace WyriHaximus\Travis\Resource\Async;

use ApiClients\Client\Pusher\CommandBus\Command\SharedAppClientCommand;
use ApiClients\Foundation\Hydrator\CommandBus\Command\HydrateCommand;
use ApiClients\Foundation\Transport\CommandBus\Command\SimpleRequestCommand;
use React\Promise\PromiseInterface;
use Rx\Observable;
use Rx\ObservableInterface;
use Rx\Observer\CallbackObserver;
use Rx\ObserverInterface;
use Rx\React\Promise;
use Rx\SchedulerInterface;
use WyriHaximus\Travis\ApiSettings;
use WyriHaximus\Travis\Resource\Job as BaseJob;
use function React\Promise\resolve;

class Job extends BaseJob
{
    public function log(): ObservableInterface
    {
        return Observable::create(function (
            ObserverInterface $observer,
            SchedulerInterface $scheduler
        ) {
            $this->handleCommand(
                new SharedAppClientCommand(ApiSettings::PUSHER_KEY)
            )->then(function ($pusher) use ($observer) {
                $pusher->channel('job-' . $this->id)->filter(function ($message) {
                    return $message->event == 'job:log';
                })->map(function ($message) {
                    return json_decode($message->data, true);
                })->flatMap(function (array $json) {
                    return Promise::toObservable($this->handleCommand(new HydrateCommand('LogLine', $json)));
                })->subscribe(new CallbackObserver(function ($repository) use ($observer) {
                    $observer->onNext($repository);
                }));
            });
        });
    }
    /**
     * @return ObservableInterface
     */
    public function annotations(): ObservableInterface
    {
        return Promise::toObservable(
            $this->handleCommand(new SimpleRequestCommand('jobs/' . $this->id() . '/annotations'))
        )->flatMap(function ($response) {
            return Observable::fromArray($response['annotations']);
        })->flatMap(function ($annotation) {
            return Promise::toObservable($this->handleCommand(new HydrateCommand('Annotation', $annotation)));
        });
    }

    /**
     * @return PromiseInterface
     */
    public function refresh(): PromiseInterface
    {
        return $this->handleCommand(new SimpleRequestCommand('jobs/' . $this->id))->then(function ($json) {
            return resolve($this->handleCommand(new HydrateCommand('Job', $json['job'])));
        });
    }
}
