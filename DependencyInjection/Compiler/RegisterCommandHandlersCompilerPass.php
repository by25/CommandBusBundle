<?php

/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Itmedia\CommandBusBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterCommandHandlersCompilerPass implements CompilerPassInterface
{
    private const PARAMETER_NAME = 'itmedia_command_bus.commands';
    private string $serviceId;
    private string $tag;
    private string $keyAttribute;

    public function __construct(string $serviceId, string $tag, string $keyAttribute)
    {
        $this->serviceId = $serviceId;
        $this->tag = $tag;
        $this->keyAttribute = $keyAttribute;
    }


    public function process(ContainerBuilder $container)
    {
        if (!$container->has($this->serviceId)) {
            return;
        }

        $definition = $container->findDefinition($this->serviceId);

        $handlers = [];

        foreach ($container->findTaggedServiceIds($this->tag) as $serviceId => $tags) {

            /**
             * @var array $tags
             */
            foreach ($tags as $tagAttributes) {
                if (!array_key_exists($this->keyAttribute, $tagAttributes)) {
                    throw new \InvalidArgumentException(
                        sprintf(
                            'The attribute "%s" of tag "%s" of service "%s" is required',
                            $this->keyAttribute,
                            $this->tag,
                            $serviceId
                        )
                    );
                }

                $callable = [
                    'service' => null,
                    'method' => null
                ];

                if (array_key_exists('method', $tagAttributes)) {
                    $callable['method'] = $tagAttributes['method'];
                }
                $callable['service'] = $serviceId;


                $key = $tagAttributes[$this->keyAttribute];
                $key = ltrim($key, '\\');

                if (array_key_exists($key, $handlers)) {
                    throw new \InvalidArgumentException(
                        sprintf('Handler for command "%s" already exists', $key)
                    );
                }

                $handlers[$key] = $callable;
            }
        }

        $definition->replaceArgument(1, $handlers);

        $messages = array_keys($handlers);
        if ($container->hasParameter(self::PARAMETER_NAME)) {
            $messages = array_merge($container->getParameter(self::PARAMETER_NAME), $messages);
        }

        $container->setParameter(self::PARAMETER_NAME, $messages);
    }
}
