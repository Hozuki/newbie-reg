<?php
/**
 * Created by PhpStorm.
 * User: MIC
 * Date: 2015/8/18
 * Time: 21:31
 */

define('NR_LOGIN_COOKIE_NAME', 'nr-login');

/**
 * @return bool
 */
function __isLoggedIn()
{
    return !!$_COOKIE[NR_LOGIN_COOKIE_NAME];
}

function __requireLogIn() {
    if (!__isLoggedIn()) {
        die('Please log in.');
    }
}

?>
