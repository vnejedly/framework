<?php
namespace Hooloovoo\Framework\ExceptionHandler\View;

use Hooloovoo\Framework\Encoder\EncoderInterface;

/**
 * Class AbstractCommonAPI
 */
abstract class AbstractCommonAPI implements ViewInterface
{
    const CONTENT_TYPE_PREFIX = 'application';

    /** @var EncoderInterface */
    protected $encoder;

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