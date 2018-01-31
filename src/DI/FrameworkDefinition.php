<?php
namespace Hooloovoo\Framework\DI;

use Hooloovoo\DI\Container\ContainerInterface;
use Hooloovoo\DI\Definition\AbstractDefinitionClass;
use Hooloovoo\DI\ObjectHolder\Singleton;
use Hooloovoo\Framework\ControllerResolver;
use Hooloovoo\Framework\EventListener\ExceptionListener;
use Hooloovoo\Framework\ExceptionHandler\Handler;
use Hooloovoo\Framework\ExceptionHandler\HandlerFactory;
use Psr\Log\LoggerInterface;
use React\EventLoop\LoopInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Hooloovoo\Framework\ExceptionHandler\View\Debug\JsonAPI as JsonAPIDebug;
use Hooloovoo\Framework\ExceptionHandler\View\Production\JsonAPI as JsonAPIProduction;
use React\EventLoop\Factory as EventLoopFactory;
use React\Socket\Server as ReactSocketServer;
use React\Http\Server as ReactHttpServer;

/**
 * Class FrameworkDefinition
 */
class FrameworkDefinition extends AbstractDefinitionClass
{
    /** @var string */
    protected $environment;

    /** @var string */
    protected $appRoot;

    /** @var string */
    protected $configPath;

    /** @var string */
    protected $templatePath;

    /**
     * ContainerDefinition constructor.
     *
     * @param string $environment
     * @param string $appRoot
     * @param string $configPath
     * @param string $templatePath
     */
    public function __construct(
        string $environment,
        string $appRoot,
        string $configPath,
        string $templatePath
    ) {
        $this->environment = $environment;
        $this->appRoot = $appRoot;
        $this->configPath = $configPath;
        $this->templatePath = $templatePath;
    }

    /**
     * @param ContainerInterface $container
     */
    public function setUpContainer(ContainerInterface $container)
    {
        $container->add(LoggerInterface::class, new Singleton(function () {
            return null;
        }));

        $container->addFactory(HandlerFactory::class, Handler::class, function () use ($container) {
            $handlerFactory = new HandlerFactory($this->environment);
            $handlerFactory->setLogger($container->get(LoggerInterface::class));
            $handlerFactory->addDebugView($container->get(JsonAPIDebug::class));
            $handlerFactory->addProductionView($container->get(JsonAPIProduction::class));

            return $handlerFactory;
        });

        $container->add(RouteCollection::class, new Singleton(function () {
            $locator = new FileLocator([$this->configPath]);
            $loader = new YamlFileLoader($locator);

            return $loader->load('routes.yml');
        }));

        $container->add(RouterListener::class, new Singleton(function () use ($container) {
            return new RouterListener($container->get(UrlMatcher::class), $container->get(RequestStack::class));
        }));

        $container->add(RequestContext::class, new Singleton(function () {
            return new RequestContext();
        }));

        $container->add(ArgumentResolver::class, new Singleton(function () {
            return new ArgumentResolver();
        }));

        $container->add(EventDispatcher::class, new Singleton(function () use ($container) {
            $eventDispatcher = new EventDispatcher();
            $eventDispatcher->addSubscriber($container->get(RouterListener::class));
            $eventDispatcher->addSubscriber($container->get(ExceptionListener::class));

            return $eventDispatcher;
        }));

        $container->add(ControllerResolver::class, new Singleton(function () use ($container) {
            return new ControllerResolver($container, $container->get(LoggerInterface::class));
        }));

        $container->add(HttpKernelInterface::class, new Singleton(function () use ($container) {
            return new HttpKernel(
                $container->get(EventDispatcher::class),
                $container->get(ControllerResolver::class),
                $container->get(RequestStack::class),
                $container->get(ArgumentResolver::class)
            );
        }));

        $container->add(LoopInterface::class, new Singleton(function () {
            return EventLoopFactory::create();
        }));

        $container->add(ReactSocketServer::class, new Singleton(function () use ($container)  {
            return new ReactSocketServer($container->get(LoopInterface::class));
        }));

        $container->add(ReactHttpServer::class, new Singleton(function () use ($container)  {
            return new ReactHttpServer($container->get(ReactSocketServer::class));
        }));
    }
}