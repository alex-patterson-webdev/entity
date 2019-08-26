<?php

namespace Arp\Entity\Validator;

use Arp\Entity\EntityInterface;
use Arp\Entity\Service\EntityServiceInterface;
use Zend\Validator\Exception\RuntimeException;

/**
 * AbstractValidator
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package Arp\Entity\Validator
 */
abstract class AbstractValidator extends \Zend\Validator\AbstractValidator
{
    /**
     * $entityService
     *
     * @var EntityServiceInterface
     */
    protected $entityService;

    /**
     * $entityName
     *
     * @var string
     */
    protected $entityName;

    /**
     * $fieldNames
     *
     * @var array
     */
    protected $fieldNames = [];

    /**
     * __construct
     *
     * @param EntityServiceInterface $entityService
     * @param array                  $fieldNames
     * @param null|array             $options
     */
    public function __construct(EntityServiceInterface $entityService, array $fieldNames, $options = null)
    {
        $this->entityService = $entityService;
        $this->entityName    = $entityService->getEntityName();
        $this->fieldNames    = $fieldNames;

        parent::__construct($options);
    }

    /**
     * getFilterCriteria
     *
     * @param mixed  $values   The values that should be validated.
     * @param array  $context  Additional information that can be used to validate.
     *
     * @return array
     *
     * @throws RuntimeException  If the match cannot be performed or the provided data is invalid.
     */
    protected function getFilterCriteria($values, array $context = [])
    {
        $data = $this->getValidationValues($values, $context);

        // String index array, map index to a field name.
        $isHashMap  = (array_values($data) !== $data);
        $criteria   = [];

        if ($isHashMap) {

            foreach($this->fieldNames as $fieldName) {

                if (! array_key_exists($fieldName, $data)) {

                    throw new RuntimeException(sprintf(
                        'The expected validation value for field \'%s::%s\' could not not found in \'%s\'.',
                        $this->entityName,
                        $fieldName,
                        __METHOD__
                    ));
                }

                $criteria[$fieldName] = $data[$fieldName];
            }
        }
        else {
            $criteria = @array_combine($this->fieldNames, $data);

            if (! is_array($criteria)) {

                throw new RuntimeException(sprintf(
                    'The expected validation for entity \'%s\' field values could not be found for numeric index array in \'%s\'.',
                    $this->entityName,
                    __METHOD__
                ));
            }
        }

        return $criteria;
    }

    /**
     * getValidationValues
     *
     * Create an array of field name to field values based on the provided value.
     *
     * @param mixed  $values   The values that should be validated.
     * @param array  $context  Additional information that can be used to validate.
     *
     * @return array
     *
     * @throws RuntimeException  If the criteria cannot be created.
     */
    protected function getValidationValues($values, array $context = [])
    {
        $useContext = (boolean) $this->getOption('useContext') ?: true;

        if (null === $values || empty($values)) {
            /**
             * @NOTE If you have NULL/empty here you probably have a config issue. This validator would normally require a
             * UNIQUE value to be compared, empty string obviously will not work.
             *
             * SUGGESTION - Perhaps you need to have a not empty validator registered before this one
             * with break_chain_on_failure = true? This will prevent the NULL from being passed here.
             */
            $values = '';
        }

        if ($values instanceof EntityInterface) {
            $values = $values->getId();
        }

        if (is_scalar($values) && isset($this->fieldNames[0]) && count($this->fieldNames) == 1) {

            /**
             * Here we are mapping the single scalar value to the single match field, casting the value to an array.
             */

            $values = [
                $this->fieldNames[0] => $values
            ];
        }
        elseif (is_scalar($values) && (count($this->fieldNames) > 1 || $useContext)) {
            /**
             * @note if we have a scalar value and more than one field in the match criteria then we have to use
             *       the context as the replacement.
             */
            $values = $context;
        }

        if (! is_array($values)) {

            throw new RuntimeException(sprintf(
                'The \'values\' argument of type \'%s\' could not be resolved \'array\' in \'%s\'.',
                gettype($values),
                __METHOD__
            ));
        }

        return $values;
    }
}