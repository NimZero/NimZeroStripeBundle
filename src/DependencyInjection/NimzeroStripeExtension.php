<?php

/*
 * This file is part of the Stripe Bundle.
 * 
 * (c) TESTA 'NimZero' Charly <contact@nimzero.fr>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nimzero\StripeBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class NimzeroStripeExtension extends Extension
{
    /**
     * @param array<mixed> $configs
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        // Load the config files
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../../config')
        );

        // Apply the services
        $loader->load('services.yaml');
        
        // Load the configuration
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // Process the config to container parameters
        $container->setParameter('nimzero.stripe_bundle.stripe.api_version', $config['stripe']['api_version']);
        $container->setParameter('nimzero.stripe_bundle.stripe.api_secret_key', $config['stripe']['api_secret_key']);
        $container->setParameter('nimzero.stripe_bundle.stripe.api_public_key', $config['stripe']['api_public_key']);
        $container->setParameter('nimzero.stripe_bundle.stripe.webhook_secret', $config['stripe']['webhook_secret']);
        $container->setParameter('nimzero.stripe_bundle.stripe.tolerance', $config['stripe']['tolerance']);

        // Configure services with their parameters        
        $stripe = $container->getDefinition('nimzero.stripe_bundle.service.stripe');
        $stripe->replaceArgument('$stripe_config', $config['stripe']);
    }
}