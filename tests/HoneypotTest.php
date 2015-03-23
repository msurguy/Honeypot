<?php namespace Msurguy\Tests;

use Mockery;

class HoneypotTest extends \PHPUnit_Framework_TestCase {

    private $honeypot;

    public function setUp()
    {
        $this->honeypot = Mockery::mock('Msurguy\Honeypot\Honeypot[getEncryptedTime]');
        $this->honeypot->shouldReceive('getEncryptedTime')->once()->andReturn('ENCRYPTED_TIME');
    }

    public function tearDown()
    {
        Mockery::close();
    }

    public function test_get_honeypot_form_html()
    {
        $actualHtml = $this->honeypot->getFormHTML('honey_name', 'honey_time');
        $expectedHtml = '' .
            '<div id="honey_name_wrap" style="display:none;">' . "\r\n" .
                '<input name="honey_name" type="text" value="" id="honey_name"/>' . "\r\n" .
                '<input name="honey_time" type="text" value="ENCRYPTED_TIME"/>' . "\r\n" .
            '</div>';

        $this->assertEquals($actualHtml, $expectedHtml);
    }

}