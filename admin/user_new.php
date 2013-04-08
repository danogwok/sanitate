<?php require_once('Connections/water.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "new_user")) {
  $insertSQL = sprintf("INSERT INTO `user` (username, password, account_type, email, name, phone) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['username'], "text"),
                       GetSQLValueString($_POST['password'], "text"),
                       GetSQLValueString($_POST['type'], "text"),
                       GetSQLValueString($_POST['email'], "text"),
                       GetSQLValueString($_POST['name'], "text"),
                       GetSQLValueString($_POST['phone'], "text"));

  mysql_select_db($database_water, $water);
  $Result1 = mysql_query($insertSQL, $water) or die(mysql_error());

  $insertGoTo = "users.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8"/>
	<title>Sanitate Dashboard</title>
	
	<link rel="stylesheet" href="css/layout.css" type="text/css" media="screen" />
	<!--[if lt IE 9]>
	<link rel="stylesheet" href="css/ie.css" type="text/css" media="screen" />
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<script src="js/jquery-1.5.2.min.js" type="text/javascript"></script>
	<script src="js/hideshow.js" type="text/javascript"></script>
	<script src="js/jquery.tablesorter.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="js/jquery.equalHeight.js"></script>
	<script src="SpryAssets/SpryValidationConfirm.js" type="text/javascript"></script>
	<script type="text/javascript">
	$(document).ready(function() 
    	{ 
      	  $(".tablesorter").tablesorter(); 
   	 } 
	);
	$(document).ready(function() {

	//When page loads...
	$(".tab_content").hide(); //Hide all content
	$("ul.tabs li:first").addClass("active").show(); //Activate first tab
	$(".tab_content:first").show(); //Show first tab content

	//On Click Event
	$("ul.tabs li").click(function() {

		$("ul.tabs li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tab_content").hide(); //Hide all tab content

		var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active ID content
		return false;
	});

});
    </script>
    <script type="text/javascript">
    $(function(){
        $('.column').equalHeight();
    });
</script>

<link href="SpryAssets/SpryValidationConfirm.css" rel="stylesheet" type="text/css">
</head>


<body>
	<?php include('header.php'); ?>
	 <!-- end of header bar -->
     <section id="secondary_bar">
		<?php include('notifications.php'); ?>
		<div class="breadcrumbs_container">
			<article class="breadcrumbs"><a href="index.php">Website Admin</a> <div class="breadcrumb_divider"></div> 
			<a class="current">New User</a></article>
		</div>
	</section>
	
	<!-- end of secondary bar -->
	<?php include('menu.php'); ?>
	<!-- end of sidebar -->
	
	<section id="main" class="column"><!-- end of stats article -->
		<form action="<?php echo $editFormAction; ?>" method="POST" name="new_user" id="new_user">
		<article class="module width_full">
			<header>
			  <h3>Create new User</h3></header>
				<div class="module_content">
				  <fieldset>
				    <label>Username</label>
				    <input name="username" type="text" id="username">
			      </fieldset>
                  <fieldset style="width:48%; float:left; margin-right: 3%;">
				    <label>Password</label>
				    <input name="password" type="password" id="password">
				    
                  </fieldset>
                  <fieldset style="width:48%; float:left; margin-right: 3%;">
				    <label>Re-type password</label>
				    <span id="spryconfirm2">
				    <input name="password2" type="password" id="password2">
				    <span class="confirmRequiredMsg">A value is required.</span><span class="confirmInvalidMsg">The values don't match.</span></span>
                  </fieldset>
                  <fieldset style="width:48%; margin-right: 3%;"> <!-- to make two field float next to one another, adjust values accordingly -->
					<label>User type</label>
							<select name="type" id="type" style="width:92%;">
							  <option value="ADMIN">Admin</option>
							  <option value="USER" selected>User</option>
                            </select>
				  </fieldset>
                  <fieldset>
				    <label>Name/Organization</label>
				    <input name="name" type="text" id="name">
			      </fieldset>
                  <fieldset>
				    <label>email</label>
				    <input name="email" type="text" id="email">
			      </fieldset>
                  <fieldset>
				    <label>phone</label>
				    <input name="phone" type="text" id="phone">
			      </fieldset>
                 
				  <div class="clear"></div>
				</div>
			<footer>
				<div class="submit_link">
					<input type="submit" value="Save" class="alt_btn">
					<input name="Reset" type="reset" value="Reset">
				</div>
			</footer>
		</article>
		<input type="hidden" name="MM_insert" value="new_user">
      </form>
		<!-- end of content manager article --><!-- end of messages article -->
		
	  <div class="clear"></div><!-- end of post new article --><!-- end of styles article -->
	  <div class="spacer"></div>
	</section>
<script type="text/javascript">
var spryconfirm2 = new Spry.Widget.ValidationConfirm("spryconfirm2", "password", {validateOn:["blur"]});
</script>
</body>

</html>