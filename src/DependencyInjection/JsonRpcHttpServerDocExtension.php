<?php
namespace Yoanm\SymfonyJsonRpcHttpServerDoc\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class JsonRpcHttpServerDocExtension
 */
class JsonRpcHttpServerDocExtension implements ExtensionInterface, CompilerPassInterface
{
    /** Tags */
    /** Use this tag to inject your custom documentation creator */
    const DOC_PROVIDER_TAG = 'json_rpc_server_doc.doc_provider';

    // Extension identifier (used in configuration for instance)
    const EXTENSION_IDENTIFIER = 'json_rpc_http_server_doc';

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $this->compileAndProcessConfigurations($configs, $container);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $loader->load('services.sdk.yaml');
        $loader->load('services.private.yaml');
        $loader->load('services.public.yaml');
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $this->appendDocumentationProvider($container);
    }


    /**
     * {@inheritdoc}
     */
    public function getNamespace()
    {
        return 'http://example.org/schema/dic/'.$this->getAlias();
    }

    /**
     * {@inheritdoc}
     */
    public function getXsdValidationBasePath()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return self::EXTENSION_IDENTIFIER;
    }

    /**
     * @param ContainerBuilder $container
     */
    private function appendDocumentationProvider(ContainerBuilder $container)
    {
        $docProviderDefinition = $container->getDefinition('json_rpc_http_server_doc.provider.chain_provider');
        $docCreatorServiceList = $container->findTaggedServiceIds(self::DOC_PROVIDER_TAG);
        foreach (array_keys($docCreatorServiceList) as $serviceId) {
            $docProviderDefinition->addMethodCall('addProvider', [new Reference($serviceId)]);
        }
    }

    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    private function compileAndProcessConfigurations(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = (new Processor())->processConfiguration($configuration, $configs);

        $httpEndpointPath = $config['endpoint'];

        $container->setParameter(self::EXTENSION_IDENTIFIER.'.http_endpoint_path', $httpEndpointPath);
    }
}
