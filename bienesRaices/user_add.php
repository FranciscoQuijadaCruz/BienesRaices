<?php
if(!isset($_SESSION)) {
  session_start();
  if(!isset($_SESSION['userId']) || $_SESSION['userLevel'] != "admin") header("Location: cpanel.php?error=2");
}

include('conexiones/conexionLocalhost.php');
include('includes/codigoComun.php');

// Evaluamos que el formulario ha sido enviado
if(isset($_POST['sent'])) {

  // Verificamos si existen campos vacios
  foreach($_POST as $calzon => $caca) {
    if($calzon != "telefono") {
      if($caca == "") $error[] = "The field $calzon is required";
    }
  }

  // Verificamos que los password sean coincidentes
  if($_POST['password'] != $_POST['password2']) {
    $error[] = "The password doesn't match";
  }

  // Definir el query para buscar el email en la base de datos
  $queryValidateEmail = sprintf("SELECT id FROM tblUsuarios WHERE email = '%s'",
    mysql_real_escape_string(trim($_POST['email']))
  );

  // Ejecutar el query
  $resQueryValidateEmail = mysql_query($queryValidateEmail, $conexionLocalhost) or die("The query for searching the email wasn't executed");

  // Contamos el numero de registros que devuelve la consulta y en caso de existir un registro generamos un error de email utilizado
  if(mysql_num_rows($resQueryValidateEmail)) $error[] = "The given email is already in use, please use another one";

  // Solamente ejecutar la transacción en la base de datos cuando estamos libre de errores
  if(!isset($error)) {

    // Definir el query a ejecutar
    $queryUserAdd = sprintf("INSERT INTO tblUsuarios (nombre, apellidos, email, password, telefono, rol) VALUES ('%s', '%s', '%s', '%s', '%s', '%s')",
      mysql_real_escape_string(trim($_POST['nombre'])),
      mysql_real_escape_string(trim($_POST['apellidos'])),
      mysql_real_escape_string(trim($_POST['email'])),
      mysql_real_escape_string(trim($_POST['password'])),
      mysql_real_escape_string(trim($_POST['telefono'])),
      mysql_real_escape_string(trim($_POST['rol']))
    );

    // Ejecutamos el query
    $resQueryUserAdd = mysql_query($queryUserAdd, $conexionLocalhost) or die("We're sorry but the query for registering new users wasn't executed");

    // Si todo salio bien, redirigimos al usuario al panel de control
    if($resQueryUserAdd) {
      header("Location: cpanel.php?registerUser=true");
    }

  }

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>My San Carlos Vacation, San Carlos Property Rentals - User add</title>

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


<div class="txt_navbar" id="navbar"><strong>You are here:</strong> <a href="index.php">Home</a> &raquo; <a href="cpanel.php">Control panel</a> &raquo; <a href="user_management.php">User management</a> &raquo; User add
</div>

<div id="content" class="txt_content">
  <h2>User add</h2>
  <p>Use the form below to add a new user.</p>
  <?php if(isset($error)) printMsg($error,"error"); ?>

  <form action="user_add.php" method="post">
  	<table>
  		<tr>
  			<td><label for="nombre">First name:</label></td>
  			<td><input type="text" name="nombre" <?php if(isset($_POST['nombre'])) echo 'value="'.$_POST['nombre'].'"'; ?> /></td>
  		</tr>
  		<tr>
  			<td><label for="apellidos">Last name:</label></td>
  			<td><input type="text" name="apellidos" <?php if(isset($_POST['apellidos'])) echo 'value="'.$_POST['apellidos'].'"'; ?> /></td>
  		</tr>
  		<tr>
  			<td><label for="email">Email:</label></td>
  			<td><input type="text" name="email" <?php if(isset($_POST['email'])) echo 'value="'.$_POST['email'].'"'; ?> /></td>
  		</tr>
  		<tr>
  			<td><label for="telefono">Telephone:</label></td>
  			<td><input type="text" name="telefono" <?php if(isset($_POST['telefono'])) echo 'value="'.$_POST['telefono'].'"'; ?> /></td>
  		</tr>
  		<tr>
  			<td><label for="password">Password:</label></td>
  			<td><input type="password" name="password" /></td>
  		</tr>
      <tr>
        <td><label for="password2">Confirm password:</label></td>
        <td><input type="password" name="password2" /></td>
      </tr>
  		<tr>
  			<td><label for="rol">Role:</label></td>
  			<td>
  				<select name="rol" id="rol">
  					<option value="agent" selected="selected">Agent</option>
  					<option value="admin">Administrator</option>
  				</select>
  			</td>
  		</tr>
  		<tr>
  			<td>&nbsp;</td>
  			<td><br /><input type="submit" value="Register user" name="sent" /></td>
  		</tr>
  	</table>
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
  if(isset($resQueryValidateEmail)) mysql_free_result($resQueryValidateEmail);
  if(isset($resQueryUserAdd)) mysql_free_result($resQueryUserAdd);
?>