<?php namespace Msurguy\Tests;

use Mockery;
use Illuminate\Support\Facades\Facade;
use Msurguy\Honeypot\HoneytimeValidator;

class HoneytimeValidatorTest extends \PHPUnit_Framework_TestCase {

    private $crypt;
    private $validator;

    public function setUp()
    {
        $this->validator = new HoneytimeValidator;
        $this->crypt = Mockery::mock();

        $app = $this->getFacadeApplication();

        Facade::setFacadeApplication($app);
    }

    public function tearDown()
    {
        Mockery::close();
        Facade::setFacadeApplication(null);
        Facade::clearResolvedInstances();
    }

    private function getFacadeApplication()
    {
        return array( 'encrypter' => $this->crypt );
    }

    private function runValidate()
    {
        return $this->validator->validate(null, 'foo', [ 100 ], null);
    }

    /** @test */
    public function it_passes_validation_when_values_are_before_current_time()
    {
        $this->crypt->shouldReceive('decrypt')
                    ->with('foo')->once()
                    ->andReturn(100);

        $this->assertTrue(
            $this->runValidate(),
            'Validate should pass when values are before current time.'
        );
    }

    /** @test */
    public function it_fails_validation_when_values_are_after_current_time()
    {
        $this->crypt->shouldReceive('decrypt')
                    ->with('foo')->once()
                    ->andReturn(1000);

        $this->assertFalse(
            $this->runValidate(),
            'Validate should fail when values are after current time.'
        );
    }

    /** @test */
    public function it_fails_validation_when_value_is_not_numeric()
    {
        $this->crypt->shouldReceive('decrypt')
                    ->with('foo')->once()
                    ->andReturn('bar');

        $this->assertFalse(
            $this->runValidate(),
            'Validate should fail when decrypted value is not numeric.'
        );
    }

}
