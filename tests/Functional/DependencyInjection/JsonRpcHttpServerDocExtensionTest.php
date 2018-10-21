<?php
namespace Tests\Functional\DependencyInjection;

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\DependencyInjection\Reference;
use Tests\Common\DependencyInjection\AbstractTestClass;
use Tests\Common\DependencyInjection\ConcreteDocProvider;
use Tests\Common\DependencyInjection\ConcreteJsonRpcServerDispatcherAware;
use Tests\Common\Mock\ConcreteParamsValidator;
use Yoanm\JsonRpcServer\App\Dispatcher\JsonRpcServerDispatcherAwareTrait;
use Yoanm\SymfonyJsonRpcHttpServerDoc\DependencyInjection\JsonRpcHttpServerDocExtension;

/**
 * @covers \Yoanm\SymfonyJsonRpcHttpServerDoc\DependencyInjection\JsonRpcHttpServerDocExtension
 */
class JsonRpcHttpServerDocExtensionTest extends AbstractTestClass
{
    /**
     * {@inheritdoc}
     */
    protected function getContainerExtensions()
    {
        return [
            new JsonRpcHttpServerDocExtension()
        ];
    }


    public function testShouldBeLoadable()
    {
        $this->load();

        $this->assertEndpointIsUsable();
    }

    public function testShouldManageCustomEndpointPathFromConfiguration()
    {
        $myCustomEndpoint = 'my-custom-endpoint';
        $this->load(['endpoint' => $myCustomEndpoint]);

        // Assert custom resolver is an alias of the stub
        $this->assertContainerBuilderHasParameter(self::EXPECTED_HTTP_ENDPOINT_PATH_CONTAINER_PARAM, $myCustomEndpoint);

        $this->assertEndpointIsUsable();
    }

    public function testShouldReturnAnXsdValidationBasePath()
    {
        $this->assertNotNull((new JsonRpcHttpServerDocExtension())->getXsdValidationBasePath());
    }

    public function testShouldBindServerDispatcherToDispatcherAwareService()
    {
        $docProviderServiceId =  'my-doc-provider';
        $docProviderServiceDefinition = new Definition(ConcreteDocProvider::class);
        $docProviderServiceDefinition->addTag(self::EXPECTED_DOC_PROVIDER_TAG);

        $this->setDefinition($docProviderServiceId, $docProviderServiceDefinition);

        $this->load();

        // Assert custom resolver is an alias of the stub
        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            self::EXPECTED_CHAIN_PROVIDER_SERVICE_ID,
            'addProvider',
            [new Reference($docProviderServiceId)],
            0
        );

        $this->assertEndpointIsUsable();
    }
}
