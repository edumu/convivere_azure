<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


define('SUPERADMIN', 3);
define('ADMIN'     , 2);
define('USER'      , 1);

define('CARD' , 6);
define('BANK' , 7);
define('STORE', 8);

define('IVA', 16);

define('STATUS_PAG', 'PAGADA');
define('STATUS_PEN', 'PENDIENTE');
define('STATUS_REC', 'RECHAZADA');

define('PTO_DESACTIVADO', 14);
define('PTO_ACTIVO'     , 13);

define('TITULO_NAVEGADOR', 'Convivere, por una sana convivencia');
define('SLOGAN'          , 'Por una sana convivencia');
define('CALL_CENTER'     , 'Call Center (55) 520-4567');
define('CORREO_OFICIAL'  , 'soluciones@convivere.mx');
define('DIR_OFICIAL'     , 'Benito Juarez, CDMX');
define('SELLO'           , 'CVR');

define('DIR_UPLOAD'    , 'upload/');
define('DIR_LOGOS'     , 'logo_edificios/');
define('DIR_DOCUMENTS' , 'docs_edificios/');
define('DIR_GASTOS'    , 'gastos_edificios/');
define('DIR_TBJO_MANTO', 'tbjo_manto_edificios/');
define('DIR_RECIBOS'   , 'recibo_pagos/');
define('DIR_FORMATOS'  , 'formato_pagos/');
define('DIR_EDO_CTA'   , 'estados_cuenta/');
define('DIR_CODE_BAR'  , 'code_bar/');

define('MSJ_SEG','Usted no cuenta con los privilegios necesarios para accesar a la sección solicitada');
define('MSJ_SES','Su sesión ha expirado, favor de ingresar al portal nuevamente');
define('MSJ_SES_C','Su sesión ha expirado');
define('AVISO_MORO_HEADER','Su sesión ha expirado');

define('OPENPAY_ID','murhuosyqouvqptjzlkr');
define('OPENPAY_PRIVATE_KEY','sk_3e90e0057c6c40bdbe2e4e2f88ee842c'); // FOR PHP
define('OPENPAY_PUBLIC_KEY' ,'pk_79e455b48c0341da8a0aa7fd959af813'); // FOR JAVASCRIPT
define('OPENPAY_MODE_PRODUCTION',FALSE);
define('OPENPAY_COMISION_PAYNET',"+8 pesos por comisión");
define('OPENPAY_REFERENCIA_PAYNET',"En caso de efectuarse la lectura por parte del escáner de la caja, favor de escribir la referencia tal como aparce en este recibo debajo del código de barras");
define('OPENPAY_ACLARACION_PAYNET',"Para cualquier duda sobre como cobrar, por favor llamar al teléfono (55) 5351 7371 en un horario de 8am a 9pm de lunes a domingo");
