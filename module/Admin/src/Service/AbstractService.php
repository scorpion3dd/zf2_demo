<?php

namespace Admin\Service;

use Interop\Container\ContainerInterface;
use Traversable;
use Zend\Stdlib\ArrayUtils;

/**
 * Class AbstractService
 * @package Admin\Service
 */
class AbstractService
{
    /** @var array[] $config */
    private array $config;

    /**
     * AbstractService constructor.
     * @param array<array> $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @var ContainerInterface ServiceManager
     */
    protected ContainerInterface $serviceManager;

    /**
     * @return ContainerInterface ServiceManager
     */
    public function getServiceManager(): ContainerInterface
    {
        return $this->serviceManager;
    }

    /**
     * @param ContainerInterface $serviceManager
     * @return $this
     */
    public function setServiceManager(ContainerInterface $serviceManager): self
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }

    /**
     * @return array<array>
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function getLocationPath(string $key): string
    {
        $config = $this->getConfig();
        if (! empty($config['module_config'][$key])) {
            return $config['module_config'][$key];
        } else {
            return '';
        }
    }
}
