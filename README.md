# Symfony JSON-RPC server documentation
[![License](https://img.shields.io/github/license/yoanm/symfony-jsonrpc-http-server-doc.svg)](https://github.com/yoanm/symfony-jsonrpc-http-server-doc) [![Code size](https://img.shields.io/github/languages/code-size/yoanm/symfony-jsonrpc-http-server-doc.svg)](https://github.com/yoanm/symfony-jsonrpc-http-server-doc) [![Dependabot Status](https://api.dependabot.com/badges/status?host=github&repo=yoanm/symfony-jsonrpc-http-server-doc)](https://dependabot.com)

[![Scrutinizer Build Status](https://img.shields.io/scrutinizer/build/g/yoanm/symfony-jsonrpc-http-server-doc.svg?label=Scrutinizer&logo=scrutinizer)](https://scrutinizer-ci.com/g/yoanm/symfony-jsonrpc-http-server-doc/build-status/master) [![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/yoanm/symfony-jsonrpc-http-server-doc/master.svg?logo=scrutinizer)](https://scrutinizer-ci.com/g/yoanm/symfony-jsonrpc-http-server-doc/?branch=master) [![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/yoanm/symfony-jsonrpc-http-server-doc/master.svg?logo=scrutinizer)](https://scrutinizer-ci.com/g/yoanm/symfony-jsonrpc-http-server-doc/?branch=master)

[![Travis Build Status](https://img.shields.io/travis/com/yoanm/symfony-jsonrpc-http-server-doc/master.svg?label=Travis&logo=travis)](https://travis-ci.com/yoanm/symfony-jsonrpc-http-server-doc) [![Travis PHP versions](https://img.shields.io/travis/com/php-v/yoanm/symfony-jsonrpc-http-server-doc.svg?logo=travis)](https://php.net/) [![Travis Symfony Versions](https://img.shields.io/badge/Symfony-v3%20%2F%20v4-8892BF.svg?logo=travis)](https://symfony.com/)

[![Latest Stable Version](https://img.shields.io/packagist/v/yoanm/symfony-jsonrpc-http-server-doc.svg)](https://packagist.org/packages/yoanm/symfony-jsonrpc-http-server-doc) [![Packagist PHP version](https://img.shields.io/packagist/php-v/yoanm/symfony-jsonrpc-http-server-doc.svg)](https://packagist.org/packages/yoanm/symfony-jsonrpc-http-server-doc)

Symfony bundle for easy JSON-RPC server documentation

Symfony bundle for [`yoanm/jsonrpc-server-doc-sdk`](https://raw.githubusercontent.com/yoanm/php-jsonrpc-server-doc-sdk)

See [yoanm/symfony-jsonrpc-params-sf-constraints-doc](https://github.com/yoanm/symfony-jsonrpc-params-sf-constraints-doc) for params documentation generation.

## Availble formats

 - Raw : Built-in `json` format at `/doc` or `/doc/raw.json`
 - Swagger : [yoanm/symfony-jsonrpc-http-server-swagger-doc](https://github.com/yoanm/symfony-jsonrpc-http-server-swagger-doc)
 - OpenApi : [yoanm/symfony-jsonrpc-http-server-openapi-doc](https://github.com/yoanm/symfony-jsonrpc-http-server-openapi-doc)

## How to use

Once configured, your project is ready to handle HTTP `GET` request on `/doc/{?filename}` endpoint.

See below how to configure it.

## Configuration

*[Behat demo app configuration folders](./features/demo_app/) can be used as examples.*

 - Add the bundles in your `config/bundles.php` file:
   ```php
   // config/bundles.php
   return [
       ...
       Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
       Yoanm\SymfonyJsonRpcHttpServer\JsonRpcHttpServerBundle::class => ['all' => true],
       Yoanm\SymfonyJsonRpcHttpServerDoc\JsonRpcHttpServerDocBundle::class => ['all' => true],
       ...
   ];
   ```
   
 - Add the following in your routing configuration :
   ```yaml
   # config/routes.yaml
   json-rpc-endpoint:
     resource: '@JsonRpcHttpServerBundle/Resources/config/routing/endpoint.xml'
   
   json-rpc-endpoint-doc:
     resource: '@JsonRpcHttpServerDocBundle/Resources/config/routing/endpoint.xml'
   ```
   
 - Add the following in your configuration :
   ```yaml
   # config/config.yaml
   framework:
     secret: '%env(APP_SECRET)%'

   json_rpc_http_server: ~
   
   json_rpc_http_server_doc: ~
   # Or the following in case you want to customize endpoint path
   #json_rpc_http_server_doc:
   #  endpoint: '/my-custom-doc-endpoint' # Default to '/doc'
   ```
   
 - Register JSON-RPC methods as described on [yoanm/symfony-jsonrpc-http-server](https://github.com/yoanm/symfony-jsonrpc-http-server) documentation.
 
 - Query your project at `/doc` endpoint and you will have a `json` documentation of your server.

## Contributing
See [contributing note](./CONTRIBUTING.md)
