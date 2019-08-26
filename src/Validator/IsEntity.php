<?php

namespace Arp\Entity\Validator;

use Arp\Entity\EntityInterface;
use Arp\Entity\Exception\EntityServiceException;

/**
 * IsEntity
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Validator
 */
class IsEntity extends AbstractValidator
{
    /**
     * @const
     */
    const ERROR_ENTITY_NOT_FOUND = 'entity.not_found';

    /**
     * $messageTemplates
     *
     * @var array
     */
    protected $messageTemplates = [
        self::ERROR_ENTITY_NOT_FOUND => 'An entity of type \'%entityName%\' could not be found matching for value \'%value%\'.'
    ];

    /**
     * abstractOptions
     *
     * @var array
     */
    protected $abstractOptions = [
        'messageVariables'     => [
            'entityName' => 'entityName', // Placeholder variables that can be nested in the validation error messages.
        ],
        'messages'         => [], // Array of validation failure messages
        'messageTemplates' => [], // Array of validation failure message templates

        'translator'           => null,    // Translation object to used -> Translator\TranslatorInterface
        'translatorTextDomain' => null,    // Translation text domain
        'translatorEnabled'    => true,    // Is translation enabled?
        'valueObscured'        => false,   // Flag indicating whether or not value should be obfuscated
    ];

    /**
     * isValid
     *
     * Validate the entity exists within the database.
     *
     * @param mixed       $value    The value that should be validated.
     * @param array|null  $context  Optional additional data to use to perform the validation.
     *
     * @return boolean
     */
    public function isValid($value, array $context = [])
    {
        $criteria = $this->getFilterCriteria($value, $context);

        try {
            $entityName = $this->entityService->getEntityName();

            if (! empty($criteria)) {
                $entity = $this->entityService->getOne($criteria);

                if ($entity instanceof EntityInterface && $entity instanceof $entityName) {
                    return true;
                }
            }
        }
        catch (EntityServiceException $e) {

        }

        $this->error(static::ERROR_ENTITY_NOT_FOUND, $value);

        return false;
    }

}