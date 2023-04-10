<?php
namespace DemoApp;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Routing\RouteCollectionBuilder;

class KernelWithServerDocCreatedListener extends AbstractKernel
{
    public function getConfigDirectoryName() : string
    {
        return 'config_with_server_doc_created_listener';
    }
}
