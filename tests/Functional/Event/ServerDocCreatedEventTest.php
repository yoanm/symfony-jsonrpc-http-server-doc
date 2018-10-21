<?php
namespace Tests\Functional\Event;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Yoanm\JsonRpcServerDoc\Domain\Model\ServerDoc;
use Yoanm\SymfonyJsonRpcHttpServerDoc\Event\ServerDocCreatedEvent;

/**
 * @covers \Yoanm\SymfonyJsonRpcHttpServerDoc\Event\ServerDocCreatedEvent
 */
class ServerDocCreatedEventTest extends TestCase
{
    /** @var ServerDocCreatedEvent */
    private $event;

    /** @var ServerDoc|ObjectProphecy */
    private $doc;

    protected function setUp()
    {
        $this->doc = $this->prophesize(ServerDoc::class);

        $this->event = new ServerDocCreatedEvent(
            $this->doc->reveal()
        );
    }
    public function testShouldReturnTheDoc()
    {
        $this->assertSame(
            $this->doc->reveal(),
            $this->event->getDoc()
        );
    }

    public function testShouldHandleDocOverride()
    {
        $myDoc = $this->prophesize(ServerDoc::class);
        $this->event->setDoc($myDoc->reveal());

        $this->assertSame(
            $myDoc->reveal(),
            $this->event->getDoc()
        );
    }
}
