<?php
namespace Hooloovoo\Framework\IOControl;

use Hooloovoo\Framework\Encoder\URL;
use Symfony\Component\HttpFoundation\Response;
use Twig_Environment as TwigEnvironment;

/**
 * Class Web
 */
class Web extends AbstractIO
{
    /** @var TwigEnvironment */
    private $twigEnvironment;

    /**
     * Web constructor.
     *
     * @param URL $encoder
     * @param TwigEnvironment $twigEnvironment
     */
    public function __construct(URL $encoder, TwigEnvironment $twigEnvironment)
    {
        $this->encoder = $encoder;
        $this->twigEnvironment = $twigEnvironment;
    }

    /**
     * @param string $template
     * @param array $data
     * @param int $status
     * @param array $headers
     * @return Response
     */
    public function getResponse(string $template, array $data, int $status = 200, array $headers = []) : Response
    {
        $content = $this->twigEnvironment->render($template, $data);
        return new Response($content, $status, $headers);
    }


    /**
     * @param string $contentType
     * @return bool
     */
    protected function matchContentType(string $contentType) : bool
    {
        return true;
    }
}