<?php
namespace Hooloovoo\Framework\DI;

use Hooloovoo\DI\Container\ContainerInterface;
use Hooloovoo\DI\Definition\AbstractDefinitionClass;
use Hooloovoo\DI\ObjectHolder\Singleton;
use Hooloovoo\Framework\Command\CommandResolver;

/**
 * Class CommandDefinition
 */
class CommandDefinition extends AbstractDefinitionClass
{
    /** @var string */
    protected $commandNamespace;

    /** @var string */
    protected $commandName;

    /** @var string */
    protected $actionName;

    /**
     * CommandDefinition constructor.
     * @param string $commandNamespace
     * @param string $commandName
     * @param string $actionName
     */
    public function __construct(
        string $commandNamespace,
        string $commandName,
        string $actionName
    ) {
        $this->commandNamespace = $commandNamespace;
        $this->commandName = $commandName;
        $this->actionName = $actionName;
    }

    /**
     * @param ContainerInterface $container
     */
    public function setUpContainer(ContainerInterface $container)
    {
        $container->add(CommandResolver::class, new Singleton(function () use ($container) {
            return new CommandResolver(
                $container,
                $this->commandNamespace,
                $this->commandName,
                $this->actionName
            );
        }));
    }
}