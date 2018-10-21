<?php
namespace Yoanm\SymfonyJsonRpcHttpServerDoc\Creator;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Yoanm\JsonRpcServer\Domain\JsonRpcMethodAwareInterface;
use Yoanm\JsonRpcServer\Domain\JsonRpcMethodInterface;
use Yoanm\JsonRpcServerDoc\Domain\Model\HttpServerDoc;
use Yoanm\JsonRpcServerDoc\Domain\Model\MethodDoc;
use Yoanm\JsonRpcServerDoc\Domain\Model\ServerDoc;
use Yoanm\SymfonyJsonRpcHttpServerDoc\Event\MethodDocCreatedEvent;
use Yoanm\SymfonyJsonRpcHttpServerDoc\Event\ServerDocCreatedEvent;

/**
 * Class HttpServerDocCreator
 */
class HttpServerDocCreator implements JsonRpcMethodAwareInterface
{
    /** @var EventDispatcherInterface */
    private $dispatcher;
    /** @var JsonRpcMethodInterface[] */
    private $methodList = [];
    /** @var string|null */
    private $jsonRpcEndpoint = null;

    /**
     * @param EventDispatcherInterface $dispatcher
     * @param string|null              $jsonRpcEndpoint
     */
    public function __construct(EventDispatcherInterface $dispatcher, string $jsonRpcEndpoint = null)
    {
        $this->dispatcher = $dispatcher;
        $this->jsonRpcEndpoint = $jsonRpcEndpoint;
    }
    /**
     * @param string|null $host
     *
     * @return HttpServerDoc
     */
    public function create($host = null) : HttpServerDoc
    {
        $serverDoc = new HttpServerDoc();
        if (null !== $this->jsonRpcEndpoint) {
            $serverDoc->setEndpoint($this->jsonRpcEndpoint);
        }
        if (null !== $host) {
            $serverDoc->setHost($host);
        }

        $this->appendMethodsDoc($serverDoc);

        $event = new ServerDocCreatedEvent($serverDoc);
        $this->dispatcher->dispatch($event::EVENT_NAME, $event);

        return $serverDoc;
    }

    /**
     * {@inheritdoc}
     */
    public function addJsonRpcMethod(string $methodName, JsonRpcMethodInterface $method) : void
    {
        $this->methodList[$methodName] = $method;
    }

    /**
     * @param ServerDoc $serverDoc
     */
    protected function appendMethodsDoc(ServerDoc $serverDoc)
    {
        foreach ($this->methodList as $methodName => $method) {
            $event = (
                new MethodDocCreatedEvent(
                    new MethodDoc($methodName)
                )
            )
                ->setMethod($method);

            $this->dispatcher->dispatch($event::EVENT_NAME, $event);

            $serverDoc->addMethod($event->getDoc());
        }
    }
}
