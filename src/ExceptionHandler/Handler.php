<?php
namespace Hooloovoo\Framework\ExceptionHandler;

use Hooloovoo\Framework\ExceptionHandler\View\ViewInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class Handler
 */
class Handler
{
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