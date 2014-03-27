<?php namespace Msurguy\Tests;

use Msurguy\Honeypot\HoneypotValidator;

class HoneypotValidatorTest extends \PHPUnit_Framework_TestCase {

    private $validator;

    public function setUp()
    {
        $this->validator = new HoneypotValidator;
    }

    /** @test */
    public function it_passes_validation_when_value_is_empty()
    {
        $this->assertTrue(
            $this->validator->validate(null, '', null, null),
            'Validate should pass when value is empty.'
        );
    }

    /** @test */
    public function it_fails_validation_when_value_is_not_empty()
    {
        $this->assertFalse(
            $this->validator->validate(null, 'foo', null, null),
            'Validate should fail when value is not empty.'
        );
    }

}
