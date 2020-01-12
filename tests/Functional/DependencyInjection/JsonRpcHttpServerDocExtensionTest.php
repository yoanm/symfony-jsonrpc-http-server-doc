<?php
namespace Tests\Functional\DependencyInjection;

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Tests\Common\DependencyInjection\AbstractTestClass;
use Tests\Common\DependencyInjection\ConcreteDocProvider;
use Yoanm\SymfonyJsonRpcHttpServerDoc\DependencyInjection\JsonRpcHttpServerDocExtension;

/**
 * @covers \Yoanm\SymfonyJsonRpcHttpServerDoc\DependencyInjection\JsonRpcHttpServerDocExtension
 */
class JsonRpcHttpServerDocExtensionTest extends AbstractTestClass
{
    public function testShouldBeLoadable()
    {
        $this->loadContainer();

        $this->assertEndpointIsUsable();
    }

    public function testShouldManageCustomEndpointPathFromConfiguration()
    {
        $myCustomEndpoint = 'my-custom-endpoint';
        $this->loadContainer(['endpoint' => $myCustomEndpoint]);

        // Assert custom resolver is an alias of the stub
        $this->assertContainerBuilderHasParameter(self::EXPECTED_HTTP_ENDPOINT_PATH_CONTAINER_PARAM, $myCustomEndpoint);

        $this->assertEndpointIsUsable();
    }

    public function testShouldReturnAnXsdValidationBasePath()
    {
        $this->assertNotNull((new JsonRpcHttpServerDocExtension())->getXsdValidationBasePath());
    }

    public function testShouldBindDocProviderToNormalizedDocFinder()
    {
        $docProviderServiceId =  'my-doc-provider';
        $docProviderServiceDefinition = new Definition(ConcreteDocProvider::class);
        $docProviderServiceDefinition->addTag(self::EXPECTED_DOC_PROVIDER_TAG);

        $this->setDefinition($docProviderServiceId, $docProviderServiceDefinition);

        $this->loadContainer();

        // Assert custom resolver is an alias of the stub
        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            self::EXPECTED_NORMALIZED_DOC_FINDER_SERVICE_ID,
            'addProvider',
            [new Reference($docProviderServiceId)],
            0
        );

        $this->assertEndpointIsUsable();
    }
}
