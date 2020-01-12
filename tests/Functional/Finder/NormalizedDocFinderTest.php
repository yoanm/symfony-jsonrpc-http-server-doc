<?php
namespace Tests\Functional\Finder;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Yoanm\SymfonyJsonRpcHttpServerDoc\Finder\NormalizedDocFinder;
use Yoanm\SymfonyJsonRpcHttpServerDoc\Provider\DocProviderInterface;

/**
 * @covers \Yoanm\SymfonyJsonRpcHttpServerDoc\Finder\NormalizedDocFinder
 */
class NormalizedDocFinderTest extends TestCase
{
    /** @var NormalizedDocFinder */
    private $finder;

    protected function setUp(): void
    {
        $this->finder = new NormalizedDocFinder();
    }

    public function testShouldThrownAnExceptionIfProviderNotFound()
    {
        $filename = 'not-found.txt';
        $host = 'fake-host';
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(
            sprintf('No documentation provider found for "%s"/"%s"', $filename, $host)
        );

        $this->finder->findFor($filename, $host);
    }



    public function testShouldIterateOverProviderListThenPickFirstOneWhichSupportsFilenameAndHostAndReturnDoc()
    {
        $filename = 'filename.ext';
        $host = 'fake-host';
        $expectedReturn = ['return'];

        /** @var DocProviderInterface|ObjectProphecy $wrongProvider1 */
        $wrongProvider1 = $this->prophesize(DocProviderInterface::class);
        /** @var DocProviderInterface|ObjectProphecy $wrongProvider2 */
        $wrongProvider2 = $this->prophesize(DocProviderInterface::class);
        /** @var DocProviderInterface|ObjectProphecy $rightProvider */
        $rightProvider = $this->prophesize(DocProviderInterface::class);

        $wrongProvider1->supports($filename, $host)
            ->willReturn(false)
            ->shouldBeCalled()
        ;
        $wrongProvider2->supports($filename, $host)
            ->willReturn(false)
            ->shouldBeCalled()
        ;
        $rightProvider->supports($filename, $host)
            ->willReturn(true)
            ->shouldBeCalled()
        ;
        $rightProvider->getDoc($host)
            ->willReturn($expectedReturn)
            ->shouldBeCalled()
        ;

        $this->finder->addProvider($wrongProvider1->reveal());
        $this->finder->addProvider($wrongProvider2->reveal());
        $this->finder->addProvider($rightProvider->reveal());

        $this->assertSame(
            $expectedReturn,
            $this->finder->findFor($filename, $host)
        );
    }
}
