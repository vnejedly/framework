<?php
namespace Hooloovoo\Framework\QueryEngine;

use Hooloovoo\QueryEngine\Query\RequestConnector\ConnectorInterface ;
use Hooloovoo\QueryEngine\Query\RequestConnector\Exception\NoParamException;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SymfonyRequestConnector
 */
class SymfonyRequestConnector implements ConnectorInterface
{
    const PARAM_FILTER = 'filter';
    const PARAM_SORTER = 'sorter';
    const PARAM_OFFSET = 'offset';
    const PARAM_LIMIT = 'limit';

    /** @var Request */
    protected $request;

    /** @var int */
    protected $defaultOffset = 0;

    /** @var int */
    protected $defaultLimit = 10;

    /**
     * RequestConnector constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @return string
     * @throws NoParamException
     */
    public function getRawFilter()
    {
        return $this->getParamOrThrowException(static::PARAM_FILTER);
    }

    /**
     * @return string
     * @throws NoParamException
     */
    public function getRawSorter()
    {
        return $this->getParamOrThrowException(static::PARAM_SORTER);
    }

    /**
     * @return string
     */
    public function getRawOffset()
    {
        return (string) $this->request->get(static::PARAM_OFFSET, $this->defaultOffset);
    }

    /**
     * @return string
     */
    public function getRawLimit()
    {
        return (string) $this->request->get(static::PARAM_LIMIT, $this->defaultLimit);
    }

    /**
     * @param string $name
     * @return string
     * @throws NoParamException
     */
    protected function getParamOrThrowException(string $name) : string
    {
        $param = $this->request->get($name, null);

        if (is_null($param)) {
            throw new NoParamException("Parameter $name not present in request");
        }

        return (string) $param;
    }
}