<?php
/**
 * @package: chapi
 *
 * @author:  msiebeneicher
 * @since:   2017-03-23
 *
 */

namespace unit\Component\Config;

use Chapi\Component\DependencyInjection\Loader\ChapiConfigLoader;

class ChapiConfigLoaderTest extends \PHPUnit\Framework\TestCase
{
    /** @var \Prophecy\Prophecy\ObjectProphecy */
    private $container;

    /** @var \Prophecy\Prophecy\ObjectProphecy */
    private $config;

    protected function setUp(): void
    {
        // Symfony\Component\DependencyInjection\ContainerInterface
        $this->container = $this->prophesize('Symfony\Component\DependencyInjection\ContainerBuilder');
        $this->config = $this->prophesize('Chapi\Component\Config\ChapiConfigInterface');
    }

    public function testLoadProfileParametersWithoutConfigSettings()
    {
        $chapiConfigLoader = new ChapiConfigLoader(
            $this->container->reveal(),
            $this->config->reveal()
        );

        $this->assertNull($chapiConfigLoader->loadProfileParameters());
    }

    public function testLoadProfileParametersWithConfigSettings()
    {
        $this->config->getProfileConfig()->willReturn([
            'parameters' => [
                'paramA' => 'A',
                'paramB' => 'B'
            ]
        ]);

        $chapiConfigLoader = new ChapiConfigLoader(
            $this->container->reveal(),
            $this->config->reveal()
        );

        $this->assertNull($chapiConfigLoader->loadProfileParameters());
    }

    public function testLoadProfileParametersFailure()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->config->getProfileConfig()->willReturn([
            'parameters' => 'not_valid'
        ]);

        $chapiConfigLoader = new ChapiConfigLoader(
            $this->container->reveal(),
            $this->config->reveal()
        );

        $chapiConfigLoader->loadProfileParameters();
    }
}
