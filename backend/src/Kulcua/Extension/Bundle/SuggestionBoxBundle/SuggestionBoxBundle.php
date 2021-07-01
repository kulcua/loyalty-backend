<?php

namespace Kulcua\Extension\Bundle\SuggestionBoxBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Kulcua\Extension\Bundle\SuggestionBoxBundle\DependencyInjection\SuggestionBoxExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SuggestionBoxBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass($this->buildMappingCompilerPass());
        $container->registerExtension(new SuggestionBoxExtension());
    }

    /**
     * @return DoctrineOrmMappingsPass
     */
    public function buildMappingCompilerPass()
    {
        return DoctrineOrmMappingsPass::createYamlMappingDriver(
            [
                __DIR__.'/../../Component/SuggestionBox/Infrastructure/Persistence/Doctrine/ORM' => 'Kulcua\Extension\Component\SuggestionBox\Domain',
            ],
            [],
            false,
            ['SuggestionBox' => 'Kulcua\Extensions\Component\SuggestionBox\Domain']
        );
    }
}
