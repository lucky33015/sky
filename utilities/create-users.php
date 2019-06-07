<?php
/**
 * VFM - veno file manager create-users.php
 *
 * Use this file to programmatically create users
 * copy the file inside your main /vfm/ directory and run it.
 *
 */
require 'vfm-admin/config.php';

$basename = filter_input(INPUT_POST, 'basename', FILTER_SANITIZE_STRING);
$role = filter_input(INPUT_POST, 'role', FILTER_SANITIZE_STRING);
$howmany = filter_input(INPUT_POST, 'howmany', FILTER_SANITIZE_NUMBER_INT);
$leng = filter_input(INPUT_POST, 'leng', FILTER_SANITIZE_NUMBER_INT);

/**
* generate random string
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
    return "$1$".$randomString;
}


/**
* generate random password
*
* @param string $length string lenght
*
* @return $randomString random string
*/
function randomPass($length = 6) 
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomPass = '';
    for ($i = 0; $i < $length; $i++) {
        $randomPass .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomPass;
}


/**
* update users file
*
* @param string $userslist complete users list
*
* @return file updated
*
*/
function updateUsers($userslist)
{
    $usrs = '$_USERS = ';

    echo "<hr>";

    if ( false == (file_put_contents(
        'new-users.php', "<?php\n\n $usrs".var_export($userslist, true).";\n"
    ))
    ) {
        echo '<div class="alert alert-error" role="alert">error creating <strong>users-new.php</strong></div>';
    } else {
        echo '<div class="alert alert-success" role="alert">users created inside <strong>users-new.php</strong></div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Recursive function to generate users</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

  </head>
  <body>
    <div class="container">
        <h1>Recursive function to generate users on VFM</h1>

<?php
if ($basename && $howmany && $leng && $role) {

    $logfile = fopen("new-users.txt", "w") or die("Unable to open file!");
    echo '<p class="lead">A new file <strong>new-users.php</strong> has been generated, rename it in <strong>users.php</strong> and replace the old one inside of <strong>/vfm-admin/users/</strong></p>';

    echo '<p class="lead">A new file <strong>new-users.txt</strong> has been generated with the following list, save it somewhere and remove it from the root</p>';

    echo '<div class="row">';

    $salt = $_CONFIG['salt'];

    $_USERS = array();

    $masteradmin = array();
    $masteradmin['name'] = 'admin';
    $masteradmin['pass'] = crypt($salt.urlencode('password'), randomString());
    $masteradmin['role'] = 'superadmin';

    echo '<div class="col-sm-12">';
    echo "<blockquote><strong>name:</strong> ".$masteradmin['name']."<br>";
    echo "<strong>password:</strong> password<br>";
    echo "<strong>role:</strong> superadmin<br></blockquote>";
    echo '</div>';

    array_push($_USERS, $masteradmin);

    for ($number = 0; $number < $howmany ; $number++) {
        $usernew = array();
        $randompass = randomPass($leng);
        $encodedpass = crypt($salt.urlencode($randompass), randomString());
        $usernew['name'] = $basename.$number;
        $usernew['pass'] = $encodedpass;
        $usernew['role'] = $role;

        array_push($_USERS, $usernew);
        ?>

        <div class="col-sm-3">
            <?php
            $txt = "name: ".$usernew['name']."\n";
            fwrite($logfile, $txt);
            $txt = "password: ".$randompass."\n\n";
            fwrite($logfile, $txt);


            echo "<blockquote><strong>name:</strong> ".$usernew['name']."<br>";
            echo "<strong>password:</strong> ".$randompass."<br></blockquote>";
            // echo "<strong>role:</strong> ".$role."<br><br>";
            ?>
        </div>
        <?php

    }
    fclose($logfile);

    echo "</div>";
    updateUsers($_USERS);
    unlink(__FILE__);
}
    ?>

        <form  method="POST">
            <div class="form-group">
                <label>Base name (all usernames will be basename+incremental number)</label>
                <input type="text" class="form-control" name="basename" value="user">
            </div>
            <div class="form-group">
                <label>Number of users to generate</label>
                <input type="number" min="1" class="form-control" name="howmany" value="1">
            </div>
            <div class="form-group">
                <label>Password lenght</label>
                <input type="number" min="1" class="form-control" name="leng" value="6">
            </div>

            <div class="form-group">
            <label>Role</label>
                <select class="form-control" name="role">
                  <option>user</option>
                  <option>admin</option>
                  <option>superadmin</option>
                </select>
            </div>
          
          <button type="submit" class="btn btn-primary btn-block">Generate</button>
        </form>
    </div>
  </body>
</html>