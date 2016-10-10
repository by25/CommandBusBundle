<?php
/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Infrastructure\CommandBusBundle\Tests\Handler;


use Infrastructure\CommandBusBundle\Exception\HandlerNotFoundException;
use Infrastructure\CommandBusBundle\Handler\HandlerMapper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class HandlerMapperTest extends TestCase
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


        $mapper = new HandlerMapper($container, [
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


    public function messages()
    {
        return [
            ['message1', true],
            ['message2', true],
            ['message_fail', false],
        ];
    }


}