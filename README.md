CommandBusBundle
==========

@todo Документация


Install
-------

```
composer require itmedia/command-bus-bundle 
```


CommandBus 
-----------

Пример создания ниже, также смотри добавление поведения (Middleware):

```yml
services:

    app.command_bus:
        class: Itmedia\CommandBusBundle\Command\CommandBus
        arguments: ["@infrastructure_command_bus.handler_mapper"]
```


Middleware
----------

Middleware реализуют дополнительную обработку сообщений, например: валидацию, проверку прав доступа, логирование.
Middleware должны реализовывать интерфейс `MiddlewareInterface`. В CommandBus при выполнении сообщения
происходит его обработка подключенными Middleware. При не выполнении правил, должно всегда выбрасываться  
исключение.

Пример конфигурации:

```yml

    app.command_bus:
        class: Itmedia\CommandBusBundle\CommandBus\CommandBus
        arguments: ["@infrastructure_command_bus.handler_mapper"]
        calls:
            - [addMiddleware, ["@infrastructure_command_bus.middleware_validation"]]
            - [addMiddleware, ["@app.middleware_access_control"]]


    app.middleware_access_control:
        class: Itmedia\CommandBusBundle\Middleware\AccessControlMiddleware

```



---



Command
-------

Команда должна иметь интерфейс `CommandInterface`.
 
Handlers могут иметь произвольную структуру, если используется, либо для единичного handler - `CommandHandlerInterface`

Пример конфигурации:

```yml
services:
    # по умолчанию будет вызван метод execute()
    handler1:
        class: Itmedia\CommandBusBundle\Test\HandlerTest
        tags:
            - {name: command_handler, command: core_register_user } 


    # явное указание методов
    handler:
        class: Itmedia\CommandBusBundle\Test\HandlerTest
        tags:
            - {name: command_handler, command: core_register_user1, method: methodName1 }
            - {name: command_handler, command: core_register_user2, method: methodName2 }
            - {name: command_handler, command: core_register_user3, method: methodName3 }
```

Пример использования:

```
    $command = new CommandTest();
    $this->get('command_bus')->handle($command);
```




