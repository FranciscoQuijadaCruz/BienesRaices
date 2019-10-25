<?php
if(!isset($_SESSION)) {
  session_start();
  if(!isset($_SESSION['userId'])) header("Location: login.php?error=1");
}

include('includes/codigoComun.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>My San Carlos Vacation, San Carlos Property Rentals - Control  panel</title

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


<div class="txt_navbar" id="navbar"><strong>You are here:</strong> <a href="index.php">Home</a> &raquo; Control panel
</div>

<div id="content" class="txt_content">
  <h2>Control panel</h2>
  <p>Use the option below to manage user and settings.</p>
  <?php 
    if(isset($_GET['registerUser'])) printMsg("The user was succesfully registered","exito");
    if(isset($_GET['updatedUser'])) printMsg("The user data was succesfully updated","exito");
    if(isset($_GET['error']) && $_GET['error'] == "2") printMsg("You can't access this resource without logging in first or without the required privileges.","announce");
  ?>

  <ul>
    <li><a href="user_edit.php">Update profile</a></li>
    <li><a href="property_management.php">Manage my properties</a></li>
    <?php if($_SESSION['userLevel'] == "admin") { ?>
    <li><a href="user_admin.php">Manage users</a></li>
    <li><a href="admin_property_management.php">Manage other agents properties</a></li>
    <?php } ?>
    <li><a href="trash.php">Trash</a></li>
  </ul>

  <?php if($_SESSION['userLevel'] == "admin") { ?>
  <h3>User search</h3>

  <p>Use the box below for searching users by name and/or last name.</p>

  <form action="search_user.php" method="get">
    <label for="userSearch">Search criteria</label>
    <input type="search" name="userSearch" />
    <input type="submit" name="sent" value="Search user" />
  </form>
  <?php } ?>

</div>

<!--CONTENT END -->

<?php include('includes/sidebar.php'); ?>
<!-- SIDEBAR END -->
<div style="clear: both;"></div>
<?php include('includes/footer.php'); ?>

</div>

</body>
</html>
