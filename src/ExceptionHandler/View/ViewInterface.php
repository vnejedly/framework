<?php
namespace Hooloovoo\Framework\ExceptionHandler\View;

use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Interface ViewInterface
 */
interface ViewInterface
{
    const CONTENT_TYPE_HEADER = 'Content-Type';

    /**
     * @return string[]
     */
    public function getContentTypes() : array ;

    /**
     * @param Throwable $throwable
     * @return Response
     */
    public function getResponse(Throwable $throwable) : Response ;
}