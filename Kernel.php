<?php

use Symfony\Component\Config\Loader\LoaderInterface;

class Kernel extends Symfony\Component\HttpKernel\Kernel
{
    private const DIR_ROOT   = __DIR__;
    private const DIR_CONFIG = self::DIR_ROOT . '/etc';
    private const DIR_VAR    = self::DIR_ROOT . '/var';

    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
        ];

        if ($this->debug) {
            $bundles = array_merge($bundles, [
                new Sensio\Bundle\DistributionBundle\SensioDistributionBundle(),
                new Symfony\Bundle\DebugBundle\DebugBundle(),
                new Symfony\Bundle\TwigBundle\TwigBundle(),
                new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle(),
            ]);
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(self::DIR_CONFIG . '/env/' . $this->getEnvironment() . '.yml');
    }

    protected function getKernelParameters()
    {
        return array_merge(parent::getKernelParameters(), [
            'kernel.config_dir' => self::DIR_CONFIG,
        ]);
    }

    public function getLogDir()
    {
        return self::DIR_VAR . '/logs';
    }

    public function getCacheDir()
    {
        return self::DIR_VAR . '/cache/' . $this->getEnvironment();
    }
}
