<?php namespace Msurguy\Tests;

use Mockery;

class HoneypotTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \Mockery\MockInterface
     */
    private $honeypot;

    public function setUp()
    {
        $this->honeypot = Mockery::mock('Msurguy\Honeypot\Honeypot[getEncryptedTime]');
        $this->honeypot->shouldReceive('getEncryptedTime')->once()->withNoArgs()->andReturn('ENCRYPTED_TIME');
        $this->honeypot->shouldDeferMissing();
    }

    public function tearDown()
    {
        Mockery::close();
    }

    public function test_get_honeypot_form_html()
    {
        $actualHtml = $this->honeypot->generate('honey_name', 'honey_time');
        $expectedHtml = '' .
            '<div id="honey_name_wrap" style="display:none;">' .
            '<input name="honey_name" type="text" value="" id="honey_name"/>' .
            '<input name="honey_time" type="text" value="ENCRYPTED_TIME"/>' .
            '</div>';

        $this->assertEquals($actualHtml, $expectedHtml);
    }

}
