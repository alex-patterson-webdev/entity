<?php

namespace Arp\Entity\Service;

/**
 * EntityServiceManagerProviderInterface
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Service
 */
interface EntityServiceManagerProviderInterface
{
    /**
     * getEntityServiceConfig
     *
     * @return array
     */
    public function getEntityServiceConfig();

}