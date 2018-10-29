<?php
/**
 * Created by PhpStorm.
 * User: David Leonardo V
 * Date: 27/10/2018
 * Time: 7:10 PM
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');
date_default_timezone_set('America/Lima');
require(__DIR__ . '/vendor/autoload.php');

class main
{
    private function setup()
    {

    }

    /**
     * @param PhpSerial $serial
     */
    private function read(PhpSerial $serial)
    {
        // SMS inbox query - mode command and list command
        //$serial->sendMessage("AT", 1);
        //var_dump($serial->readPort());

        //$serial->sendMessage("AT+CMGF=1\n\r");
        //file_put_contents("CMGF_".date('Y-m-d-H-i-s').".txt", $serial->readPort());

        $serial->sendMessage("AT+CMGL=\"REC UNREAD\",0\n\r");
        file_put_contents("CMGL_".date('Y-m-d-H-i-s').".txt", $serial->readPort());
    }

    /**
     * @param PhpSerial $serial
     * @param $messageIndex
     */
    public function deleteMessage( PhpSerial $serial, $messageIndex )
    {
        //CMGD
        $serial->sendMessage("AT+CMGD={$messageIndex}\n\r");
        file_put_contents("CMGD_".date('Y-m-d-H-i-s').".txt", $serial->readPort());
    }

    /**
     * @param PhpSerial $serial
     */
    private function write(PhpSerial $serial)
    {
        $serial->sendMessage("AT+CMGF=1\n\r");
        file_put_contents("CMGF_".date('Y-m-d-H-i-s').".txt", $serial->readPort());

        $serial->sendMessage("AT+CMGS=\"+51982841966\",\"mensaje\"\n\r");
        file_put_contents("CMGS_".date('Y-m-d-H-i-s').".txt", $serial->readPort());
        sleep(1);

        //$serial->sendMessage("probando\n\r");
        //$serial->sendMessage("\x1A"); # Enable to send SMS

        //file_put_contents("CMGS2_".date('Y-m-d-H-i-s').".txt", $serial->readPort());

        # Sending a message to a particular Number
        #port.write('AT+CMGS="9495353464"'+'\r\n')
        #rcv = port.read(10)
        #print rcv
        #time.sleep(1)

        #port.write('Hello User'+'\r\n') # Message
        #rcv = port.read(10)
        #print rcv

        #port.write("\x1A") # Enable to send SMS
        #for i in range(10):
        #rcv = port.read(10)
        #print rcv
    }

    public function run()
    {
        $this->setup();

        // Let's start the class
        $serial = new PhpSerial();

        // First we must specify the device. This works on both linux and windows (if
        // your linux serial device is /dev/ttyS0 for COM1, etc)
        // If you are using Windows, make sure you disable FIFO from the modem's
        // Device Manager properties pane (Advanced >> Advanced Port Settings...)

        $deviceName = '/dev/serial0';
        //$deviceName = '/dev/ttyAMA0';
        $set = $serial->deviceSet($deviceName);
        var_dump($serial->_dState); echo chr(10);

        // We can change the baud rate
        $setRate = $serial->confBaudRate(115200);//9600
        var_dump($serial->_dState); echo chr(10);

        // Then we need to open it
        $open = $serial->deviceOpen('w+');
        var_dump($serial->_dState); echo chr(10);

        if ($set && $open) {
            // We may need to return if nothing happens for 10 seconds
            stream_set_timeout($serial->_dHandle, 10);
            echo '----', chr(10);
            //$this->read($serial);
            echo '----.----', chr(10);
            $this->write($serial);
            echo '----.----', chr(10);
            /*
            do {
                echo '----', chr(10);
                $this->read($serial);
                echo '----.----', chr(10);
                $break = false;
            } while ($break);
            */
        } else {
            var_dump($serial->_dState);
        }

        // If you want to change the configuration, the device must be closed
        $serial->deviceClose();
        echo "finish.".chr(10);
    }
}

$obj = new main();
$obj->run();
