CommandBusBundle
==========

@todo Документация

CommandBus 
-----------

Пример создания ниже, также смотри добавление поведения (Middleware):

```yml
services:

    simple_command_bus:
        class: Infrastructure\CommandBusBundle\Command\CommandBus
        arguments: ["@command_handler_mapper"]

```


Middleware
----------

Middleware реализуют дополнительную обработку сообщений, например: валидацию, проверку прав доступа, логирование.
Middleware должны реализовывать интерфейс `MiddlewareInterface`. В CommandBus при выполнении сообщения
происходит его обработка подключенными Middleware. При не выполнении правил, должно всегда выбрасываться  
исключение.

Пример конфигурации:

```yml

    app.query_bus:
        class: Infrastructure\CommandBusBundle\Command\CommandBus
        arguments: ["@command_handler_mapper"]
        calls:
            - [addMiddleware, ["@core.middleware_validation"]]
            - [addMiddleware, ["@app.middleware_access_control"]]


    app.middleware_access_control:
        class: Infrastructure\CommandBusBundle\Middleware\AccessControlMiddleware

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
        class: Infrastructure\CommandBusBundle\Test\HandlerTest
        tags:
            - {name: command_handler, command: core_register_user } 


    # явное указание методов
    handler:
        class: Infrastructure\CommandBusBundle\Test\HandlerTest
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




