<?php
if (!defined('VFM_APP')) {
    return;   
}
require 'users/users.php';

function existsInArray($newname, $newnames)
{
    if (in_array($newname, $newnames)) {
        return true;
    }
    return false;
}

function updateUsersSens()
{
    $sensi_result = array();
    global $_USERS;
    $usrs = '$_USERS = ';
    if (false == (file_put_contents('users/users.php', "<?php\n\n $usrs".var_export($_USERS, true).";\n"))) {
        $sensi_result['status'] = 'nope';
        $sensi_result['message'] = 'Trying to update usernames... Error writing on users/users.php, check CHMOD';
    } else {
        $sensi_result['status'] = 'yep';
        $sensi_result['message'] = 'Case-sensitive usernames updated';
    }
    return $sensi_result;
}

$newnames = array();

foreach ($_USERS as $key => $user) {

    $newname = $user['name'];
    
    $i = 0;
    do {
        $exists = existsInArray(strtolower($newname), $newnames);
        if ($exists) {
            $i++;
            $newname = $user['name'] . $i;
        }
    } while ($exists);

    if ($i > 0) {
        $_USERS[$key]['sensitive'] = $user['name'];
    }
    $newnames[] = strtolower($newname);
    $_USERS[$key]['name'] = $newname;
}

// echo "<pre>";
// print_r($_USERS);
// echo "</pre>";
$get_sensiresult = updateUsersSens();
// echo "<pre>";
// print_r($_USERS);
// echo "</pre>";
if (isset($get_sensiresult['status'])) {
    $response = array();
    array_push($response, $get_sensiresult); 
    if ($get_sensiresult['status'] === 'yep' ) {
         unlink(__FILE__);
    }
}
