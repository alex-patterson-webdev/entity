<?php

namespace Arp\Entity\QueryFilter;

use Arp\DoctrineQueryFilter\QueryFilterInterface;
use Arp\DoctrineQueryFilter\Service\QueryBuilderInterface;

/**
 * EntityId
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\DoctrineQueryFilter
 */
class EntityId implements QueryFilterInterface
{
    /**
     * $fieldName
     *
     * @var string
     */
    protected $fieldName = 'id';

    /**
     * $alias
     *
     * @var string
     */
    protected $alias = '';

    /**
     * $id
     *
     * @var integer|string
     */
    protected $id;

    /**
     * __construct
     *
     * @param integer|string $id
     * @param string         $alias
     * @param string         $fieldName
     */
    public function __construct($id, $alias = '', $fieldName = 'id')
    {
        $this->id        = $id;
        $this->alias     = $alias;
        $this->fieldName = $fieldName;
    }

    /**
     * build
     *
     * Build the query filter expression.
     *
     * @param QueryBuilderInterface $queryBuilder
     *
     * @return string
     */
    public function build(QueryBuilderInterface $queryBuilder) : string
    {
        $alias = empty($this->alias) ? $queryBuilder->getAlias() : $this->alias;

        return (string) $queryBuilder->factory()
            ->eq($alias . '.' . $this->fieldName, $this->id)
            ->build($queryBuilder);
    }

}