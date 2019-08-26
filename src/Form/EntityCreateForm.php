<?php

namespace Arp\Entity\Form;

use Zend\Form\FormElementManager\FormElementManagerV3Polyfill;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Form\Form;

/**
 * EntityCreateForm
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Form
 */
class EntityCreateForm extends Form implements InputFilterProviderInterface
{
    /**
     * $entityName
     *
     * @var string
     */
    protected $entityName;

    /**
     * __construct
     *
     * @param string $entityName
     * @param array  $options
     */
    public function __construct(string $entityName, array $options)
    {
        $this->entityName = $entityName;

        parent::__construct(null, $options);
    }

    /**
     * init
     *
     * @return void
     */
    public function init()
    {
        $entityName   = '';
        $fieldsetName = '';
        $formName     = 'create_' . $fieldsetName;

        $this->setName($formName);

        $this->add([
            'name' => 'create',
            'options' => [
                'label' => 'Create',
            ],
        ]);
    }

    /**
     * getFieldsetTypeSpec
     *
     * @param string $name
     * @param string $entityName
     *
     * @return string
     */
    protected function getFieldsetTypeSpec($name, $entityName)
    {
        /** @var FormElementManagerV3Polyfill $formElementManager */
        $formElementManager = $this->getFormFactory()->getFormElementManager();

        return $formElementManager->get(
            EntityFieldset::class,
            [
                'config' => [
                    'name'        => $name,
                    'entity_name' => $entityName,
                    'options'     => $this->getFieldsetOptionSpec(),
                    'attributes'  => $this->getFieldsetAttributeSpec(),
                ],
            ]
        );
    }

    /**
     * getFieldsetOptionSpec
     *
     * @return array
     */
    protected function getFieldsetOptionSpec()
    {
        return [
            'config' => [
                'entity_name' => '',
            ],
            'use_as_base_fieldset' => true,
        ];
    }

    /**
     * getFieldsetAttributeSpec
     *
     * @return array
     */
    protected function getFieldsetAttributeSpec()
    {
        return [];
    }

    /**
     * getInputFilterSpecification
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        $entityName = '';

        $spec = [
            $entityName => [
                'id' => [
                    'required'    => false,
                    'allow_empty' => true,
                ]
            ],
        ];

        return $spec;
    }


}