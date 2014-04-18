<?php namespace Msurguy\Tests;

use Mockery;
use Msurguy\Honeypot\Honeypot;
use Illuminate\Support\Facades\Facade;

class HoneypotTest extends \PHPUnit_Framework_TestCase {

    private $honeypot;
    private $crypt;
    private $view;

    public function setUp()
    {
        $this->honeypot = new Honeypot;
        $this->crypt = Mockery::mock();
        $this->view  = Mockery::mock();

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
        return array(
            'encrypter' => $this->crypt,
            'view' => $this->view
        );
    }

    /** @test */
    public function it_assigns_the_values_to_the_view()
    {
        $this->crypt->shouldReceive('encrypt')
                    ->with(1000)->once()
                    ->andReturn('encrypted');

        $viewVariables = array(
            'honey_name' => 'honey_name',
            'honey_time' => 'honey_time',
            'honey_time_encrypted' => 'encrypted'
        );

        $this->view->shouldReceive('make')
                   ->with('honeypot::fields', $viewVariables)->once()
                   ->andReturn('view');

        $result = $this->honeypot->getFormHtml('honey_name', 'honey_time');

        $this->assertEquals(
            'view',
            $result,
            'The values should be assigned to the view.'
        );
    }

}
