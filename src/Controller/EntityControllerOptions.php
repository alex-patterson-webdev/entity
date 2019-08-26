<?php

namespace Arp\Entity\Controller;

use Arp\Stdlib\Service\OptionsAwareInterface;
use Arp\Stdlib\Service\OptionsAwareTrait;

/**
 * EntityControllerOptions
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Controller
 */
class EntityControllerOptions implements OptionsAwareInterface
{
    /**
     * @trait OptionsAwareTrait
     */
    use OptionsAwareTrait;

    /**
     * $templates
     *
     * @var array
     */
    protected $templates = [];

    /**
     * $routes
     *
     * @var array
     */
    protected $routes = [];

    /**
     * __construct
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    /**
     * getTemplate
     *
     * @param string $name
     *
     * @return string
     *
     * @throws \Exception
     */
    public function getTemplate($name)
    {
        if (empty($this->templates[$name])) {

            throw new \Exception(sprintf(
                'The template \'%s\' cannot be found.',
                $name
            ));
        }

        return $this->templates[$name];
    }

    /**
     * getTemplates
     *
     * Return the value of the Templates property.
     *
     * @return array
     */
    public function getTemplates()
    {
        return $this->templates;
    }

    /**
     * setTemplates
     *
     * Set the value of the Templates property.
     *
     * @param  array $templates
     */
    public function setTemplates(array $templates)
    {
        $this->templates = $templates;
    }

    /**
     * getRoute
     *
     * @param string $name
     *
     * @return string
     *
     * @throws \Exception
     */
    public function getRoute($name)
    {
        if (empty($this->routes[$name])) {

            throw new \Exception(sprintf(
                'The route \'%s\' cannot be found.',
                $name
            ));
        }

        return $this->routes[$name];
    }

    /**
     * getRoutes
     *
     * Return the value of the Routes property.
     *
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * setRoutes
     *
     * Set the value of the Routes property.
     *
     * @param  array $routes
     */
    public function setRoutes(array $routes)
    {
        $this->routes = $routes;
    }

    /**
     * setOptions
     *
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options) : OptionsAwareInterface
    {
        if (isset($options['routes']) && is_array($options['routes'])) {
            $this->setRoutes($options['routes']);
            unset($options['routes']);
        }

        if (isset($options['templates']) && is_array($options['templates'])) {
            $this->setTemplates($options['templates']);
            unset($options['templates']);
        }

        foreach($options as $name => $option) {
            $this->setOption($name, $option);
        }

        return $this;
    }

}