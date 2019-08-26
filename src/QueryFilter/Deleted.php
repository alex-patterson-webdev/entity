<?php

namespace Arp\Entity\QueryFilter;

use Doctrine\DBAL\Types\Type;

/**
 * Deleted
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\DoctrineQueryFilter
 */
class Deleted implements QueryFilterInterface
{
    /**
     * $deleted
     *
     * @var boolean
     */
    protected $deleted;

    /**
     * $fieldName
     *
     * @var string
     */
    protected $fieldName = 'deleted';

    /**
     * __construct
     *
     * @param boolean     $deleted
     * @param string|null $fieldName
     */
    public function __construct($deleted, $fieldName = null)
    {
        $this->deleted = $deleted ? true : false;

        if (! empty($fieldName)) {
            $this->fieldName = $fieldName;
        }
    }

    /**
     * build
     *
     * @param QueryBuilderInterface $queryBuilder
     *
     * @return string
     */
    public function build(QueryBuilderInterface $queryBuilder)
    {
        return $queryBuilder->factory()->eq(
            $this->fieldName,
            $queryBuilder->setParameter('deleted', $this->deleted, Type::BOOLEAN)
        );
    }

}