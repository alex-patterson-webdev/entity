<?php

namespace Arp\Entity\Service;

use Arp\Stdlib\Service\OptionsAwareTrait;

/**
 * EntityServiceOptions
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Service
 */
class EntityServiceOptions implements EntityServiceOptionsInterface
{
    /**
     * @trait OptionsAwareTrait
     */
    use OptionsAwareTrait;

    /**
     * $className
     *
     * @var string
     */
    protected $className;

    /**
     * __construct
     *
     * @param string $className
     * @param array  $options
     */
    public function __construct($className, array $options = [])
    {
        $this->className = $className;

        $this->setOptions($options);
    }

    /**
     * getClassName
     *
     * Return the class name string.
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * factory
     *
     * Create a new entity persist options instance.
     *
     * @param array $options The default options to set.
     *
     * @return EntityServiceOptionsInterface
     */
    public function factory(array $options)
    {
        return new static($this->className, $options);
    }

    /**
     * isTransactionEnabled
     *
     * Check if the transaction is enabled.
     *
     * @return boolean
     */
    public function isTransactionEnabled()
    {
        return (true === $this->getOption('transaction_enabled', false));
    }

    /**
     * setTransactionEnabled
     *
     * @param boolean $transactionEnabled
     */
    public function setTransactionEnabled($transactionEnabled)
    {
        $this->setOption('transaction_enabled', $transactionEnabled);
    }

    /**
     * isFlushEnabled
     *
     * Check if the flushing is enabled.
     *
     * @return boolean
     */
    public function isFlushEnabled()
    {
        return (true === $this->getOption('flush_enabled', false));
    }

    /**
     * setFlushEnabled
     *
     * If the transaction should be flushed.
     *
     * @param boolean $flushEnabled
     */
    public function setFlushEnabled($flushEnabled)
    {
        $this->setOption('flush_enabled', $flushEnabled);
    }

    /**
     * hasFlushOptions
     *
     * @return boolean
     */
    public function hasFlushOptions() : bool
    {
        return $this->hasOption('flush_options');
    }

    /**
     * getFlushOptions
     *
     * @return array
     */
    public function getFlushOptions()
    {
        return $this->getOption('flush_options', []);
    }

    /**
     * setFlushOptions
     *
     * @param array $flushOptions
     */
    public function setFlushOptions(array $flushOptions)
    {
        $this->setOption('flush_options', $flushOptions);
    }

    /**
     * isEventTriggerEnabled
     *
     * @return boolean
     */
    public function isEventTriggerEnabled()
    {
        return (true === $this->getOption('event_trigger_enabled', true));
    }

    /**
     * setEventTriggerEnabled
     *
     * @param boolean $eventTriggerEnabled
     */
    public function setEventTriggerEnabled($eventTriggerEnabled)
    {
        $this->setOption('event_trigger_enabled', true);
    }

    /**
     * hasEventOptions
     *
     * @return boolean
     */
    public function hasEventOptions()
    {
        return $this->hasOption('event_options');
    }

    /**
     * getEventOptions
     *
     * @return array
     */
    public function getEventOptions()
    {
        return $this->getOption('event_options', []);
    }

    /**
     * setEventOptions
     *
     * @param array $eventOptions
     */
    public function setEventOptions(array $eventOptions = [])
    {
        $this->setOption('event_options', $eventOptions);
    }

}