<?php
namespace Tests\Functional\Endpoint;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Yoanm\SymfonyJsonRpcHttpServerDoc\Endpoint\DocumentationEndpoint;
use Yoanm\SymfonyJsonRpcHttpServerDoc\Finder\NormalizedDocFinder;
use Yoanm\SymfonyJsonRpcHttpServerDoc\Provider\RawDocProvider;

/**
 * @covers \Yoanm\SymfonyJsonRpcHttpServerDoc\Endpoint\DocumentationEndpoint
 */
class DocumentationEndpointTest extends TestCase
{
    use ProphecyTrait;

    /** @var DocumentationEndpoint */
    private $endpoint;

    /** @var NormalizedDocFinder|ObjectProphecy */
    private $normalizedDocFinder;

    protected function setUp(): void
    {
        $this->normalizedDocFinder = $this->prophesize(NormalizedDocFinder::class);

        $this->endpoint = new DocumentationEndpoint(
            $this->normalizedDocFinder->reveal()
        );
    }

    public function testHttPostShouldHandleRequestContentAndReturnA200ResponseContainingEncodedDoc()
    {
        $filename = 'test.raw.json';
        $host = 'host';
        $doc = ['doc'];
        $expectedResponseContent = json_encode($doc);

        /** @var Request|ObjectProphecy $request */
        $request = $this->prophesize(Request::class);

        $request->get('filename')
            ->willReturn($filename)
            ->shouldBeCalled();
        $request->getHttpHost()
            ->willReturn($host)
            ->shouldBeCalled()
        ;

        $this->normalizedDocFinder->findFor($filename, $host)
            ->willReturn($doc)
            ->shouldBeCalled();

        $response = $this->endpoint->httpGet($request->reveal());

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSame($expectedResponseContent, $response->getContent());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
    }

    public function testHttPostShouldHaveUseRawDocIfNoFilenameProvided()
    {
        $defaultRawDocFilename = RawDocProvider::SUPPORTED_FILENAME;
        $host = 'host';
        $doc = ['doc'];
        $expectedResponseContent = json_encode($doc);

        /** @var Request|ObjectProphecy $request */
        $request = $this->prophesize(Request::class);

        $request->get('filename')
            ->willReturn(null)
            ->shouldBeCalled();
        $request->getHttpHost()
            ->willReturn($host)
            ->shouldBeCalled()
        ;

        $this->normalizedDocFinder->findFor($defaultRawDocFilename, $host)
            ->willReturn($doc)
            ->shouldBeCalled();

        $response = $this->endpoint->httpGet($request->reveal());

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSame($expectedResponseContent, $response->getContent());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));
    }

    public function testHttOptionsShouldReturnAllowedMethodsAndContentType()
    {
        $expectedAllowedMethodList = implode(', ', [Request::METHOD_GET, Request::METHOD_OPTIONS]);

        $response = $this->endpoint->httpOptions();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());
        $this->assertSame('application/json', $response->headers->get('Content-Type'));

        // Check allowed methods
        $this->assertSame($expectedAllowedMethodList, $response->headers->get('Allow', null, false));
        $this->assertSame(
            $expectedAllowedMethodList,
            $response->headers->get('Access-Control-Request-Method', null, false)
        );

        // Check allowed content types
        $this->assertSame('application/json', $response->headers->get('Accept'));
        $this->assertSame('Content-Type', $response->headers->get('Access-Control-Allow-Headers'));
    }
}
