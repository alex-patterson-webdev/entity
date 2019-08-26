<?php

namespace Arp\Entity\Service;

use Arp\Stdlib\Service\OptionsAwareInterface;

/**
 * EntityServiceOptionsInterface
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Service
 */
interface EntityServiceOptionsInterface extends OptionsAwareInterface
{
    /**
     * factory
     *
     * Create a new entity persist options instance.
     *
     * @param array $options The default options to set.
     *
     * @return EntityServiceOptionsInterface
     */
    public function factory(array $options);

    /**
     * getClassName
     *
     * Return the class name string.
     *
     * @return string
     */
    public function getClassName();

    /**
     * isTransactionEnabled
     *
     * Check if the transaction is enabled.
     *
     * @return boolean
     */
    public function isTransactionEnabled();

    /**
     * setTransactionEnabled
     *
     * @param boolean  $transactionEnabled
     */
    public function setTransactionEnabled($transactionEnabled);

    /**
     * isFlushEnabled
     *
     * Check if the flushing is enabled.
     *
     * @return boolean
     */
    public function isFlushEnabled();

    /**
     * setFlushEnabled
     *
     * If the transaction should be flushed.
     *
     * @param boolean $flushEnabled
     */
    public function setFlushEnabled($flushEnabled);

    /**
     * hasFlushOptions
     *
     * @return array
     */
    public function hasFlushOptions();

    /**
     * getFlushOptions
     *
     * @return array
     */
    public function getFlushOptions();

    /**
     * setFlushOptions
     *
     * @param array $flushOptions
     */
    public function setFlushOptions(array $flushOptions);

    /**
     * isEventTriggerEnabled
     *
     * @return boolean
     */
    public function isEventTriggerEnabled();

    /**
     * setEventTriggerEnabled
     *
     * @param boolean  $eventTriggerEnabled
     */
    public function setEventTriggerEnabled($eventTriggerEnabled);

    /**
     * hasEventOptions
     *
     * @return boolean
     */
    public function hasEventOptions();

    /**
     * getEventOptions
     *
     * @return array
     */
    public function getEventOptions();

    /**
     * setEventOptions
     *
     * @param array $options
     */
    public function setEventOptions(array $options = []);

}