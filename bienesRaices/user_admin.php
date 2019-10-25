<?php
if(!isset($_SESSION)) {
  session_start();
  if(!isset($_SESSION['userId']) || $_SESSION['userLevel'] != "admin") header("Location: cpanel.php?error=2");
}

include('conexiones/conexionLocalhost.php');
include('includes/codigoComun.php');

// Obtenemos todos los usuarios de la base de datos
$queryGetUsers = "SELECT id, nombre, apellidos, email FROM tblUsuarios";

// Ejecutamos el query
$resQueryGetUsers = mysql_query($queryGetUsers, $conexionLocalhost) or triggger_error("The query for obtaining all users couldn't be executed.");

// Extraemos del recordset los datos del primer registro
$userDetail = mysql_fetch_assoc($resQueryGetUsers);

// Obtenemos el total de usuarios encontrados
$totalUsers = mysql_num_rows($resQueryGetUsers);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>My San Carlos Vacation, San Carlos Property Rentals - User management</title

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


<div class="txt_navbar" id="navbar"><strong>You are here:</strong> <a href="index.php">Home</a> &raquo; <a href="user_admin.php">Control panel</a> &raquo; User management
</div>

<div id="content" class="txt_content">
  <h2>User management</h2>

  <p>Use the option below to manage users.</p>

  <?php
    if(isset($_GET['error']) && $_GET['error'] == "3") printMsg("The user identifying parameter is incorrect.", "error");
    if(isset($_GET['updatedUser'])) printMsg("The user was succesfully updated", "exito");
    if(isset($_GET['deleteUser'])) printMsg("The user was succesfully deleted", "exito");
  ?>

  <p>There are <?php echo $totalUsers;?> registered users.</p>

  <ul class="listadoUsuarios">
  <?php
    do { ?>  
    <li>
      <p class="nombreUsuario"><?php echo $userDetail['nombre'].' '.$userDetail['apellidos'].' - '.$userDetail['email']; ?></p>
      <p class="accionesUsuario"><a href="user_edit_admin.php?userId=<?php echo $userDetail['id'];?>">Edit</a> | <a href="user_delete.php?userId=<?php echo $userDetail['id'];?>">Delete</a></p>
    </li>
  <?php } while($userDetail = mysql_fetch_assoc($resQueryGetUsers)); ?>
  </ul>
  

</div>

<!--CONTENT END -->

<?php include('includes/sidebar.php'); ?>
<!-- SIDEBAR END -->
<div style="clear: both;"></div>
<?php include('includes/footer.php'); ?>

</div>

</body>
</html>
