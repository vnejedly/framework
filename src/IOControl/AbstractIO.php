<?php
namespace Hooloovoo\Framework\IOControl;

use Hooloovoo\Framework\Encoder\EncoderInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class AbstractIO
 */
abstract class AbstractIO implements IOControlInterface
{
    const CONTENT_TYPE_HEADER = 'Content-Type';
    const METHODS_NO_DATA = ['GET', 'DELETE'];

    /** @var EncoderInterface */
    protected $encoder;

    /**
     * @param Request $request
     */
    public function prepareRequest(Request $request)
    {
        $request->request = $this->getParameters($request);
    }

    /**
     * @param Request $request
     * @return array
     * @throws BadRequestHttpException
     */
    protected function getData(Request $request) : array
    {
        if (in_array($request->getMethod(), self::METHODS_NO_DATA)) {
            return [];
        }

        if (!$request->headers->has(self::CONTENT_TYPE_HEADER)) {
            throw new BadRequestHttpException("Content-Type header missing");
        }

        $contentType = $request->headers->get(self::CONTENT_TYPE_HEADER);
        if (!$this->matchContentType($contentType)) {
            throw new BadRequestHttpException("Wrong Content-Type: $contentType");
        }

        return $this->encoder->decode($request->getContent());
    }

    /**
     * @param Request $request
     * @return ParameterBag
     */
    protected function getParameters(Request $request) : ParameterBag
    {
        return new ParameterBag($this->getData($request));
    }

    /**
     * @param string $contentType
     * @return bool
     */
    abstract protected function matchContentType(string $contentType) : bool ;
}