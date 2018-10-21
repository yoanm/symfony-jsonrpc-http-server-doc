<?php
namespace Tests\Functional\Provider;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Yoanm\JsonRpcServerDoc\Domain\Model\HttpServerDoc;
use Yoanm\JsonRpcServerDoc\Infra\Normalizer\HttpServerDocNormalizer;
use Yoanm\SymfonyJsonRpcHttpServerDoc\Creator\HttpServerDocCreator;
use Yoanm\SymfonyJsonRpcHttpServerDoc\Provider\RawDocProvider;

/**
 * @covers \Yoanm\SymfonyJsonRpcHttpServerDoc\Provider\RawDocProvider
 */
class RawDocProviderTest extends TestCase
{
    /** @var RawDocProvider */
    private $provider;

    /** @var HttpServerDocCreator|ObjectProphecy */
    private $httpServerDocCreator;
    /** @var HttpServerDocNormalizer|ObjectProphecy */
    private $serverDocNormalizer;

    protected function setUp()
    {
        $this->httpServerDocCreator = $this->prophesize(HttpServerDocCreator::class);
        $this->serverDocNormalizer = $this->prophesize(HttpServerDocNormalizer::class);

        $this->provider = new RawDocProvider(
            $this->httpServerDocCreator->reveal(),
            $this->serverDocNormalizer->reveal()
        );
    }

    public function testShouldSupportRawDotJsonFilename()
    {
        $this->assertTrue($this->provider->supports('raw.json'));
    }

    public function testShouldCreateDocAndNormalizeIt()
    {
        $host = 'my-host';
        $normalizedDoc = ['doc'];
        $doc = $this->prophesize(HttpServerDoc::class);

        $this->httpServerDocCreator->create($host)
            ->willReturn($doc->reveal())
            ->shouldBeCalled()
        ;
        $this->serverDocNormalizer->normalize($doc->reveal())
            ->willReturn($normalizedDoc)
            ->shouldBeCalled()
        ;

        $this->assertSame(
            $normalizedDoc,
            $this->provider->getDoc($host)
        );
    }
}
