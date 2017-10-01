<?php
namespace Hooloovoo\Framework;

use Exception;
use Hooloovoo\Framework\Controller\ControllerInterface;
use Psr\Log\LoggerInterface;
use Hooloovoo\DI\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolver as SymfonyControllerResolver;

/**
 * Class ControllerResolver
 */
class ControllerResolver extends SymfonyControllerResolver
{
    /** @var ContainerInterface */
    protected $container;

    /**
     * ControllerResolver constructor.
     *
     * @param ContainerInterface $container
     * @param LoggerInterface|null $logger
     */
    public function __construct(ContainerInterface $container, LoggerInterface $logger = null)
    {
        $this->container = $container;
        parent::__construct($logger);
    }

    /**
     * @param Request $request
     * @return callable|false|mixed|object
     * @throws Exception
     */
    public function getController(Request $request)
    {
        $controller = parent::getController($request);
        $controllerClass = $controller[0];

        if (!$controllerClass instanceof ControllerInterface) {
            $controllerName = get_class($controllerClass);
            $controllerInterfaceName = ControllerInterface::class;
            throw new Exception("Controller {$controllerName} must implement {$controllerInterfaceName}");
        }

        $controllerClass->getIOControl()->prepareRequest($request);

        return $controller;
    }

    /**
     * @param string $class
     * @return object
     */
    protected function instantiateController($class)
    {
        $controller = $this->container->get($class);
        return $controller;
    }
}