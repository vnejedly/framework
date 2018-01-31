<?php
namespace Hooloovoo\Framework\ExceptionHandler;

use Hooloovoo\Framework\ExceptionHandler\View\ViewInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class Handler
 */
class Handler
{
    /** @var LoggerInterface */
    protected $logger = null;

    /** @var string */
    protected $defaultContentType = 'application/json';

    /** @var ViewInterface[] */
    protected $views = [];

    /**
     * @param ViewInterface $view
     */
    public function addView(ViewInterface $view)
    {
        foreach ($view->getContentTypes() as $contentType) {
            $this->views[$contentType] = $view;
        }
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * @param Throwable $throwable
     * @param Request $request
     * @return Response
     */
    public function handle(Throwable $throwable, Request $request) : Response
    {
        $contentType = $this->defaultContentType;
        if ($request->headers->has(ViewInterface::CONTENT_TYPE_HEADER)) {
            $contentType = $request->headers->get(ViewInterface::CONTENT_TYPE_HEADER);
        }

        $view = $this->getView($contentType);

        if (!is_null($this->logger)) {
            $view->log($this->logger, $throwable);
        }

        return $view->getResponse($throwable);
    }

    /**
     * @param string $contentType
     * @return ViewInterface
     */
    protected function getView(string $contentType) : ViewInterface
    {
        $baseType = $this->defaultContentType;
        if (preg_match('/([\w, \-]+\/[\w, \-]+)/', $contentType, $matches)) {
            $baseType = $matches[1];
        }

        if (!in_array($baseType, array_keys($this->views))) {
            $baseType = $this->defaultContentType;
        }

        return $this->views[$baseType];
    }
}