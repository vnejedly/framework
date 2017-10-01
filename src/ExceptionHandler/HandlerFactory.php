<?php
namespace Hooloovoo\Framework\ExceptionHandler;

use Exception;
use Hooloovoo\DI\Factory\AbstractFactory;
use Hooloovoo\Framework\ExceptionHandler\View\ViewInterface;

/**
 * Class HandlerFactory
 */
class HandlerFactory extends AbstractFactory
{
    const ENV_PROD = 'prod';
    const ENV_DEV = 'dev';

    /** @var string */
    protected $environment;

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

        if ($this->environment === self::ENV_PROD) {
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