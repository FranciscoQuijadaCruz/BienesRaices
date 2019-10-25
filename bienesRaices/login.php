<?php
if(!isset($_SESSION)) {
  session_start();
  if(isset($_SESSION['userId'])) header("Location: cpanel.php");
}

include('conexiones/conexionLocalhost.php');
include('includes/codigoComun.php');

// Evaluamos si el formulario ha sido enviado
if(isset($_POST['sent'])) {
  // Validación de campos vacios
  foreach ($_POST as $calzon => $caca) {
    if($caca == "") $error[] = "The field $calzon is required";
  }

  // Si no hay error, procedemos a definir el query y ejecutarlo
  if(!isset($error)) {
  	$queryValidateUser = sprintf("SELECT id, email, nombre, apellidos, rol FROM tblUsuarios WHERE email = '%s' AND password = '%s'",
  			
        mysql_real_escape_string(trim($_POST['email'])),
  			mysql_real_escape_string(trim($_POST['password']))
        
  	);

  	// Ejecutar el query
  	$resQueryValidateUser = mysql_query($queryValidateUser, $conexionLocalhost) or die("The query for validating the user couldn't be executed");

    // Contamos los resultados obtenidos, 0 = no hay registro que cumpla con los criterios email y ó password; 1 = se encontró unj registro que satisface ambos criterios
  	if(mysql_num_rows($resQueryValidateUser)) {
      $userData = mysql_fetch_assoc($resQueryValidateUser);
      $_SESSION['userId'] = $userData['id'];
      $_SESSION['userEmail'] = $userData['email'];
      $_SESSION['userFullname'] = $userData['nombre']." ".$userData['apellidos'];
      $_SESSION['userLevel'] = $userData['rol'];
      header("Location: cpanel.php?login=true");
  	}
    else {
      $error[] = "The user email/password didn't match... please check your credentials and try again.";
    }
  }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>My San Carlos Vacation, San Carlos Property Rentals - Login</title

<link href='http://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' type='text/css' />

<link href="css_main.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
<!--
function MM_jumpMenuGo(objId,targ,restore){ //v9.0
  var selObj = null;  with (document) { 
  if (getElementById) selObj = getElementById(objId);
  if (selObj) eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0; }
}
//-->
</script>
</head>

<body>
<div id="main">

<?php include('includes/header.php'); ?>
<!-- HEADER END -->


<div class="txt_navbar" id="navbar"><strong>You are here:</strong> <a href="index.php">Home</a> &raquo; Login
</div>

<div id="content" class="txt_content">
  <h2>Login</h2>
  <p>Use your email and password to login</p>
  <?php 
    if(isset($error)) printMsg($error,"error");
    if(isset($_GET['error']) && $_GET['error'] == "1" ) printMsg("You can't access this resource without logging in first.","announce");
    if(isset($_GET['loggedOut']) && $_GET['loggedOut'] == "true" ) printMsg("Thank you for using our app... come back soon :)","exito");
  ?>

  <form action="login.php" method="post">
  	<label for="email">Email:</label>
  	<input type="text" name="email" />
  	<br /><br />

  	<label for="password">Password:</label>
  	<input type="password" name="password" />
  	<br /><br />

  	<input type="submit" name="sent" value="Login" />
  </form>

</div>

<!--CONTENT END -->

<?php include('includes/sidebar.php'); ?>
<!-- SIDEBAR END -->
<div style="clear: both;"></div>
<?php include('includes/footer.php'); ?>

</div>

</body>
</html>
<?php
  if(isset($resQueryValidateUser)) mysql_free_result($resQueryValidateUser);
?>