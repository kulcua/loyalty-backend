<?php

namespace Kulcua\Extension\Bundle\ChatBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Kulcua\Extension\Bundle\ChatBundle\DependencyInjection\ChatExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ChatBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass($this->buildMappingCompilerPass());
        $container->registerExtension(new ChatExtension());
    }

    /**
     * @return DoctrineOrmMappingsPass
     */
    public function buildMappingCompilerPass()
    {
        return DoctrineOrmMappingsPass::createYamlMappingDriver(
            [
                __DIR__.'/../../Component/Message/Infrastructure/Persistence/Doctrine/ORM' => 'Kulcua\Extension\Component\Message\Domain',
            ],
            [],
            false,
            ['Chat' => 'Kulcua\Extensions\Component\Message\Domain']
        );
    }
}
