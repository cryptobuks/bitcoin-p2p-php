<?php

namespace BitWasp\Bitcoin\Networking\P2P;

use BitWasp\Bitcoin\Networking\Structure\NetworkAddress;
use BitWasp\Bitcoin\Networking\MessageFactory;
use React\EventLoop\LoopInterface;
use React\Socket\Connection;
use React\Socket\Server;

class Listener
{
    /**
     * @var NetworkAddress
     */
    private $local;

    /**
     * @var LoopInterface
     */
    private $loop;

    /**
     * @var MessageFactory
     */
    private $msgs;

    /**
     * @var Server
     */
    private $server;

    /**
     * @param NetworkAddress $localAddr
     * @param MessageFactory $messageFactory
     * @param Server $server
     * @param LoopInterface $loop
     */
    public function __construct(NetworkAddress $localAddr, MessageFactory $messageFactory, Server $server, LoopInterface $loop)
    {
        $this->local = $localAddr;
        $this->msgs = $messageFactory;
        $this->server = $server;
        $this->loop = $loop;

        $server->on('connection', [$this, 'handleIncomingPeer']);
    }

    /**
     * @return Peer
     */
    public function getPeer()
    {
        return new Peer(
            $this->local,
            $this->msgs,
            $this->loop
        );
    }

    /**
     * @param Connection $connection
     * @return \React\Promise\Promise|\React\Promise\PromiseInterface
     */
    public function handleIncomingPeer(Connection $connection)
    {
        return $this->getPeer()->inboundConnection($connection);
    }

    /**
     * @param int $port
     * @param string $host
     * @throws \React\Socket\ConnectionException
     */
    public function listen($port = 8333, $host = '0.0.0.0')
    {
        $this->server->listen($port, $host);
    }
}