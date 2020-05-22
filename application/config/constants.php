<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//mail para pruebas
//define('MAILUSUARIOTEST', 'jgatica@sb.cl,jgatica@yahoo.com');
//define('MAILUSUARIOTEST', array('jgatica@sb.cl', 'rtorres@sb.cl') );
//define('MAILUSUARIOTEST', 'jgatica@sb.cl,jgatica@yahoo.com');
define('MAILUSUARIOTEST', 'jgatica@sb.cl,rtorres@sb.cl');

//nombre del proyecto
define('NOMBREPROYECTO', 'Movilidad de Colaboradores');


defined('SHOW_DEBUG_BACKTRACE') or define('SHOW_DEBUG_BACKTRACE', TRUE);
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
defined('FILE_READ_MODE')  or define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') or define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   or define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  or define('DIR_WRITE_MODE', 0755);


/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('MAX_CARACT_COM', 1000);
defined('FOPEN_READ')                           or define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     or define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       or define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  or define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   or define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              or define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            or define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       or define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

//constantes dashboard
//DOWN_MAX es el numero maximo de apagadas al pos
 define('DOWN_MAX', 3);

/* End of file constants.php */
/* Location: ./application/config/constants.php */
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
defined('EXIT_SUCCESS')        or define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          or define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         or define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   or define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  or define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') or define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     or define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       or define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      or define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      or define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code



defined('CLIENTID')                 or define('CLIENTID', 'sb-movilidad');
defined('CLIENTSECRET')             or define('CLIENTSECRET', 'ce441feb-0dc6-46ab-b9e0-1cd6fc68ea66');
defined('REDIRECTURI')              or define('REDIRECTURI', 'https://' . $_SERVER['HTTP_HOST'] . '/seleccion/indexcontrolador/index');
defined('URLAUTHORIZE')             or define('URLAUTHORIZE', 'https://sso-sac.sb.cl/auth/realms/ESB/protocol/openid-connect/auth');
defined('URLACCESSTOKEN')           or define('URLACCESSTOKEN', 'https://sso-sac.sb.cl/auth/realms/ESB/protocol/openid-connect/token');
defined('URLRESOURCEOWNERDETAILS')  or define('URLRESOURCEOWNERDETAILS', 'https://sso-sac.sb.cl/auth/realms/ESB/protocol/openid-connect/userinfo');
defined('LOGOUTREDIRECT')             or define('LOGOUTREDIRECT', 'https://sso-sac.sb.cl/auth/realms/ESB/protocol/openid-connect/logout?redirect_uri=');
defined('URLLOGOUT')                 or define('URLLOGOUT', 'https://sso-sac.sb.cl/auth/realms/ESB/protocol/openid-connect/logout');
defined('URLLOGOUTREDITECTION')     or define('URLLOGOUTREDITECTION', 'https://sso-sac.sb.cl/auth/realms/ESB/protocol/openid-connect/logout?redirect_uri=URI_ENCODED_URL');


defined('TOKENACCESO')              or define('TOKENACCESO', '');






