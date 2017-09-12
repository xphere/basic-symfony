<?php

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouteCollectionBuilder;

/*final */class Kernel extends BaseKernel
{
    public function getLogDir(): string
    {
        return $this->getVarDir() . '/logs';
    }

    public function getCacheDir(): string
    {
        return $this->getVarDir() . '/cache/' . $this->environment;
    }

    public function registerBundles(): iterable
    {
        $fileName = $this->getBootDir() . '/bundles.php';
        foreach ($this->filterByEnvironment(require $fileName) as $bundleClassName) {
            yield new $bundleClassName();
        }
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load($this->getConfigDir() . '/' . $this->environment . '.yaml');
    }

    public function loadRoutes(LoaderInterface $loader): RouteCollection
    {
        $builder = new RouteCollectionBuilder($loader);
        $filename = $this->getBootDir() . '/routes.php';

        $environments = $this->filterByEnvironment(require $filename);
        foreach ($environments as $routingFilename) {
            $builder->import($this->getConfigDir() . '/routing.d/' . $routingFilename);
        }

        $routeCollection = $builder->build();
        $routeCollection->addResource(new FileResource($filename));

        return $routeCollection;
    }

    protected function build(ContainerBuilder $container)
    {
        $fileName = $this->getBootDir() . '/extensions.php';
        $container->addResource(new FileResource($fileName));

        $content = require $fileName;
        $extensions = $content['extensions'];
        $environments = $this->filterByEnvironment($content['environments']);
        foreach ($environments as $extensionName) {
            if (!isset($extensions[$extensionName])) {
                throw new ErrorException(sprintf('Can\'t find index "%s" in extensions', $extensionName));
            }

            $callable = $extensions[$extensionName];
            $callable($container);
        }
    }

    protected function getKernelParameters(): array
    {
        $kernelParameters = parent::getKernelParameters();

        return array_merge($kernelParameters, [
            'kernel.boot_dir' => $this->getBootDir(),
            'kernel.config_dir' => $this->getConfigDir(),
            'kernel.source_dir' => $this->getSourceDir(),
            'kernel.var_dir' => $this->getVarDir(),
        ]);
    }

    private function getConfigDir()
    {
        return $this->getProjectDir() . '/etc';
    }

    private function getSourceDir()
    {
        return $this->getProjectDir() . '/src';
    }

    private function getVarDir()
    {
        return $this->getProjectDir() . '/var';
    }

    private function getBootDir()
    {
        return $this->getProjectDir() . '/boot';
    }

    private function filterByEnvironment(iterable $source): iterable
    {
        foreach ($source as $string => $environments) {
            if (
                $environments === true ||
                $environments === $this->environment ||
                ($environments[$this->environment] ?? false)
            ) {
                yield $string;
            }
        }
    }
}
