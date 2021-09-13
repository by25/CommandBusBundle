CommandBusBundle
================

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/by25/CommandBusBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/by25/CommandBusBundle/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/by25/CommandBusBundle/badges/build.png?b=master)](https://scrutinizer-ci.com/g/by25/CommandBusBundle/build-status/master)


Install
-------

```bash
composer require itmedia/command-bus-bundle 
```


CommandBus 
-----------

Пример регистрации сервиса, также смотри ниже добавление встроеных обработчиков комманд (Middleware):

```yaml
services:

    app.command_bus:
        class: Itmedia\CommandBusBundle\CommandBus\CommandBus
        arguments: ["@itmedia_command_bus.container_handler_mapper"]
```


Middleware
----------

Middleware реализуют дополнительную обработку сообщений, например: валидацию, проверку прав доступа, логирование.
Middleware должны реализовывать интерфейс `MiddlewareInterface`. В CommandBus при выполнении сообщения
происходит его обработка подключенными Middleware. При не выполнении правил, должно всегда выбрасываться исключение.

Пример конфигурации:

```yaml
services:
    app.command_bus:
        class: Itmedia\CommandBusBundle\CommandBus\CommandBus
        arguments: ["@itmedia_command_bus.container_handler_mapper"]
        calls:
            - [addMiddleware, ["@itmedia_command_bus.middleware_validation"]]
            - [addMiddleware, ["@app.middleware_access_control"]] 

    # custom middleware
    app.middleware_access_control:
        class: AppBundle\Middleware\AccessControlMiddleware
```

---


Command
-------

Команда должна иметь интерфейс `Command`.

```php
use Itmedia\CommandBusBundle\Command\Command;

class TestCommand implements Command
{
    //...

    public function commandName()
    {
        return 'test_command';
    }

}
```
 
Handlers могут иметь произвольную структуру, если используется, либо для единичного handler - `CommandHandler`

Пример конфигурации `CommandHandler`:

```yaml
services:
    # по умолчанию будет вызван метод execute()
    AppBundle\Handler\MyHandler:
        public: true
        tags:
            - {name: command_handler, command: core_register_user } 


    # явное указание методов
    AppBundle\Handler\NyHandler2:
        public: true
        tags:
            - {name: command_handler, command: core_register_user1, method: methodName1 }
            - {name: command_handler, command: core_register_user2, method: methodName2 }
            - {name: command_handler, command: core_register_user3, method: methodName3 }
```

Пример использования:

```php
    $command = new CommandTest();
    $this->get('app.command_bus')->handle($command);
```



Валидация команд 
----------------

Для валидации команд средствами `symfony/validator` необходимо подключить `ValidationMiddleware` для `CommandBus`:


```yaml
services:
    Itmedia\CommandBusBundle\CommandBus\CommandBus:
        arguments: ["@itmedia_command_bus.container_handler_mapper"]
        calls:
            - [addMiddleware, ["@itmedia_command_bus.middleware_validation"]]
```

Пример правил валидации команды:

```php
use Itmedia\CommandBusBundle\Command\Command;
use Symfony\Component\Validator\Constraints as Assert;

class TestCommand implements Command
{

    /**
     * @var string
     * @NotBlank()
     */
    private $username;

    /**
     * @var string
     * @NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * TestCommand constructor.
     * @param string $username
     * @param string $email
     */
    public function __construct($username, $email)
    {
        $this->username = $username;
        $this->email = $email;
    }

    public function commandName()
    {
        return 'test_command';
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

}
```

Если комманда не проходит валидацию выбрасывается исключение `ValidationException`


Установка значений свойств комманды из массива 
----------------------------------------------

`HandlePropertiesFormArrayTrait` - вспомогательный трейт для устаовки значений по ключу из массива
в свойства команды. Название ключа должно соответсвовать названию свойства.


```php
use Itmedia\CommandBusBundle\Command\Command;
use Itmedia\CommandBusBundle\Command\HandlePropertiesFormArrayTrait;

class TestCommand implements Command
{

    use HandlePropertiesFormArrayTrait;
    
    // ....
  
    public function __construct($id, array $data)
    {
        $this->handleProperties($data);
        $this->id = $id;
    }

}    
```