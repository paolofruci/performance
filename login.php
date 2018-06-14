<?php
require 'data.php';
$mydb = new db();
$message = '';

//controllo user e passwd da login 
if(isset($_POST['username']) && isset($_POST['password']) && trim($_POST['password']) != '' && trim($_POST['username'])) 
{
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
    /* CONTROLLO WHITELIST */
    
	$login = $mydb->login($username,$password);
	if( $login != false && is_array($login)){
        session_start();
        $_SESSION['user'] = array(
            "username" => $login['utente_name'],
            "userid" => $login['utente_id']
            // "nomecompleto" => $aInfo[0]['displayname'][0],
            // "struttura" => $aInfo[0]['descrizstruttura'][0],
            // "department" => $aInfo[0]['department'][0],
            // "type" => $aInfo[0]['tigemployeetype'][0],
            // "company" => $aInfo[0]['company'][0],
            // "mail" => $aInfo[0]['mail'][0],
            // "profile" => $profile,
        );
        header("Location: index.php");
    } else {
        $message = "<div class='alert alert-danger' role='alert'>Credenziali non valide!</div>";	
    }
}
// else {
//     $message = "<div class='alert alert-danger' role='alert'>Credenziali Incomplete!</div>";
// }

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Signin</title>

    <!-- Bootstrap core CSS -->
    <link href="bootstrap-4.1.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="fontawesome/css/fontawesome-all.min.css" rel="stylesheet">

  </head>

  <body>
        <div class="d-flex justify-content-center mt-4">
            <div class="col-md-4 card shadow">
                <div class="card-body">
                    <form action="login.php" class="form-signin" method="post">
                    <div class="d-flex justify-content-start">
                    <img src='imgs/image.png' style='width:36px;height:36px';>
                      <h1 class="h3 mb-3 font-weight-normal">VMs Performance Manager</h1>
                    </div>
                        <?php if($message) echo $message ?>
                        <label for="username" class="sr-only">Username</label>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Username" required autofocus>
                        <label for="password" class="sr-only">Password</label>
                        <input type="password" name="password" id="password" class="form-control mt-2" placeholder="Password" required>
                        <button class="btn btn-lg btn-primary btn-block mt-4" type="submit">Login</button>
                    </form>
                </div>
            </div>
        </div>
  </body>
</html>
