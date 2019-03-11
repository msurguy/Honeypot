<?php namespace Msurguy\Tests;

use Mockery;

class HoneypotValidatorTest extends \PHPUnit_Framework_TestCase {

    private $validator;

    public function setUp()
    {
        $this->validator = Mockery::mock('Msurguy\Honeypot\Honeypot[decryptTime]');
    }

    /** @test */
    public function it_passes_validation_when_value_is_empty()
    {
        $this->assertTrue(
            $this->validator->validateHoneypot(null, '', null),
            'Validate should pass when value is empty.'
        );
    }

    /** @test */
    public function it_fails_validation_when_value_is_not_empty()
    {
        $this->assertFalse(
            $this->validator->validateHoneypot(null, 'foo', null),
            'Validate should fail when value is not empty.'
        );
    }

    /** @test */
    public function it_passes_validation_when_values_are_before_current_time()
    {
        $this->assertTrue(
            $this->validateHoneyTime(100),
            'Validate should pass when values are before current time.'
        );
    }

    /** @test */
    public function it_fails_validation_when_values_are_after_current_time()
    {
        $this->assertFalse(
            $this->validateHoneyTime(1000),
            'Validate should fail when values are after current time.'
        );
    }

    /** @test */
    public function it_fails_validation_when_value_is_not_numeric()
    {
        $this->assertFalse(
            $this->validateHoneyTime('bar'),
            'Validate should fail when decrypted value is not numeric.'
        );
    }

    private function validateHoneyTime($time)
    {
        $this->validator
            ->shouldReceive('decryptTime')
            ->with('foo')->once()
            ->andReturn($time);

        return $this->validator->validateHoneytime(null, 'foo', array(100), null);
    }

}
