<?php
namespace Hooloovoo\Framework\ExceptionHandler\View;

use Hooloovoo\Framework\Encoder\EncoderInterface;
use Hooloovoo\ORM\Exception\HttpExceptionInterface as HttpExceptionORM;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface as HttpExceptionSymfony;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Class AbstractCommonAPI
 */
abstract class AbstractCommonAPI implements ViewInterface
{
    const CONTENT_TYPE_PREFIX = 'application';

    /** @var EncoderInterface */
    protected $encoder;

    /**
     * @param LoggerInterface $logger
     * @param Throwable $throwable
     */
    public function log(LoggerInterface $logger, Throwable $throwable)
    {
        if (
            $throwable instanceof HttpExceptionSymfony |
            $throwable instanceof HttpExceptionORM
        ) {
            $logger->info($throwable->getMessage());
        } else {
            $logger->error($throwable->getMessage());
        }
    }

    /**
     * @return string[]
     */
    public function getContentTypes() : array
    {
        return [$this->getContentType()];
    }

    /**
     * @return string
     */
    protected function getContentType() : string
    {
        return self::CONTENT_TYPE_PREFIX . '/' . $this->encoder->getType();
    }
}