<?php

/**
 * (c) itmedia.by <info@itmedia.by>
 */

namespace Infrastructure\CommandBusBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class RegisterCommandHandlersCompilerPass implements CompilerPassInterface
{


    private $serviceId;

    private $tag;

    private $keyAttribute;

    /**
     * RegisterHandlers constructor.
     *
     * @param $serviceId
     * @param $tag
     * @param $keyAttribute
     */
    public function __construct($serviceId, $tag, $keyAttribute)
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
                    $callable['service'] = $serviceId;
                    $callable['method'] = $tagAttributes['method'];
                } else {
                    $callable['service'] = $serviceId;
                }

                $key = $tagAttributes[$this->keyAttribute];
                $key = ltrim($key, '\\');

                if (array_key_exists($key, $handlers)) {
                    throw new \InvalidArgumentException(
                        sprintf(
                            'Для обработчика команды "%s" уже создан обработчик',
                            $key
                        )
                    );
                }

                $handlers[$key] = $callable;
            }
        }

        $definition->replaceArgument(1, $handlers);

        $parameterName = 'infrastructure_command_bus.messages';

        $messages = array_keys($handlers);
        if ($container->hasParameter($parameterName)) {
            $messages = array_merge($container->getParameter($parameterName), $messages);
        }

        $container->setParameter($parameterName, $messages);
    }


}