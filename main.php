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

        $serial->sendMessage("AT+CMGL=\"REC UNREAD\",0\n\r");
        //file_put_contents("CMGL_".date('Y-m-d-H-i-s').".txt", $serial->readPort());
    }

    /**
     * @param PhpSerial $serial
     * @param $messageIndex
     */
    public function deleteMessage(PhpSerial $serial, $messageIndex)
    {
        //CMGD
        $serial->sendMessage("AT+CMGD={$messageIndex}\n\r");
        file_put_contents("CMGD_" . date('Y-m-d-H-i-s') . ".txt", $serial->readPort());
    }

    /**
     * @param PhpSerial $serial
     */
    private function write(PhpSerial $serial, array $command)
    {
        //AT+CMGS="+85291234567",145<CR>This is an example for illustrating the syntax of the +CMGS AT command in SMS text mode.<Ctrl+z>
        //$serial->sendMessage("AT+CMGS=?\n\r");//,145\n\r
        //$serial->serialflush();

        //$serial->sendMessage("AT+CMGF=1\n\r");

        foreach ($command as $cmd) {
            $serial->sendMessage($cmd);
            sleep(1);
        }
        //file_put_contents("CMGS_".date('Y-m-d-H-i-s').".txt", $serial->readPort());

        //$serial->sendMessage("AT+CMGS=\"982841966\"\n\r");//,145\n\r
        //sleep(1);
        //$serial->sendMessage("mensaje");
        //$serial->sendMessage("\x1A"); # Enable to send SMS
        //$serial->sendMessage("\x1a"); # Enable to send SMS
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

    public function sendSms(PhpSerial $serial)
    {
        $command[] = "AT+CMGS=\"982841966\"\n\r";
        $command[] = "6668701\n\r";
        $command[] = "\x1A";
        $this->write($serial, $command);
    }

    public function callByPhone(PhpSerial $serial)
    {
        $command[] = "ATD982841966\n\r";
        //$command[] = "6668701";
        //$command[] = "\x1A";
        $this->write($serial, $command);
    }

    /**
     * Run
     */
    public function run($args = array())
    {
        $this->setup();
        $mode = isset($args[1]) ? $args[1] : 1;

        // Let's start the class
        $serial = new PhpSerial();

        // First we must specify the device. This works on both linux and windows (if
        // your linux serial device is /dev/ttyS0 for COM1, etc)
        // If you are using Windows, make sure you disable FIFO from the modem's
        // Device Manager properties pane (Advanced >> Advanced Port Settings...)

        $deviceName = '/dev/serial0';
        //$deviceName = '/dev/ttyAMA0';
        $set = $serial->deviceSet($deviceName);
        var_dump($serial->_dState);
        echo chr(10);

        // We can change the baud rate
        $setRate = $serial->confBaudRate(115200);//9600
        $serial->confParity("none");
        $serial->confCharacterLength(8);
        $serial->confStopBits(1);
        var_dump($serial->_dState);
        echo chr(10);

        // Then we need to open it
        $open = $serial->deviceOpen('w+');
        var_dump($serial->_dState);
        echo chr(10);

        if ($set && $open) {
            // We may need to return if nothing happens for 10 seconds
            stream_set_timeout($serial->_dHandle, 1);

            $serial->serialflush();
            //$serial->sendMessage("AT+CMGF=1\n\r");
            //file_put_contents("CMGF_" . date('Y-m-d-H-i-s') . ".txt", $serial->readPort());

            switch ($mode) {
                case 1:
                    $this->read($serial);
                    break;
                case 'read':
                    $this->read($serial);
                    break;
                case 2:
                    $this->sendSms($serial);
                    break;
                case 'write':
                    $this->sendSms($serial);
                    break;
                default:
                    $this->read($serial);
                    break;
            }

            echo '----', chr(10);
            //$this->callByPhone($serial);
            //sleep(60);
            //echo '----.----', chr(10);
            //$this->sendSms($serial);
            //sleep(2);
            //echo '----.----', chr(10);
            //$this->read($serial);
            //echo '----.----', chr(10);
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
        $close = $serial->deviceClose();
        echo "finish -> " . print_r($close, true) . chr(10);
    }
}

$obj = new main();
$obj->run($argv);
