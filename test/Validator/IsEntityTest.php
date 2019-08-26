<?php

namespace ArpTest\Entity\Validator;

use Arp\Entity\Validator\IsEntity;
use Arp\Entity\Service\EntityServiceInterface;
use Zend\Validator\ValidatorInterface;
use PHPUnit\Framework\TestCase;

/**
 * IsEntityTest
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\Entity\Validator
 */
class IsEntityTest extends TestCase
{
    /**
     * $entityService
     *
     * @var EntityServiceInterface|MockObject
     */
    protected $entityService;

    /**
     * $fieldNames
     *
     * @var array
     */
    protected $fieldNames = [];

    /**
     * setUp
     *
     * @return void
     */
    public function setUp() : void
    {
        $this->fieldNames = ['id'];

        $this->entityService = $this->getMockForAbstractClass(EntityServiceInterface::class);
    }

    /**
     * testImplementsValidatorInterface
     *
     * Test that the validator implements ValidatorInterface.
     *
     * @test
     */
    public function testImplementsValidatorInterface()
    {
        $validator = new IsEntity($this->entityService, $this->fieldNames);

        $this->assertInstanceOf(ValidatorInterface::class, $validator);
    }

}