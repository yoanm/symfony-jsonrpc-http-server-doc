<?php
namespace DemoApp\Listener;

use DemoApp\Method\MethodB;
use Yoanm\JsonRpcServerDoc\Domain\Model\Type\ArrayDoc;
use Yoanm\SymfonyJsonRpcHttpServerDoc\Event\MethodDocCreatedEvent;

/**
 * Class MethodDocCreatedListener
 */
class MethodDocCreatedListener
{
    /**
     * @param MethodDocCreatedEvent $event
     */
    public function enhanceMethodDoc(MethodDocCreatedEvent $event) : void
    {
        if ($event->getMethod() instanceof MethodB) {
            $event->getDoc()->setResultDoc(
                (new ArrayDoc())
                    ->setDescription('method a dataResult description')
            );
        }
    }
}
