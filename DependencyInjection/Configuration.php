<?php

namespace Ibtikar\ShareEconomyPayFortBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ibtikar_share_economy_pay_fort');

        $rootNode->children()
                    ->scalarNode("environment")
                        ->defaultValue("sandbox")
                        ->validate()
                            ->ifNotInArray(array("prod", "sandbox"))
                            ->thenInvalid("Enviroment must be either 'sandbox' or 'prod'")
                        ->end()
                    ->end()
                    ->arrayNode("sandbox")
                        ->children()
                            ->scalarNode('merchantIdentifier')
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('accessCode')
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('shaType')
                                ->cannotBeEmpty()
                                ->validate()
                                    ->ifNotInArray(array("sha128", "sha256", "sha512"))
                                    ->thenInvalid("SHA type must be one of the following values (sha128, sha256, sha512)")
                                ->end()
                            ->end()
                            ->scalarNode('shaRequestPhrase')
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('shaResponsePhrase')
                                ->cannotBeEmpty()
                            ->end()
                        ->end()
                    ->end()
                    ->arrayNode("production")
                        ->children()
                            ->scalarNode('merchantIdentifier')
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('accessCode')
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('shaType')
                                ->cannotBeEmpty()
                                ->validate()
                                    ->ifNotInArray(array("sha128", "sha256", "sha512"))
                                    ->thenInvalid("SHA type must be one of the following values (sha128, sha256, sha512)")
                                ->end()
                            ->end()
                            ->scalarNode('shaRequestPhrase')
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('shaResponsePhrase')
                                ->cannotBeEmpty()
                            ->end()
                        ->end()
                    ->end()
                ->end();

        return $treeBuilder;
    }
}
