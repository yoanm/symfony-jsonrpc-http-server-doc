<?php
namespace Yoanm\SymfonyJsonRpcHttpServerDoc\Creator;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Yoanm\JsonRpcServer\Domain\JsonRpcMethodInterface;
use Yoanm\JsonRpcServerDoc\Model\HttpServerDoc;
use Yoanm\JsonRpcServerDoc\Model\MethodDoc;
use Yoanm\JsonRpcServerDoc\Model\ServerDoc;
use Yoanm\SymfonyJsonRpcHttpServer\Model\MethodMappingAwareInterface;
use Yoanm\SymfonyJsonRpcHttpServerDoc\Event\MethodDocCreatedEvent;
use Yoanm\SymfonyJsonRpcHttpServerDoc\Event\ServerDocCreatedEvent;

/**
 * Class HttpServerDocCreator
 */
class HttpServerDocCreator implements MethodMappingAwareInterface
{
    /** @var EventDispatcherInterface */
    private $dispatcher;
    /** @var JsonRpcMethodInterface[] */
    private $methodList = [];
    /** @var string|null */
    private $jsonRpcEndpoint = null;

    /**
     * @param EventDispatcherInterface $dispatcher
     * @param null $jsonRpcEndpoint
     */
    public function __construct(EventDispatcherInterface $dispatcher, $jsonRpcEndpoint = null)
    {
        $this->dispatcher = $dispatcher;
        $this->jsonRpcEndpoint = $jsonRpcEndpoint;
    }
    /**
     * @param string|null $host
     *
     * @return HttpServerDoc
     */
    public function create($host = null)
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

        return $event->getDoc();
    }

    /**
     * {@inheritdoc}
     */
    public function addMethodMapping($methodName, JsonRpcMethodInterface $method)
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
            ))
                ->setMethod($method);

            $this->dispatcher->dispatch($event::EVENT_NAME, $event);

            $serverDoc->addMethod($event->getDoc());
        }
    }
}
