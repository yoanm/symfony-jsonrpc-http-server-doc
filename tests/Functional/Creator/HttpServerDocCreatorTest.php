<?php
namespace Tests\Functional\Creator;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Yoanm\JsonRpcServer\Domain\JsonRpcMethodInterface;
use Yoanm\JsonRpcServerDoc\Domain\Model\MethodDoc;
use Yoanm\SymfonyJsonRpcHttpServerDoc\Creator\HttpServerDocCreator;
use Yoanm\SymfonyJsonRpcHttpServerDoc\Event\MethodDocCreatedEvent;
use Yoanm\SymfonyJsonRpcHttpServerDoc\Event\ServerDocCreatedEvent;

/**
 * @covers \Yoanm\SymfonyJsonRpcHttpServerDoc\Creator\HttpServerDocCreator
 */
class HttpServerDocCreatorTest extends TestCase
{
    use ProphecyTrait;

    /** @var HttpServerDocCreator */
    private $creator;

    /** @var EventDispatcherInterface|ObjectProphecy */
    private $dispatcher;
    /** @var string|null */
    private $jsonRpcEndpoint = 'my-endpoint';

    protected function setUp(): void
    {
        $this->dispatcher = $this->prophesize(EventDispatcherInterface::class);

        $this->creator = new HttpServerDocCreator(
            $this->dispatcher->reveal(),
            $this->jsonRpcEndpoint
        );
    }

    public function testShouldCreateBasicDocWithoutMethods()
    {
        $this->dispatcher->dispatch(Argument::type(ServerDocCreatedEvent::class), ServerDocCreatedEvent::EVENT_NAME)
            ->willReturnArgument(0)
            ->shouldBeCalled();

        $doc = $this->creator->create();

        $this->assertSame(
            $doc->getEndpoint(),
            $this->jsonRpcEndpoint
        );

        $this->assertNull($doc->getHost());
        $this->assertNull($doc->getBasePath());
        $this->assertEmpty($doc->getSchemeList());
        $this->assertNull($doc->getName());
        $this->assertNull($doc->getVersion());
        $this->assertEmpty($doc->getMethodList());
        $this->assertEmpty($doc->getTagList());
        $this->assertEmpty($doc->getServerErrorList());
        $this->assertEmpty($doc->getGlobalErrorList());
    }

    public function testShouldCreateDocWithHost()
    {
        $this->dispatcher->dispatch(Argument::type(ServerDocCreatedEvent::class), ServerDocCreatedEvent::EVENT_NAME)
            ->willReturnArgument(0)
            ->shouldBeCalled();

        $host = 'my-host';
        $doc = $this->creator->create($host);

        $this->assertSame(
            $doc->getEndpoint(),
            $this->jsonRpcEndpoint
        );

        $this->assertSame($doc->getHost(), $host);
        $this->assertNull($doc->getBasePath());
        $this->assertEmpty($doc->getSchemeList());
        $this->assertNull($doc->getName());
        $this->assertNull($doc->getVersion());
        $this->assertEmpty($doc->getTagList());
        $this->assertEmpty($doc->getMethodList());
        $this->assertEmpty($doc->getServerErrorList());
        $this->assertEmpty($doc->getGlobalErrorList());
    }

    public function testShouldCreateDocWithMethodList()
    {
        $this->dispatcher->dispatch(Argument::type(ServerDocCreatedEvent::class), ServerDocCreatedEvent::EVENT_NAME)
            ->willReturnArgument(0)
            ->shouldBeCalled();

        $this->dispatcher->dispatch(Argument::type(MethodDocCreatedEvent::class), MethodDocCreatedEvent::EVENT_NAME)
            ->willReturnArgument(0)
            ->shouldBeCalledTimes(2);

        $method1Name = 'method-1';
        $method2Name = 'method-2';
        $method1 = $this->prophesize(JsonRpcMethodInterface::class);
        $method2 = $this->prophesize(JsonRpcMethodInterface::class);

        $this->creator->addJsonRpcMethod($method1Name, $method1->reveal());
        $this->creator->addJsonRpcMethod($method2Name, $method2->reveal());

        $doc = $this->creator->create();

        $this->assertSame(
            $doc->getEndpoint(),
            $this->jsonRpcEndpoint
        );

        $this->assertNull($doc->getHost());
        $this->assertNull($doc->getBasePath());
        $this->assertEmpty($doc->getSchemeList());
        $this->assertNull($doc->getName());
        $this->assertNull($doc->getVersion());
        $this->assertEmpty($doc->getTagList());
        $methodList = $doc->getMethodList();
        $this->assertCount(2, $methodList);
        $actualMethodDoc1 = array_shift($methodList);
        $actualMethodDoc2 = array_shift($methodList);
        $this->assertInstanceOf(MethodDoc::class, $actualMethodDoc1);
        $this->assertSame($method1Name, $actualMethodDoc1->getMethodName());
        $this->assertInstanceOf(MethodDoc::class, $actualMethodDoc2);
        $this->assertSame($method2Name, $actualMethodDoc2->getMethodName());
        $this->assertEmpty($doc->getServerErrorList());
        $this->assertEmpty($doc->getGlobalErrorList());
    }

    public function testShouldDispatchServerDocCreatedEvent()
    {
        $docInEvent = null;

        $this->dispatcher->dispatch(Argument::type(ServerDocCreatedEvent::class), ServerDocCreatedEvent::EVENT_NAME)
            ->will(function ($args) use (&$docInEvent) {
                $docInEvent = $args[0]->getDoc();

                return $args[0];
            })
            ->shouldBeCalled();

        $doc = $this->creator->create();

        $this->assertSame($docInEvent, $doc);
    }

    public function testShouldDispatchMethodDocCreatedEvent()
    {
        $docInEvent1 = null;
        $docInEvent2 = null;
        $method1Name = 'method-1';
        $method2Name = 'method-2';
        $method1 = $this->prophesize(JsonRpcMethodInterface::class);
        $method2 = $this->prophesize(JsonRpcMethodInterface::class);

        $this->creator->addJsonRpcMethod($method1Name, $method1->reveal());
        $this->creator->addJsonRpcMethod($method2Name, $method2->reveal());


        $this->dispatcher->dispatch(Argument::type(ServerDocCreatedEvent::class), ServerDocCreatedEvent::EVENT_NAME)
            ->willReturnArgument(0)
            ->shouldBeCalled();

        $this->dispatcher->dispatch(
            Argument::allOf(
                Argument::type(MethodDocCreatedEvent::class),
                Argument::which('getMethod', $method1->reveal())
            ),
            MethodDocCreatedEvent::EVENT_NAME
        )
            ->will(function ($args) use (&$docInEvent1) {
                $docInEvent1 = $args[0]->getDoc();

                return $args[0];
            })
            ->shouldBeCalled();
        $this->dispatcher->dispatch(
            Argument::allOf(
                Argument::type(MethodDocCreatedEvent::class),
                Argument::which('getMethod', $method2->reveal())
            ),
            MethodDocCreatedEvent::EVENT_NAME
        )
            ->will(function ($args) use (&$docInEvent2) {
                $docInEvent2 = $args[0]->getDoc();

                return $args[0];
            })
            ->shouldBeCalled();


        $doc = $this->creator->create();

        [$method1Doc, $method2Doc] = $doc->getMethodList();
        $this->assertSame($method1Doc, $docInEvent1);
        $this->assertSame($method2Doc, $docInEvent2);
    }
}
