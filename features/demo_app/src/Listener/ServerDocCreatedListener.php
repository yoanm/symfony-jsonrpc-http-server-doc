<?php
namespace DemoApp\Listener;

use DemoApp\Method\MethodB;
use Yoanm\JsonRpcServerDoc\Domain\Model\Type\ArrayDoc;
use Yoanm\SymfonyJsonRpcHttpServerDoc\Event\ServerDocCreatedEvent;

/**
 * Class ServerDocCreatedListener
 */
class ServerDocCreatedListener
{
    /**
     * @param ServerDocCreatedEvent $event
     */
    public function enhanceServerDoc(ServerDocCreatedEvent $event) : void
    {
        $event->getDoc()->setName("my custom server doc description");
    }
}
