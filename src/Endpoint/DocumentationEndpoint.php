<?php
namespace Yoanm\SymfonyJsonRpcHttpServerDoc\Endpoint;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Yoanm\SymfonyJsonRpcHttpServerDoc\Finder\NormalizedDocFinder;
use Yoanm\SymfonyJsonRpcHttpServerDoc\Provider\RawDocProvider;

/**
 * Class DocumentationEndpoint
 */
class DocumentationEndpoint
{
    /** @var NormalizedDocFinder */
    private $normalizedDocFinder;

    /** @var string[] */
    private $allowedMethodList = [];

    /**
     * @param NormalizedDocFinder $normalizedDocFinder
     */
    public function __construct(NormalizedDocFinder $normalizedDocFinder)
    {
        $this->normalizedDocFinder = $normalizedDocFinder;
        $this->allowedMethodList = [Request::METHOD_GET, Request::METHOD_OPTIONS];
    }

    /**
     * @return Response
     */
    public function httpOptions() : Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        // Set allowed http methods
        $response->headers->set('Allow', $this->allowedMethodList);
        $response->headers->set('Access-Control-Request-Method', $this->allowedMethodList);

        // Set allowed content type
        $response->headers->set('Accept', 'application/json');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type');

        return $response;
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function httpGet(Request $request) : Response
    {
        // Use Raw doc by default if not provided
        $filename = $request->get('filename') ?? RawDocProvider::SUPPORTED_FILENAME;
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        $doc = $this->normalizedDocFinder->findFor($filename, $request->getHttpHost());

        $response->setContent(json_encode($doc));

        return $response;
    }
}
