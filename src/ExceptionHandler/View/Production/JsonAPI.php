<?php
namespace Hooloovoo\Framework\ExceptionHandler\View\Production;

use Hooloovoo\Framework\Encoder\JSON;

/**
 * Class JsonAPI
 */
class JsonAPI extends AbstractAPI
{
    /**
     * AbstractAPI constructor.
     * @param JSON $encoder
     */
    public function __construct(JSON $encoder)
    {
        $this->encoder = $encoder;
    }
}