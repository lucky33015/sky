<?php

$salt = '6ffdf757e9e409725f34285f13ea2f56';
$pass = 'password';
$encrypt = crypt($salt.urlencode($pass), randomString());
/**
* Generate random string
*
* @param string $length string lenght
*
* @return $randomString random string
*/
function randomString($length = 9)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return '$1$'.$randomString;
}

echo "PASS:" .$encrypt;