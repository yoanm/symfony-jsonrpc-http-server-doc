<?php
namespace Yoanm\SymfonyJsonRpcHttpServerDoc\Event;

use Yoanm\JsonRpcServer\Domain\JsonRpcMethodInterface;
use Yoanm\JsonRpcServerDoc\Model\MethodDoc;

/**
 * Class MethodDocCreatedEvent
 */
class MethodDocCreatedEvent extends DocEvent
{
    const EVENT_NAME = 'json_rpc_http_server.method_doc_created';

    /** @var MethodDoc */
    private $doc;
    /** @var JsonRpcMethodInterface|null */
    private $method;

    /**
     * @param MethodDoc $doc
     */
    public function __construct(MethodDoc $doc)
    {
        $this->doc = $doc;
    }

    /**
     * @return MethodDoc
     */
    public function getDoc()
    {
        return $this->doc;
    }

    /**
     * @return null|JsonRpcMethodInterface
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param JsonRpcMethodInterface $method
     *
     * @return MethodDocCreatedEvent
     */
    public function setMethod(JsonRpcMethodInterface $method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @param MethodDoc $doc
     *
     * @return MethodDocCreatedEvent
     */
    public function setDoc(MethodDoc $doc)
    {
        $this->doc = $doc;

        return $this;
    }
}
