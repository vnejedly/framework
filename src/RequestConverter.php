<?php
namespace Hooloovoo\Framework;

use HttpHeaderException;
use React\Http\Request as ReactRequest;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

/**
 * Class RequestConverter
 */
class RequestConverter
{
    /**
     * @param ReactRequest $reactRequest
     * @param string $content
     * @return SymfonyRequest
     * @throws HttpHeaderException
     */
    public function getSymfonyRequest(ReactRequest $reactRequest, string $content) : SymfonyRequest
    {
        $headers = new HeaderBag($reactRequest->getHeaders());
        $method = strtoupper($reactRequest->getMethod());
        $query = $reactRequest->getQuery();

        $sfRequest = new SymfonyRequest(
            $query,     // $query
            [],         // $request
            [],         // $attributes
            [],         // $cookies
            [],         // $files
            [],         // $server
            $content    // $content
        );

        $sfRequest->setMethod($method);
        $sfRequest->headers = $headers;
        $sfRequest->server->set('REQUEST_URI', $reactRequest->getPath());
        $sfRequest->server->set('SERVER_NAME', explode(':', $headers->get('Host'))[0]);
        $sfRequest->server->set('REMOTE_ADDR', $reactRequest->remoteAddress);

        return $sfRequest;
    }
}
