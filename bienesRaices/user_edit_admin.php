<?php
if(!isset($_SESSION)) {
  session_start();
  if(!isset($_SESSION['userId']) || $_SESSION['userLevel'] != "admin") header("Location: cpanel.php?error=2");
}

include('conexiones/conexionLocalhost.php');
include('includes/codigoComun.php');

if(isset($_GET['userId']) && is_numeric($_GET['userId'])) {
  // Obtenemos todos los datos del usuario loggeado
  $queryGetUserDetails = sprintf("SELECT * FROM tblUsuarios WHERE id = %d",
    mysql_real_escape_string(trim($_GET['userId']))
  );

  $resQueryGetUserDetails = mysql_query($queryGetUserDetails, $conexionLocalhost) or trigger_error("User data couldn't be obtained");

  // Evaluo si obtuve resultados con la consulta
  if(mysql_num_rows($resQueryGetUserDetails) == 0) header("Location: user_admin.php?error=4");

  // Hacemos un fetch para extraer los datos del usuario y poder manipularlos
  $userDetails = mysql_fetch_assoc($resQueryGetUserDetails);
}
else {
  header("Location: user_admin.php?error=3");
} 

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

  // Solamente ejecutar la transacción en la base de datos cuando estamos libre de errores
  if(!isset($error)) {

    // Definir el query a ejecutar
    $queryUserEdit = sprintf("UPDATE tblUsuarios SET nombre = '%s', apellidos = '%s', password = '%s', telefono = '%s', rol = '%s' WHERE id = %d",
      mysql_real_escape_string(trim($_POST['nombre'])),
      mysql_real_escape_string(trim($_POST['apellidos'])),
      mysql_real_escape_string(trim($_POST['password'])),
      mysql_real_escape_string(trim($_POST['telefono'])),
      mysql_real_escape_string(trim($_POST['rol'])),
      $_POST['id']
    );

    // Ejecutamos el query
    $resQueryUserEdit = mysql_query($queryUserEdit, $conexionLocalhost) or die("We're sorry but the query for updating user data wasn't executed");

    // Si todo salio bien, redirigimos al usuario al panel de control
    if($resQueryUserEdit) {
      header("Location: cpanel.php?updatedUser=true");
    }

  }

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>My San Carlos Vacation, San Carlos Property Rentals - User edit</title>

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


<div class="txt_navbar" id="navbar"><strong>You are here:</strong> <a href="index.php">Home</a> &raquo; <a href="cpanel.php">Control Panel</a> &raquo; <a href="user_management.php">User management</a> &raquo; User edit
</div>

<div id="content" class="txt_content">
  <h2>User edit</h2>
  <p>Use the form below to edit your info.</p>
  <?php if(isset($error)) printMsg($error,"error"); ?>

  <form action="user_edit_admin.php?userId=<?php echo $userDetails['id'];?>" method="post">
  	<table>
  		<tr>
  			<td><label for="nombre">First name:</label></td>
  			<td><input type="text" name="nombre" value="<?php echo $userDetails['nombre']; ?>" /></td>
  		</tr>
  		<tr>
  			<td><label for="apellidos">Last name:</label></td>
  			<td><input type="text" name="apellidos" value="<?php echo $userDetails['apellidos']; ?>"</td>
  		</tr>
  		<tr>
  			<td><label for="email">Email:</label></td>
  			<td><input type="text" name="email" disabled="disabled" value="<?php echo $userDetails['email']; ?>" /></td>
  		</tr>
  		<tr>
  			<td><label for="telefono">Telephone:</label></td>
  			<td><input type="text" name="telefono" value="<?php echo $userDetails['telefono']; ?>" /></td>
  		</tr>
  		<tr>
  			<td><label for="password">Password:</label></td>
  			<td><input type="password" name="password" value="<?php echo $userDetails['password']; ?>" /></td>
  		</tr>
      <tr>
        <td><label for="password2">Confirm password:</label></td>
        <td><input type="password" name="password2" value="<?php echo $userDetails['password']; ?>" /></td>
      </tr>
      <tr>
        <td><label for="rol">Role:</label></td>
        <td>
          <select name="rol" id="rol">
            <option value="agente" <?php if($userDetails['rol'] == "agente") echo 'selected="selected"'; ?>>Agent</option>
            <option value="admin" <?php if($userDetails['rol'] == "admin") echo 'selected="selected"'; ?>>Administrator</option>
          </select>
        </td>
      </tr>
  		<tr>
  			<td><input type="hidden" name="id" value="<?php echo $userDetails['id']; ?>"/></td>
  			<td><br /><input type="submit" value="Update info" name="sent" /></td>
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