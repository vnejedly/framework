<?php
namespace Hooloovoo\Framework\ExceptionHandler\View\Debug;

use Hooloovoo\Framework\ExceptionHandler\View\AbstractCommonAPI;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class AbstractAPI
 */
abstract class AbstractAPI extends AbstractCommonAPI
{
    /**
     * @param Throwable $throwable
     * @return Response
     */
    public function getResponse(Throwable $throwable) : Response
    {
        $code = 500;
        if ($throwable instanceof HttpExceptionInterface) {
            $code = $throwable->getStatusCode();
        }

        $data = $this->getExceptionData($throwable);

        return new Response($this->encoder->encode($data), $code, [
            self::CONTENT_TYPE_HEADER => $this->getContentType()
        ]);
    }

    /**
     * @param Throwable $throwable
     * @return array
     */
    protected function getExceptionData(Throwable $throwable) : array
    {
        return [
            'error' => [
                'code' => $throwable->getCode(),
                'name' => get_class($throwable),
                'message' => $throwable->getMessage(),
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
                'backTrace' => $this->getBacktrace($throwable),
            ]
        ];
    }

    /**
     * @param Throwable $throwable
     * @return array
     */
    protected function getBacktrace(Throwable $throwable) : array
    {
        $backTrace = $throwable->getTrace();

        array_walk($backTrace, function (&$item) {
            $arguments = [];
            foreach ($item['args'] as $arg) {
                if (is_object($arg)) {
                    $arguments[] = '(object ' . get_class($arg) . ')';
                } elseif (is_array($arg)) {
                    $arguments[] = '(array...)';
                } elseif (is_resource($arg)) {
                    $arguments[] = '(resource)';
                } else {
                    $arguments[] = $arg;
                }
            }
            $item['args'] = $arguments;
        });

        return $backTrace;
    }
}