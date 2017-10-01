<?php
namespace Hooloovoo\Framework\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactory as SymfonyFormFactory;
use Symfony\Component\Form\FormRegistryInterface;
use Symfony\Component\Form\RequestHandlerInterface;
use Symfony\Component\Form\ResolvedFormTypeFactoryInterface;

/**
 * Class FormFactory
 */
class FormFactory extends SymfonyFormFactory
{
    /** @var RequestHandlerInterface */
    protected $requestHandler;

    /**
     * FormFactory constructor.
     *
     * @param FormRegistryInterface $registry
     * @param ResolvedFormTypeFactoryInterface $resolvedTypeFactory
     * @param RequestHandlerInterface $requestHandler
     */
    public function __construct(
        FormRegistryInterface $registry,
        ResolvedFormTypeFactoryInterface $resolvedTypeFactory,
        RequestHandlerInterface $requestHandler
    ) {
        parent::__construct($registry, $resolvedTypeFactory);
        $this->requestHandler = $requestHandler;
    }

    /**
     * @param int|string $name
     * @param string $type
     * @param null $data
     * @param array $options
     * @return FormBuilderInterface
     */
    public function createNamedBuilder(
        $name,
        $type = 'Symfony\Component\Form\Extension\Core\Type\FormType',
        $data = null,
        array $options = []
    ) {
        $builder = parent::createNamedBuilder($name, $type, $data, $options);
        $builder->setRequestHandler($this->requestHandler);

        return $builder;
    }
}