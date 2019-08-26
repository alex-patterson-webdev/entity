<?php

namespace Arp\Entity\Controller;

use Arp\Entity\Service\EntityServiceInterface;
use Arp\Form\Service\FormElementProviderInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * AbstractEntityController
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Controller
 */
abstract class AbstractEntityController extends AbstractActionController
{
    /**
     * $options
     *
     * @var EntityControllerOptions
     */
    protected $options;

    /**
     * $entityService
     *
     * @var EntityServiceInterface
     */
    protected $entityService;

    /**
     * $formElementProvider
     *
     * @var FormElementProviderInterface
     */
    protected $formElementProvider;

    /**
     * __construct
     *
     * @param EntityControllerOptions      $options
     * @param EntityServiceInterface       $entityService
     * @param FormElementProviderInterface $formElementProvider
     */
    public function __construct(
        EntityControllerOptions $options,
        EntityServiceInterface $entityService,
        FormElementProviderInterface $formElementProvider
    ){
        $this->options             = $options;
        $this->entityService       = $entityService;
        $this->formElementProvider = $formElementProvider;
    }

    /**
     * createViewModel
     *
     * @param array $vars
     * @param null  $template
     * @param array $options
     *
     * @return ViewModel
     */
    protected function createViewModel(array $vars = [], $template = null, array $options = [])
    {
        $model = new ViewModel($vars, $options);

        if ($template) {
            $model->setTemplate($template);
        }

        return $model;
    }

}