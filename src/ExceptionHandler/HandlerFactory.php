<?php
namespace Hooloovoo\Framework\ExceptionHandler;

use Exception;
use Hooloovoo\DI\Factory\AbstractFactory;
use Hooloovoo\Framework\ExceptionHandler\View\ViewInterface;
use Psr\Log\LoggerInterface;

/**
 * Class HandlerFactory
 */
class HandlerFactory extends AbstractFactory
{
    const ENV_PROD = 'prod';
    const ENV_STAGE = 'stage';
    const ENV_DEV = 'dev';

    /** @var string */
    protected $environment;

    /** @var LoggerInterface */
    protected $logger = null;

    /** @var ViewInterface[] */
    protected $debugViews = [];

    /** @var ViewInterface[] */
    protected $productionViews = [];

    /**
     * HandlerFactory constructor.
     *
     * @param string $environment
     * @throws Exception
     */
    public function __construct(string $environment)
    {
        $this->environment = $environment;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * @param ViewInterface $view
     */
    public function addDebugView(ViewInterface $view)
    {
        $this->debugViews[] = $view;
    }

    /**
     * @param ViewInterface $view
     */
    public function addProductionView(ViewInterface $view)
    {
        $this->productionViews[] = $view;
    }

    /**
     * @return Handler
     * @throws Exception
     */
    public function getNew()
    {
        $handler = new Handler();
        $handler->setLogger($this->logger);

        if (
            $this->environment === self::ENV_PROD ||
            $this->environment === self::ENV_STAGE
        ) {
            $views = $this->productionViews;
        } elseif ($this->environment === self::ENV_DEV) {
            $views = $this->debugViews;
        } else {
            throw new Exception("Unknown environment '{$this->environment}'");
        }

        foreach ($views as $view) {
            $handler->addView($view);
        }

        return $handler;
    }
}