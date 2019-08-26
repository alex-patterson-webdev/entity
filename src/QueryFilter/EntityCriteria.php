<?php

namespace Arp\Entity\QueryFilter;

use Arp\Entity\EntityInterface;
use Arp\DoctrineQueryFilter\Service\QueryBuilderInterface;
use Arp\DoctrineQueryFilter\QueryFilterInterface;
use Arp\Stdlib\Service\OptionsAwareInterface;
use Arp\Stdlib\Service\OptionsAwareTrait;

/**
 * EntityCriteria
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\DoctrineQueryFilter
 */
class EntityCriteria implements QueryFilterInterface, OptionsAwareInterface
{
    /**
     * @trait OptionsAwareTrait
     */
    use OptionsAwareTrait;

    /**
     * $entityName
     *
     * @var string
     */
    protected $entityName;

    /**
     * $criteria
     *
     * @var array
     */
    protected $criteria = [];

    /**
     * __construct
     *
     * @param string $entityName
     * @param array  $criteria
     * @param array  $options
     */
    public function __construct($entityName, array $criteria = [], array $options = [])
    {
        $this->entityName = $entityName;
        $this->criteria   = $criteria;

        $this->setOptions($options);
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
        $factory = $queryBuilder->factory();
        $alias   = $queryBuilder->getAlias();
        $andX    = $factory->andX();

        $searchFields = $this->getOption('search_fields', []);

        foreach($this->criteria as $name => $value) {

            if (! empty($searchFields) && ! in_array($name, $searchFields)) {
                continue;
            }

            if ($value instanceof EntityInterface) {
                $value = $value->getId();
            }

            switch($name) {
                case 'id' :
                    $value = $factory->create(EntityId::class, [$value, $alias, $name]);
                break;

                case 'deleted' :
                    $value = $factory->create(Deleted::class, [$value, $alias . '.' . $name]);
                break;

                default :
                    switch(true) {
                        case (is_array($value)) :
                            $value = $factory->in($alias . '.' . $name, $queryBuilder->setParameter($name, $value));
                        break;

                        case (is_null($value)) :
                        case ($value === 'NULL') :
                            $value = $factory->isNull($name, $alias);
                        break;

                        case ($value === 'NOTNULL') :
                            $value = $factory->isNull($name, $alias);
                        break;

                        default :
                            $value = $factory->eq($alias . '.' . $name, $queryBuilder->setParameter($name, $value));
                    }
            }

            if ($value instanceof QueryFilterInterface) {
                $andX->add($value);
            }
        }

        return (string) $andX->build($queryBuilder);
    }

}