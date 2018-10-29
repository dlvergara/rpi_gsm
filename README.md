# rpi_gsm
PHP + Raspberry pi with GSM to read sms messages

#Setup PHP
If you need setup the raspberry pi with php, you can use this guide:
https://www.stewright.me/2016/03/turn-raspberry-pi-3-php-7-powered-web-server/

# Installing composer
https://getcomposer.org/download/
```
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
```

# DIALOUT GROUP
```
usermod -a -G dialout www-data
```

https://www.raspberrypi.org/forums/viewtopic.php?t=186596

https://www.itead.cc/wiki/images/6/6f/SIM800_Series_AT_Command_Manual_V1.05.pdf

https://www.itead.cc/wiki/images/4/47/IM150720001-Raspberry_PI_GSM_Add-on_V2.0-schematic.pdf

https://www.itead.cc/wiki/RPI_SIM800_GSM/GPRS_ADD-ON_V2.0