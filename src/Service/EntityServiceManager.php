<?php

namespace Arp\Entity\Service;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\Exception\InvalidServiceException;

/**
 * EntityServiceManager
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Service
 */
class EntityServiceManager extends AbstractPluginManager
{
    /**
     * validate
     *
     * @param EntityServiceInterface $entityService
     *
     * @throws InvalidServiceException  If the service provided is invalid.
     */
    public function validate($entityService)
    {
        if ($entityService instanceof EntityServiceInterface) {
            return;
        }

        throw new InvalidServiceException(sprintf(
            'The \'entityService\' argument must be an object of type \'%s\'; \'%s\' provided in \'%s\'.',
            EntityServiceInterface::class,
            (is_object($entityService) ? get_class($entityService) : gettype($entityService)),
            __METHOD__
        ));
    }

}