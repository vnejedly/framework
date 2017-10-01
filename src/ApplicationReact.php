<?php
namespace Hooloovoo\Framework;

use React\EventLoop\LoopInterface;
use React\Socket\Server as ReactSocketServer;
use React\Http\Server as ReactHttpServer;

/**
 * Class ApplicationReact
 */
class ApplicationReact
{
    /** @var ReactSocketServer */
    private $reactSocketServer;

    /** @var LoopInterface  */
    private $loop;

    /** @var ReactRequestHandler */
    private $reactRequestHandler;

    /** @var ReactHttpServer */
    private $reactHttpServer;

    /**
     * Application constructor.
     *
     * @param ReactRequestHandler $reactRequestHandler
     * @param ReactHttpServer $reactHttpServer
     * @param ReactSocketServer $reactSocketServer
     * @param LoopInterface $loop
     */
    public function __construct(
        ReactRequestHandler $reactRequestHandler,
        ReactHttpServer $reactHttpServer,
        ReactSocketServer $reactSocketServer,
        LoopInterface $loop
    ) {
        $this->reactRequestHandler = $reactRequestHandler;
        $this->reactHttpServer = $reactHttpServer;
        $this->reactSocketServer = $reactSocketServer;
        $this->loop = $loop;
    }

    /**
     * @param int $port
     * @param string $host
     */
    public function run(int $port = 80, string $host = '0.0.0.0')
    {
        $this->reactHttpServer->on('request', [$this->reactRequestHandler, 'onRequest']);
        $this->reactSocketServer->listen($port, $host);
        $this->loop->run();
    }
}
