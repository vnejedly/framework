<?php
namespace Hooloovoo\Framework\Command;

use Exception;
use Hooloovoo\DI\Container\ContainerInterface;

/**
 * Class CommandResolver
 */
class CommandResolver
{
    const COMMAND_SUFFIX = 'Command';
    const ACTION_SUFFIX = 'Action';

    /** @var ContainerInterface */
    protected $container;

    /** @var string */
    protected $commandNamespace;

    /** @var string */
    protected $commandName;

    /** @var string */
    protected $actionName;

    /** @var array */
    protected $arguments = [];

    /**
     * CommandResolver constructor.
     * @param ContainerInterface $container
     * @param string $commandNamespace
     * @param string $commandName
     * @param string $actionName
     */
    public function __construct(
        ContainerInterface $container,
        string $commandNamespace,
        string $commandName,
        string $actionName
    ) {
        $this->container = $container;
        $this->commandNamespace = $commandNamespace;
        $this->commandName = $commandName;
        $this->actionName = $actionName;
    }

    /**
     * @param $value
     */
    public function addArgument($value)
    {
        $this->arguments[] = $value;
    }

    /**
     * @throws Exception
     */
    public function execute()
    {
        $className = $this->commandNamespace . '\\' . ucfirst($this->commandName) . self::COMMAND_SUFFIX;
        $methodName = $this->actionName . self::ACTION_SUFFIX;
        $commandInstance = $this->container->get($className);

        if (!method_exists($commandInstance, $methodName)) {
            throw new Exception("Method $methodName does not exist in $className");
        }

        call_user_func_array([$commandInstance, $methodName], $this->arguments);
    }
}