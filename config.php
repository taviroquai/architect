<?php

error_reporting(E_ALL | E_NOTICE);
ini_set('display_errors', true);

define('DS', DIRECTORY_SEPARATOR);
define('BASEPATH', '/var/www/architect');
define('BASEURL', '/architect/');

define('THEME', 'default');

define('DBDSN', 'mysql:host=localhost;dbname=test');
define('DBUSER', 'root');
define('DBPASS', 'toor');

define('MAILFROM', 'mafonso333@gmail.com');
define('MAILFROMNAME', 'Application Name');
define('MAILREPLY', 'mafonso333@gmail.com');
define('MAILREPLYNAME', 'Application Name');
define('MAILSUBJECT', '');
define('MAILTEMPLATE', 'theme/default/mail.php');

define('IDIOM', 'en');


