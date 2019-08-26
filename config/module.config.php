<?php

namespace Arp\Entity;

use Arp\Entity\Controller\EntityControllerOptions;
use Arp\Entity\Factory\Controller\EntityControllerOptionsFactory;
use Arp\Entity\Factory\Form\Element\EntitySelectFactory;
use Arp\Entity\Factory\Form\EntityFieldsetFactory;
use Arp\Entity\Factory\InputFilter\EntityInputFilterFactory;
use Arp\Entity\Factory\Validator\IsEntityFactory;
use Arp\Entity\Form\Element\EntitySelect;
use Arp\Entity\Form\EntityFieldset;
use Arp\Entity\InputFilter\EntityInputFilter;
use Arp\Entity\QueryFilter\Deleted;
use Arp\Entity\QueryFilter\EntityId;
use Arp\Entity\QueryFilter\EntityCriteria;
use Arp\Entity\Factory\Service\EntityQueryServiceFactory;
use Arp\Entity\Event\Listener\DateTimeStrategy;
use Arp\Entity\Service\EntityQueryService;
use Arp\Entity\Service\EntityServiceManager;
use Arp\Entity\Service\EntityPersistService;
use Arp\Entity\Factory\Event\Listener\DateTimeStrategyFactory;
use Arp\Entity\Factory\Service\EntityPersistServiceFactory;
use Arp\Entity\Factory\Service\EntityServiceManagerFactory;
use Arp\Entity\Factory\Hydrator\EntityHydratorFactory;
use Arp\Entity\Form\EntitySearchForm;
use Arp\Entity\Hydrator\EntityHydrator;
use Arp\Entity\Validator\IsEntity;
use Arp\QueryFilter\Factory\QueryFilterFactory;
use Zend\Hydrator\NamingStrategy\UnderscoreNamingStrategy;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'arp' => [
        'services' => [
            EntityPersistService::class => [
                'config' => [
                    'entity_manager' => 'doctrine.entitymanager.orm_default',
                ],
            ],
        ],
        'hydrators' => [

            EntityHydrator::class => [
                'config' => [
                    'entity_manager'  => 'doctrine.entitymanager.orm_default',
                    'naming_strategy' => UnderscoreNamingStrategy::class,
                ],
            ],
        ],
    ],
    'query_filter_manager' => [
        'factories' => [
            EntityId::class       => QueryFilterFactory::class,
            EntityCriteria::class => QueryFilterFactory::class,
            Deleted::class        => QueryFilterFactory::class,
        ],
    ],
    'service_manager' => [
        'shared' => [
            EntityControllerOptions::class => false,
            EntityQueryService::class      => false,
        ],
        'aliases' => [
            'EntityServiceManager' => EntityServiceManager::class,
        ],
        'factories' => [
            EntityControllerOptions::class => EntityControllerOptionsFactory::class,

            EntityServiceManager::class => EntityServiceManagerFactory::class,
            EntityPersistService::class => EntityPersistServiceFactory::class,
            EntityQueryService::class   => EntityQueryServiceFactory::class,

            // Listeners
            DateTimeStrategy::class => DateTimeStrategyFactory::class,

            // Hydrator Strategy
            UnderscoreNamingStrategy::class => InvokableFactory::class,
        ]
    ],
    'hydrators' => [
        'factories' => [
            EntityHydrator::class => EntityHydratorFactory::class,
        ],
    ],
    'validators' => [
        'shared' => [
            IsEntity::class => false,
        ],
        'factories' => [
            IsEntity::class => IsEntityFactory::class,
        ]
    ],
    'input_filters' => [
        'shared' => [
            EntityInputFilter::class => false,
        ],
        'factories' => [
            EntityInputFilter::class => EntityInputFilterFactory::class,
        ],
    ],
    'form_elements' => [
        'shared' => [
            EntityFieldset::class => false,
            EntitySelect::class   => false,
        ],
        'factories' => [
            EntityFieldset::class   => EntityFieldsetFactory::class,
            EntitySearchForm::class => InvokableFactory::class,
            EntitySelect::class     => EntitySelectFactory::class,
        ]
    ]
];