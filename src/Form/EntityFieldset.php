<?php

namespace Arp\Entity\Form;

use Zend\Form\Fieldset;

/**
 * EntityFieldset
 *
 * Generic fieldset instance automatically generated by the injected entity metadata,
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Form
 */
class EntityFieldset extends Fieldset
{


    /**
     * $elementSpec
     *
     * @var array
     */
    protected $elementSpec = [];

    /**
     * __construct
     *
     * @param string|null             $name
     * @param array                   $options
     */
    public function __construct($name = null, array $options = [])
    {
        parent::__construct($name, $options);
    }


}