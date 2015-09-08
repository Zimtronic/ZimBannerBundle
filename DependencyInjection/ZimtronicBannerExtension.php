<?php

namespace Zimtronic\BannerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Zimtronic\BannerBundle\DependencyInjection\Configuration;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ZimtronicBannerExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);		
        
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        
        if (array_key_exists('incident', $config)) {
        	
           $container->setParameter('zimban.incident.lifetime', $config['incident']['lifetime']);
           
        }
        
        if (array_key_exists('banner', $config)) {
        	
           $container->setParameter('zimban.banner.incidents_to_ban', $config['banner']['incidents_to_ban']);
           $container->setParameter('zimban.banner.ban_time_frame', $config['banner']['ban_time_frame']);
           
        }
        
        if (array_key_exists('enabled', $config)) {
           
           if ($container->hasDefinition('zimban.listener.security_hook_listener')) {
              
           	  $definition = $container->getDefinition('zimban.listener.security_hook_listener');
              
              if (!$definition->hasTag('kernel.event_subscriber') && $config['enabled']) {
                 $definition->addTag('kernel.event_subscriber', array());
              }
              
              if (!($config['enabled'])) {
              	
                 $container->removeDefinition('zimban.listener.security_hook_listener');
              	
              }
              
           }
           
        }
        
    }
    
}
