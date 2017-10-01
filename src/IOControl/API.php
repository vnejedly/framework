<?php
namespace Hooloovoo\Framework\IOControl;

use Hooloovoo\DataObjects\DataObjectInterface;
use Hooloovoo\Framework\Encoder\EncoderInterface;
use Hooloovoo\Framework\QueryEngine\SymfonyRequestConnector;
use Hooloovoo\QueryEngine\Query\Parser\ParserInterface as QueryEngineParser;
use Hooloovoo\QueryEngine\Query\Query;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class API
 */
class API extends AbstractIO
{
    const CONTENT_TYPE_PREFIX = 'application';
    
    /** @var QueryEngineParser */
    protected $queryEngineParser;

    /**
     * API constructor.
     * @param EncoderInterface $encoder
     * @param QueryEngineParser $queryEngineParser
     */
    public function __construct(
        EncoderInterface $encoder,
        QueryEngineParser $queryEngineParser
    ) {
        $this->encoder = $encoder;
        $this->queryEngineParser = $queryEngineParser;
    }

    /**
     * @param array $data
     * @param int $status
     * @param array $headers
     * @return Response
     */
    public function getResponse(array $data, int $status = 200, array $headers = []) : Response
    {
        $content = $this->encoder->encode($data);
        $headers[self::CONTENT_TYPE_HEADER] = $this->getContentType();

        return new Response($content, $status, $headers);
    }

    /**
     * @param DataObjectInterface $dataObject
     * @param int $status
     * @param array $headers
     * @return Response
     */
    public function getResponseObject(
        DataObjectInterface $dataObject, 
        int $status = 200, 
        array $headers = []
    ) : Response
    {
        $data = $dataObject->getSerialized();
        return $this->getResponse($data, $status, $headers);
    }

    /**
     * @param DataObjectInterface[] $collection
     * @param int $status
     * @param array $headers
     * @return Response
     */
    public function getResponseCollection(
        array $collection,
        int $status = 200,
        array $headers = []
    ) : Response
    {
        $data = [];
        foreach ($collection as $object) {
            $data[] = $object->getSerialized();
        }
        
        return $this->getResponse($data, $status, $headers);
    }

    /**
     * @param Request $request
     * @return Query
     */
    public function getQuery(Request $request) : Query
    {
        $eqlConnector = new SymfonyRequestConnector($request);
        return new Query($eqlConnector, $this->queryEngineParser);
    }

    /**
     * @return string
     */
    public function getContentType() : string
    {
        return self::CONTENT_TYPE_PREFIX . '/' . $this->encoder->getType();
    }

    /**
     * @param string $contentType
     * @return bool
     */
    protected function matchContentType(string $contentType) : bool
    {
        return ($contentType === $this->getContentType());
    }
}