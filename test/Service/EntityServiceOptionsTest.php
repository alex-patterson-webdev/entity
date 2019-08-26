<?php

namespace ArpTest\Entity\Service;

use Arp\Entity\Service\EntityServiceInterface;
use Arp\Entity\Service\EntityServiceOptions;
use Arp\Entity\Service\EntityServiceOptionsInterface;
use PHPUnit\Framework\TestCase;

/**
 * EntityServiceOptionsTest
 *
 * @author  Alex Patterson <alex.patterson.webdev@gmail.com>
 * @package ArpTest\Entity\Service
 */
class EntityServiceOptionsTest extends TestCase
{
    /**
     * testImplementsEntityServiceOptionsInterface
     *
     * Ensure that the options class implements EntityServiceOptionsInterface.
     *
     * @test
     */
    public function testImplementsEntityServiceOptionsInterface()
    {
        $options = new EntityServiceOptions(EntityServiceInterface::class, []);

        $this->assertInstanceOf(EntityServiceOptionsInterface::class, $options);
    }

    /**
     * testClassNameIsSetViaConstructor
     *
     * Ensure that the className property value is set via the __construct arguments.
     *
     * @test
     */
    public function testClassNameIsSetViaConstructor()
    {
        $options = new EntityServiceOptions(EntityServiceInterface::class, []);

        $this->assertSame(EntityServiceInterface::class, $options->getClassName());
    }

    /**
     * testFactoryWillReturnNewInstanceWithProvidedOptions
     *
     * Ensure that the call to factory() will return a new options service instance with the provided $options.
     *
     * @param array $options
     *
     * @test
     */
    public function testFactoryWillReturnNewInstanceWithProvidedOptions(array $options = [])
    {
        $optionsService = new EntityServiceOptions(EntityServiceInterface::class, []);

        $instance = $optionsService->factory($options);

        $this->assertInstanceOf(EntityServiceOptionsInterface::class, $instance);
        $this->assertSame(EntityServiceInterface::class, $instance->getClassName());
        $this->assertSame($instance->getOptions(), $options);
    }

    /**
     * testIsTransactionEnabled
     *
     * Ensure we can modify the transactionEnabled options via constructor $options arguments.
     *
     * @param boolean  $expected
     * @param array    $options
     *
     * @dataProvider getIsTransactionEnabledData
     * @test
     */
    public function testIsTransactionEnabled($expected, array $options = [])
    {
        $optionsService = new EntityServiceOptions(EntityServiceInterface::class, $options);

        if ($expected) {
            $this->assertTrue($optionsService->isTransactionEnabled());
        } else {
            $this->assertFalse($optionsService->isTransactionEnabled());
        }
    }

    /**
     * getIsTransactionEnabledData
     *
     * @return array
     */
    public function getIsTransactionEnabledData()
    {
        return [
            [
                true,
                [
                    'transaction_enabled' => true,
                ]
            ],

            [
                false,
                [
                    'transaction_enabled' => false,
                ]
            ],
        ];
    }

    /**
     * testTransactionsAreEnabledByDefault
     *
     * Ensure that the default configuration enables the use of transactions.
     *
     * @test
     */
    public function testTransactionsAreEnabledByDefault()
    {
        $optionsService = new EntityServiceOptions(EntityServiceInterface::class, []);

        // Test they are enabled by default
        $this->assertTrue($optionsService->isTransactionEnabled());
    }

    /**
     * testSetTransactionEnabled
     *
     * @param boolean $enabled
     *
     * @dataProvider getSetTransactionEnabledData
     * @test
     */
    public function testSetTransactionEnabled($enabled)
    {
        $optionsService = new EntityServiceOptions(EntityServiceInterface::class, []);

        $optionsService->setTransactionEnabled($enabled);

        if ($enabled) {
            $this->assertTrue($optionsService->isTransactionEnabled());
        } else {
            $this->assertFalse($optionsService->isTransactionEnabled());
        }
    }

    /**
     * getSetTransactionEnabledData
     *
     * @return array
     */
    public function getSetTransactionEnabledData()
    {
        return [
            [
                true
            ],
            [
                false
            ],
        ];
    }

}