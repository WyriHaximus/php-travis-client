<?php
declare(strict_types=1);

namespace WyriHaximus\Travis;

use React\EventLoop\Factory as LoopFactory;
use Rx\React\Promise;
use WyriHaximus\Travis\Resource\RepositoryInterface;
use WyriHaximus\Travis\Resource\SSHKeyInterface;
use WyriHaximus\ApiClient\Transport\Client as Transport;
use WyriHaximus\ApiClient\Transport\Factory;
use function Clue\React\Block\await;
use function React\Promise\resolve;
use WyriHaximus\Travis\Resource\UserInterface;

class Client
{
    /**
     * @var Transport
     */
    protected $transport;

    /**
     * @var AsyncClient
     */
    protected $client;

    /**
     * @param string $token
     * @param Transport|null $transport
     */
    public function __construct(string $token = '', Transport $transport = null)
    {
        $loop = LoopFactory::create();
        if (!($transport instanceof Transport)) {
            $options = [
                    'resource_namespace' => 'Async',
                ] + ApiSettings::TRANSPORT_OPTIONS;

            if ($token !== '') {
                $options['headers']['Authorization'] = 'token ' . $token;
            }

            $transport = Factory::create($loop, $options);
        }
        $this->transport = $transport;
        $this->client = new AsyncClient($loop, $token, $this->transport);
    }

    /**
     * @param string $repository
     * @return RepositoryInterface
     */
    public function repository(string $repository): RepositoryInterface
    {
        return await(
            $this->client->repository($repository),
            $this->transport->getLoop()
        );
    }

    /**
     * @return UserInterface
     */
    public function user(): UserInterface
    {
        return await(
            $this->client->user(),
            $this->transport->getLoop()
        );
    }

    /**
     * @param int $id
     * @return SSHKeyInterface
     */
    public function sshKey(int $id): SSHKeyInterface
    {
        return await(
            $this->client->sshKey($id),
            $this->transport->getLoop()
        );
    }

    /**
     * @return array
     */
    public function hooks(): array
    {
        return await(
            Promise::fromObservable(
                $this->client->hooks()->toArray()
            ),
            $this->transport->getLoop()
        );
    }

    /**
     * @return array
     */
    public function accounts(): array
    {
        return await(
            Promise::fromObservable(
                $this->client->accounts()->toArray()
            ),
            $this->transport->getLoop()
        );
    }

    /**
     * @return array
     */
    public function broadcasts(): array
    {
        return await(
            Promise::fromObservable(
                $this->client->broadcasts()->toArray()
            ),
            $this->transport->getLoop()
        );
    }
}
