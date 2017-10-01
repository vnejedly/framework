<?php
namespace Hooloovoo\Framework\EventListener;

use Hooloovoo\Framework\ExceptionHandler\Handler;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class ExceptionListener
 */
class ExceptionListener implements EventSubscriberInterface
{
    /** @var Handler */
    protected $exceptionHandler;

    /**
     * ExceptionListener constructor.
     * @param Handler $exceptionHandler
     */
    public function __construct(Handler $exceptionHandler)
    {
        $this->exceptionHandler = $exceptionHandler;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $response = $this->exceptionHandler->handle($event->getException(), $event->getRequest());
        $event->setResponse($response);
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            KernelEvents::EXCEPTION => array('onKernelException', -128),
        );
    }
}