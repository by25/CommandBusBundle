<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\CommandBusBundle\Tests\Handler;

use Itmedia\CommandBusBundle\Exception\HandlerNotFoundException;
use Itmedia\CommandBusBundle\Handler\ContainerCommandHandlerMapper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ContainerHandlerMapperTest extends TestCase
{


    /**
     * @dataProvider messages
     */
    public function testGet($messageName, $success)
    {
        $callableService = $this->getMockBuilder('CallableService')
            ->setMethods(['execute', 'getMethod']);


        $container = $this->createMock(ContainerInterface::class);
        $container->expects($this->any())
            ->method('get')
            ->will($this->returnValue($callableService));


        $mapper = new ContainerCommandHandlerMapper($container, [
            'message1' => [
                'service' => 'service1',
                'method' => 'getMethod'
            ],
            'message2' => [
                'service' => 'service1',
                'method' => null
            ],
            'message3' => [
                'service' => 'service1',
                'method' => 'hui'
            ],
        ]);


        if (!$success) {
            $this->expectException(HandlerNotFoundException::class);
        }

        $mapper->get($messageName);
    }


    public function messages(): array
    {
        return [
            ['message1', true],
            ['message2', true],
            ['message_fail', false],
        ];
    }
}
