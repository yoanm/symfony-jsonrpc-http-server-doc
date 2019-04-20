<?php
namespace DemoApp\Listener;

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
