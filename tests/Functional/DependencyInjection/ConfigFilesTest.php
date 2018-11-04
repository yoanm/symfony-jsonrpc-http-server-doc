<?php
namespace Tests\Functional\DependencyInjection;

use Tests\Common\DependencyInjection\AbstractTestClass;
use Yoanm\JsonRpcServerDoc\Infra\Normalizer\ErrorDocNormalizer;
use Yoanm\JsonRpcServerDoc\Infra\Normalizer\HttpServerDocNormalizer;
use Yoanm\JsonRpcServerDoc\Infra\Normalizer\MethodDocNormalizer;
use Yoanm\JsonRpcServerDoc\Infra\Normalizer\ServerDocNormalizer;
use Yoanm\JsonRpcServerDoc\Infra\Normalizer\TagDocNormalizer;
use Yoanm\JsonRpcServerDoc\Infra\Normalizer\TypeDocNormalizer;
use Yoanm\SymfonyJsonRpcHttpServer\DependencyInjection\JsonRpcHttpServerExtension;
use Yoanm\SymfonyJsonRpcHttpServerDoc\Creator\HttpServerDocCreator;
use Yoanm\SymfonyJsonRpcHttpServerDoc\Endpoint\DocumentationEndpoint;
use Yoanm\SymfonyJsonRpcHttpServerDoc\Finder\NormalizedDocFinder;
use Yoanm\SymfonyJsonRpcHttpServerDoc\Provider\RawDocProvider;

/**
 * /!\ This test class does not cover JsonRpcHttpServerDocExtension, it covers yaml configuration files
 * => So no [at]covers tag !
 */
class ConfigFilesTest extends AbstractTestClass
{
    /**
     * @dataProvider provideSDKInfraServiceIdAndClass
     * @dataProvider provideBundlePublicServiceIdAndClass
     * @dataProvider provideBundlePrivateServiceIdAndClass
     *
     * @param string $serviceId
     * @param string $expectedClassName
     * @param bool   $public
     */
    public function testShouldHaveService($serviceId, $expectedClassName, $public)
    {
        $this->load(['endpoint' => '/endpoint'], true, false);

        $this->assertContainerBuilderHasService($serviceId, $expectedClassName);
        if (true === $public) {
            // Check that service is accessible through the container
            $this->assertNotNull($this->container->get($serviceId));
        }
    }

    public function testHttpServerDocCreatorShouldHaveMethodsMappingAwareTag()
    {
        $serviceId = 'json_rpc_http_server_doc.creator.http_server';

        $this->load();

        // From yoanm/symfony-jsonrpc-http-server
        $this->assertContainerBuilderHasServiceDefinitionWithTag(
            $serviceId,
            JsonRpcHttpServerExtension::JSONRPC_METHOD_AWARE_TAG
        );
    }

    /**
     * @return array
     */
    public function provideSDKInfraServiceIdAndClass()
    {
        return [
            'SDK - Infra - TypeDocNormalizer' => [
                'serviceId' => 'json_rpc_server_doc_sdk.normalizer.type',
                'serviceClassName' => TypeDocNormalizer::class,
                'public' => true,
            ],
            'SDK - Infra - ErrorDocNormalizer' => [
                'serviceId' => 'json_rpc_server_doc_sdk.normalizer.error',
                'serviceClassName' => ErrorDocNormalizer::class,
                'public' => true,
            ],
            'SDK - Infra - MethodDocNormalizer' => [
                'serviceId' => 'json_rpc_server_doc_sdk.normalizer.method',
                'serviceClassName' => MethodDocNormalizer::class,
                'public' => true,
            ],
            'SDK - Infra - TagDocNormalizer' => [
                'serviceId' => 'json_rpc_server_doc_sdk.normalizer.tag',
                'serviceClassName' => TagDocNormalizer::class,
                'public' => true,
            ],
            'SDK - Infra - ServerDocNormalizer' => [
                'serviceId' => 'json_rpc_server_doc_sdk.normalizer.server',
                'serviceClassName' => ServerDocNormalizer::class,
                'public' => true,
            ],
            'SDK - Infra - Endpoint' => [
                'serviceId' => 'json_rpc_server_doc_sdk.normalizer.http_server',
                'serviceClassName' => HttpServerDocNormalizer::class,
                'public' => true,
            ],
        ];
    }

    /**
     * @return array
     */
    public function provideBundlePublicServiceIdAndClass()
    {
        return [
            'Bundle - Public - HTTP doc endpoint' => [
                'serviceId' => 'json_rpc_http_server_doc.endpoint',
                'serviceClassName' => DocumentationEndpoint::class,
                'public' => true,
            ],
            'Bundle - Public - HttpServerDocCreator' => [
                'serviceId' => 'json_rpc_http_server_doc.creator.http_server',
                'serviceClassName' => HttpServerDocCreator::class,
                'public' => true,
            ],
            'Bundle - Public - RawDocProvider' => [
                'serviceId' => 'json_rpc_http_server_doc.provider',
                'serviceClassName' => RawDocProvider::class,
                'public' => true,
            ],
        ];
    }

    /**
     * @return array
     */
    public function provideBundlePrivateServiceIdAndClass()
    {
        return [
            'Bundle - Private - NormalizedDocFinder' => [
                'serviceId' => 'json_rpc_http_server_doc.finder.normalized_doc',
                'serviceClassName' => NormalizedDocFinder::class,
                'public' => false,
            ],
        ];
    }
}
