<?php
namespace Hooloovoo\Framework;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use React\Http\Request;
use React\Http\Response;

/**
 * Handles React requests and dispatches them to HttpKernelInterface
 */
class ReactRequestHandler
{
    /** @var HttpKernelInterface */
    protected $httpKernel;

    /** @var RequestConverter */
    protected $requestConverter;

    /**
     * ReactRequestHandler constructor.
     *
     * @param HttpKernelInterface $httpKernel
     * @param RequestConverter $requestConverter
     */
    public function __construct(
        HttpKernelInterface $httpKernel,
        RequestConverter $requestConverter
    ) {
        $this->httpKernel = $httpKernel;
        $this->requestConverter = $requestConverter;
    }

    /**
     * @param Request $request
     * @param Response $response
     */
    public function onRequest(Request $request, Response $response)
    {
        if ($this->getContentLength($request) == 0) {
            $this->processRequest($request, $response);
        } else $request->on('data', function (string $data) use ($request, $response) {
            $this->processRequest($request, $response, $data);
        });
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param string $content
     */
    protected function processRequest(Request $request, Response $response, string $content = '')
    {
        $symfonyRequest = $this->requestConverter->getSymfonyRequest($request, $content);
        $symfonyResponse = $this->httpKernel->handle($symfonyRequest, HttpKernelInterface::MASTER_REQUEST);
        $symfonyResponse->prepare($symfonyRequest);

        $response->writeHead($symfonyResponse->getStatusCode(), $symfonyResponse->headers->all());
        $response->end($symfonyResponse->getContent());
    }

    /**
     * @param Request $request
     * @return int
     */
    protected function getContentLength(Request $request) : int
    {
        return (int) $this->getHeader('Content-Length', $request->getHeaders());
    }

    /**
     * @param string $name
     * @param array $headers
     * @return mixed
     */
    protected function getHeader(string $name, array $headers)
    {
        $name = strtolower($name);
        $headers = array_change_key_case($headers, CASE_LOWER);

        if (!array_key_exists($name, $headers)) {
            return null;
        }

        return $headers[$name];
    }
}
