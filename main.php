<?php
/**
 * Created by PhpStorm.
 * User: David Leonardo V
 * Date: 27/10/2018
 * Time: 7:10 PM
 */

class main
{
    private function setup()
    {
        require 'vendor/autoload.php';
    }

    public function run()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
        include "php_serial.class.php";

        $serial = new phpSerial;
        $serial->deviceSet("/dev/ttyAMA0");
        $serial->confBaudRate(115200);
        $serial->confParity("none");
        $serial->confCharacterLength(8);
        $serial->confStopBits(1);
        $serial->deviceOpen();
        $serial->sendMessage("Hello from my PHP script, say hi back!");

        $serial->deviceClose();

        echo "I've sended a message! \n\r";
    }
}

$obj = new main();
$obj->run();
