Feature: demo symfony application

  Scenario: Check that all methods are available
    # Ensure methods with tag have been succesfully loaded
    When I send a "GET" request on "/my-custom-doc-endpoint" demoApp kernel endpoint
    Then I should have a "200" response from demoApp with following content:
    """
    {
      "methods": [
        {
          "identifier": "BundledMethodA",
          "name": "bundledMethodA"
        },
        {
          "identifier": "BundledMethodAAlias",
          "name": "bundledMethodAAlias"
        },
        {
          "identifier": "BundledMethodB",
          "name": "bundledMethodB"
        },
        {
          "identifier": "BundledGetDummy",
          "name": "bundledGetDummy"
        },
        {
          "identifier": "BundledGetAnotherDummy",
          "name": "bundledGetAnotherDummy"
        }
      ],
      "errors": [
        {
          "id": "ParseError-32700",
          "title": "Parse error",
          "type": "object",
          "properties": {
            "code": -32700
          }
        },
        {
          "id": "InvalidRequest-32600",
          "title": "Invalid request",
          "type": "object",
          "properties": {
            "code": -32600
          }
        },
        {
          "id": "MethodNotFound-32601",
          "title": "Method not found",
          "type": "object",
          "properties": {
            "code": -32601
          }
        },
        {
          "id": "ParamsValidationsError-32602",
          "title": "Params validations error",
          "type": "object",
          "properties": {
            "code": -32602,
            "data": {
              "type": "object",
              "nullable": true,
              "required": true,
              "siblings": {
                "violations": {
                  "type": "array",
                  "nullable": true,
                  "required": false
                }
              }
            }
          }
        },
        {
          "id": "InternalError-32603",
          "title": "Internal error",
          "type": "object",
          "properties": {
            "code": -32603,
            "data": {
              "type": "object",
              "nullable": true,
              "required": false,
              "siblings": {
                "previous": {
                  "type": "string",
                  "nullable": true,
                  "required": false,
                  "description": "Previous error message"
                }
              }
            }
          }
        }
      ],
      "http": {
        "host": "localhost"
      }
    }
    """

  Scenario: Check method B doc can be enhanced thanks to MethodDocCreatedEvent event
    Given I will use kernel with MethodDocCreated listener
    When I send a "GET" request on "/my-custom-doc-endpoint" demoApp kernel endpoint
    Then I should have a "200" response from demoApp with following content:
    """
    {
      "methods": [
        {
          "identifier": "BundledMethodA",
          "name": "bundledMethodA"
        },
        {
          "identifier": "BundledMethodB",
          "name": "bundledMethodB",
          "result": {
            "description": "method a dataResult description",
            "type": "array",
            "nullable": true,
            "required": false
          }
        }
      ],
      "errors": [
        {
          "id": "ParseError-32700",
          "title": "Parse error",
          "type": "object",
          "properties": {
            "code": -32700
          }
        },
        {
          "id": "InvalidRequest-32600",
          "title": "Invalid request",
          "type": "object",
          "properties": {
            "code": -32600
          }
        },
        {
          "id": "MethodNotFound-32601",
          "title": "Method not found",
          "type": "object",
          "properties": {
            "code": -32601
          }
        },
        {
          "id": "ParamsValidationsError-32602",
          "title": "Params validations error",
          "type": "object",
          "properties": {
            "code": -32602,
            "data": {
              "type": "object",
              "nullable": true,
              "required": true,
              "siblings": {
                "violations": {
                  "type": "array",
                  "nullable": true,
                  "required": false
                }
              }
            }
          }
        },
        {
          "id": "InternalError-32603",
          "title": "Internal error",
          "type": "object",
          "properties": {
            "code": -32603,
            "data": {
              "type": "object",
              "nullable": true,
              "required": false,
              "siblings": {
                "previous": {
                  "type": "string",
                  "nullable": true,
                  "required": false,
                  "description": "Previous error message"
                }
              }
            }
          }
        }
      ],
      "http": {
        "host": "localhost"
      }
    }
    """

  Scenario: Check server name doc can be defined thanks to ServerDocCreatedEvent event
    Given I will use kernel with ServerDocCreated listener
    When I send a "GET" request on "/my-custom-doc-endpoint" demoApp kernel endpoint
    Then I should have a "200" response from demoApp with following content:
    """
    {
      "methods": [
        {
          "identifier": "BundledMethodA",
          "name": "bundledMethodA"
        }
      ],
      "errors": [
        {
          "id": "ParseError-32700",
          "title": "Parse error",
          "type": "object",
          "properties": {
            "code": -32700
          }
        },
        {
          "id": "InvalidRequest-32600",
          "title": "Invalid request",
          "type": "object",
          "properties": {
            "code": -32600
          }
        },
        {
          "id": "MethodNotFound-32601",
          "title": "Method not found",
          "type": "object",
          "properties": {
            "code": -32601
          }
        },
        {
          "id": "ParamsValidationsError-32602",
          "title": "Params validations error",
          "type": "object",
          "properties": {
            "code": -32602,
            "data": {
              "type": "object",
              "nullable": true,
              "required": true,
              "siblings": {
                "violations": {
                  "type": "array",
                  "nullable": true,
                  "required": false
                }
              }
            }
          }
        },
        {
          "id": "InternalError-32603",
          "title": "Internal error",
          "type": "object",
          "properties": {
            "code": -32603,
            "data": {
              "type": "object",
              "nullable": true,
              "required": false,
              "siblings": {
                "previous": {
                  "type": "string",
                  "nullable": true,
                  "required": false,
                  "description": "Previous error message"
                }
              }
            }
          }
        }
      ],
      "http": {
        "host": "localhost"
      },
      "info": {
        "name": "my custom server doc description"
      }
    }
    """
