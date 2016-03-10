<?php

namespace AlexeyKuperhstokh\LocationBundle\Tests\DependencyInjection;

use AlexeyKuperhstokh\LocationBundle\DependencyInjection\LocationBundleExtension;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class LocationBundleExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultConfig()
    {
        $configs = array();

        $container = $this->getContainer($configs);

        // check location and request
        $this->assertInstanceOf('GuzzleHttp\Psr7\Uri', $container->get('location_bundle.uri'));
        $this->assertInstanceOf('GuzzleHttp\Psr7\Request', $container->get('location_bundle.request'));
        $this->assertInstanceOf('GuzzleHttp\Client', $container->get('location_bundle.http_client'));
        $this->assertInstanceOf(
            'AlexeyKuperhstokh\LocationBundle\Client\Client',
            $container->get('location_bundle.client')
        );
    }

    /**
     * @param array $config
     * @return ContainerBuilder
     */
    protected function getContainer(array $config = array())
    {
        $container = new ContainerBuilder();
        $container->getCompilerPassConfig()->setOptimizationPasses(array());
        $container->getCompilerPassConfig()->setRemovingPasses(array());
        $loader = new LocationBundleExtension();
        $loader->load($config, $container);
        $container->compile();
        return $container;
    }
}
