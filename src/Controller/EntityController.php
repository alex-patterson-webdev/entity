<?php

namespace Arp\Entity\Controller;

use Arp\Entity\EntityInterface;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;
use Zend\View\Model\ViewModel;
use Zend\Form\Form;

/**
 * EntityController
 *
 * @method Request getRequest()
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Controller
 */
class EntityController extends AbstractEntityController
{
    /**
     * indexAction
     *
     * List the entity resources.
     *
     * @return ViewModel|Response
     *
     * @throws \Exception
     */
    public function indexAction()
    {
        $search = $this->params()->fromQuery('search', false);

        /** @var Form $form */
        $form     = $this->formElementProvider->getElement('index');
        $options  = $this->options;
        $criteria = [];

        if ($search) {
            $form->setData($this->params()->fromQuery());

            if ($form->isValid()) {
                $criteria = $form->getData();
            }
        }

        $collection = $this->entityService->getCollection($criteria);

        $viewModel = $this->createViewModel(compact('form', 'collection', 'options'));
        $viewModel->setTemplate($options->getTemplate('index'));

        return $viewModel;
    }

    /**
     * createAction
     *
     * Create a new account.
     *
     * @return ViewModel|Response|\Zend\Http\Response
     *
     * @throws \Exception
     */
    public function createAction()
    {
        $entityName = $this->entityService->getEntityName();

        /** @var Form $form */
        $form = $this->formElementProvider->getElement('create', ['entity_name' => $entityName]);

        /** @var Request $request */
        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                /** @var EntityInterface $entity */
                $entity = $form->getData();

                if ($entity instanceof EntityInterface && $entity instanceof $entityName) {
                    $this->entityService->save($entity);

                    return $this->redirect()->toRoute($this->options->getRoute('index'));
                }

                return $this->redirect()->refresh();
            }
        }

        $viewModel = $this->createViewModel(compact('form'));
        $viewModel->setTemplate($this->options->getTemplate('create'));

        return $viewModel;
    }

    /**
     * editAction
     *
     * Allow the user to edit an account.
     *
     * @return ViewModel|Response|\Zend\Http\Response
     *
     * @throws \Exception
     */
    public function editAction()
    {
        $entityName = $this->entityService->getEntityName();
        $id = $this->params()->fromRoute('id');

        $entity = empty($id) ? null : $this->entityService->getOneById($id);

        if (! $entity instanceof EntityInterface || ! $entity instanceof $entityName) {

            return $this->redirect()->toRoute($this->options->getRoute('index'));
        }

        /** @var Form $form */
        $form    = $this->formElementProvider->getElement('edit');
        $request = $this->getRequest();

        $form->bind($entity);

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->entityService->save($entity);

                return $this->redirect()->toRoute($this->options->getRoute('index'));
            }
        }

        $view = new ViewModel(compact('entity', 'form'));
        $view->setTemplate($this->options->getTemplate('edit'));

        return $view;
    }

    /**
     * deleteAction
     *
     * @return \Zend\Http\Response|ViewModel
     *
     * @throws \Exception
     */
    public function deleteAction()
    {
        $entityName = $this->entityService->getEntityName();
        $id = $this->params()->fromRoute('id');

        $entity = empty($id) ? null : $this->entityService->getOneById($id);

        if (! $entity instanceof EntityInterface || ! $entity instanceof $entityName) {
            return $this->redirect()->toRoute($this->options->getRoute('index'));
        }

        /** @var Form $form */
        $form    = $this->formElementProvider->getElement('delete');
        $request = $this->getRequest();

        $form->bind($entity);

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $this->entityService->delete($entity);

                return $this->redirect()->toRoute($this->options->getRoute('index'));
            }
        }

        $view = new ViewModel(compact('entity', 'form'));
        $view->setTemplate($this->options->getTemplate('delete'));

        return $view;
    }

}