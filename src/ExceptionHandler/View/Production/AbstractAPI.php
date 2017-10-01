<?php
namespace Hooloovoo\Framework\ExceptionHandler\View\Production;

use Hooloovoo\Framework\ExceptionHandler\View\AbstractCommonAPI;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Throwable;

abstract class AbstractAPI extends AbstractCommonAPI
{
    /**
     * @param Throwable $throwable
     * @return Response
     */
    public function getResponse(Throwable $throwable) : Response
    {
        $data = $this->getExceptionData($throwable, $code);

        return new Response($this->encoder->encode($data), $code, [
            self::CONTENT_TYPE_HEADER => $this->getContentType()
        ]);
    }

    /**
     * @param Throwable $throwable
     * @param int $code
     * @return array
     */
    protected function getExceptionData(Throwable $throwable, int &$code = null)
    {
        if ($throwable instanceof HttpExceptionInterface) {
            $message = $throwable->getMessage();
            $code = $throwable->getStatusCode();
        } elseif ($throwable instanceof ResourceNotFoundException) {
            $message = $throwable->getMessage();
            $code = Response::HTTP_NOT_FOUND;
        } elseif ($throwable instanceof MethodNotAllowedException) {
            $message = $throwable->getMessage();
            $code = Response::HTTP_METHOD_NOT_ALLOWED;
        } else {
            $message = 'An error occured';
            $code = 500;
        }

        return [
            'error' => $message
        ];
    }
}