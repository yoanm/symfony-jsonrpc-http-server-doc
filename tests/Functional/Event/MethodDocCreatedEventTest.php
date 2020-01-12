<?php
namespace Tests\Functional\Event;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Yoanm\JsonRpcServer\Domain\JsonRpcMethodInterface;
use Yoanm\JsonRpcServerDoc\Domain\Model\MethodDoc;
use Yoanm\SymfonyJsonRpcHttpServerDoc\Event\MethodDocCreatedEvent;

/**
 * @covers \Yoanm\SymfonyJsonRpcHttpServerDoc\Event\MethodDocCreatedEvent
 */
class MethodDocCreatedEventTest extends TestCase
{
    /** @var MethodDocCreatedEvent */
    private $event;

    /** @var MethodDoc|ObjectProphecy */
    private $doc;
    /** @var JsonRpcMethodInterface|ObjectProphecy|null */
    private $method;

    protected function setUp(): void
    {
        $this->doc = $this->prophesize(MethodDoc::class);
        $this->method = $this->prophesize(JsonRpcMethodInterface::class);

        $this->event = new MethodDocCreatedEvent(
            $this->doc->reveal()
        );
    }

    public function testShouldReturnTheDocAndNullMethodByDefault()
    {
        $this->assertSame(
            $this->doc->reveal(),
            $this->event->getDoc()
        );

        $this->assertNull($this->event->getMethod());
    }

    public function testShouldReturnTheMethodDefined()
    {
        $this->event->setMethod($this->method->reveal());

        $this->assertSame(
            $this->method->reveal(),
            $this->event->getMethod()
        );
    }

    public function testShouldHandleDocOverride()
    {
        $myDoc = $this->prophesize(MethodDoc::class);
        $this->event->setDoc($myDoc->reveal());

        $this->assertSame(
            $myDoc->reveal(),
            $this->event->getDoc()
        );
    }
}
