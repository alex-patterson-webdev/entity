<?php

namespace Arp\Entity\Factory\Service;

use Arp\Entity\Service\EntityServiceManager;
use Zend\Mvc\Service\AbstractPluginManagerFactory;

/**
 * EntityServiceManagerFactory
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Factory\Service
 */
class EntityServiceManagerFactory extends AbstractPluginManagerFactory
{
    /**
     * @const
     */
    const PLUGIN_MANAGER_CLASS = EntityServiceManager::class;
}