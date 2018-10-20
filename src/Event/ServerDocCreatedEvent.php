<?php
namespace Yoanm\SymfonyJsonRpcHttpServerDoc\Event;

use Yoanm\JsonRpcServerDoc\Domain\Model\ServerDoc;

/**
 * Class ServerDocCreatedEvent
 */
class ServerDocCreatedEvent extends DocEvent
{
    const EVENT_NAME = 'json_rpc_http_server_doc.server_doc_created';

    /** @var ServerDoc */
    private $doc;

    /**
     * @param ServerDoc $doc
     */
    public function __construct(ServerDoc $doc)
    {
        $this->doc = $doc;
    }

    /**
     * @return ServerDoc
     */
    public function getDoc() : ServerDoc
    {
        return $this->doc;
    }

    /**
     * @param ServerDoc $doc
     *
     * @return ServerDocCreatedEvent
     */
    public function setDoc(ServerDoc $doc) : ServerDocCreatedEvent
    {
        $this->doc = $doc;

        return $this;
    }
}
