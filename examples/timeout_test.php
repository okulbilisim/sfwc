<?php

include '../sfwc.php';

# set expire time as 5 seconds
SFWC::get()->setExpireTime(5);

# usual stuff
SFWC::get()->initToken('login_form');
$token = SFWC::get()->getToken();

# 6 seconds and timeout, edit and try 4.
sleep(6);
var_dump(SFWC::get()->controlToken($token,'login_form'));

?>




