<?php
namespace Zimtronic\BannerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{

   /**
    * {@inheritDoc}
    */
   public function getConfigTreeBuilder()
   {
      $treeBuilder = new TreeBuilder();
      $rootNode = $treeBuilder->root('zimtronic_banner');
      
      // Here you should define the parameters that are allowed to
      // configure your bundle. See the documentation linked above for
      // more information on that topic.
      
      $rootNode
			->children()
				->arrayNode("incident")
					->addDefaultsIfNotSet()
					->children()
						->scalarNode("lifetime")->defaultValue("600")->end()
					->end()
				->end()
				->arrayNode("banner")
					->addDefaultsIfNotSet()
					->children()
						->scalarNode("incidents_to_ban")->defaultValue("10")->end()
						->scalarNode("ban_time_frame")->defaultValue("600")->end()
						//->booleanNode("timewindow")->defaultValue()->end()
					->end()
				->end()
			->end();
      
      return $treeBuilder;
   }
}
