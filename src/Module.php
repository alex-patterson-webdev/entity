<?php

namespace Arp\Entity;

use Arp\Entity\Service\EntityServiceManager;
use Arp\Entity\Service\EntityServiceManagerProviderInterface;
use Zend\ModuleManager\Listener\ServiceListenerInterface;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\ModuleManager\ModuleManager;

/**
 * Module
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity
 */
class Module
{
    /**
     * init
     *
     * @param ModuleManagerInterface|ModuleManager $moduleManager
     */
    public function init(ModuleManagerInterface $moduleManager)
    {
        /** @var ServiceManager $serviceManager */
        $serviceManager = $moduleManager->getEvent()->getParam('ServiceManager');

        /** @var ServiceListenerInterface $serviceListener */
        $serviceListener = $serviceManager->get('ServiceListener');

        // EntityServiceManager
        $serviceListener->addServiceManager(
            EntityServiceManager::class,
            'entity_service_manager',
            EntityServiceManagerProviderInterface::class,
            'getEntityServiceConfig'
        );
    }

    /**
     * getConfig
     *
     * @return mixed
     */
    public function getConfig()
    {
        return array_replace_recursive(
            require __DIR__ . '/../config/routes.config.php',
            require __DIR__ . '/../config/module.config.php'
        );
    }

}