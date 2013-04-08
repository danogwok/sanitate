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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "newCat")) {
  $insertSQL = sprintf("INSERT INTO outgoing_sms (`number`, message, case_id) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['phone'], "text"),
                       GetSQLValueString($_POST['message'], "text"),
                       GetSQLValueString($_POST['case_id'], "int"));

  mysql_select_db($database_water, $water);
  $Result1 = mysql_query($insertSQL, $water) or die(mysql_error());

  $insertGoTo = "case_details.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$msgID_msg = "0";
if (isset($_GET['msgID'])) {
  $msgID_msg = $_GET['msgID'];
}
mysql_select_db($database_water, $water);
$query_msg = sprintf("SELECT * FROM incoming_sms WHERE incoming_sms.id=%s", GetSQLValueString($msgID_msg, "int"));
$msg = mysql_query($query_msg, $water) or die(mysql_error());
$row_msg = mysql_fetch_assoc($msg);
$totalRows_msg = mysql_num_rows($msg);
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

</head>


<body>
	<?php include('header.php'); ?>
	 <!-- end of header bar -->
     <section id="secondary_bar">
		<?php include('notifications.php'); ?>
		<div class="breadcrumbs_container">
			<article class="breadcrumbs"><a href="index.php">Website Admin</a> <div class="breadcrumb_divider"></div> 
			<a class="current">Reply SMS</a></article>
		</div>
	</section>
	
	<!-- end of secondary bar -->
	<?php include('menu.php'); ?>
	<!-- end of sidebar -->
	
	<section id="main" class="column"><!-- end of stats article -->
		<form action="<?php echo $editFormAction; ?>" method="POST" name="newCat" id="newCat">
	  
<article class="module width_full">
	  <header>
	    <h3>Reply SMS</h3></header>
		  <div class="module_content">
				  <fieldset>
					  <label>Message from <?php echo $row_msg['number']; ?></label>
					  <textarea name="message" rows="12" disabled readonly><?php echo $row_msg['message']; ?></textarea>
				  </fieldset>
				  <fieldset>
					  <label>Compose</label>
					  <textarea name="message" rows="12"></textarea>
				  </fieldset>
                  <input name="phone" type="hidden" value="<?php echo $row_msg['number']; ?>">
                  <input name="case_id" type="hidden" id="case_id" value="<?php echo $_GET['caseID']; ?>">
<div class="clear"></div>
		  </div>
	  <footer>
		  <div class="submit_link">
			  <input type="submit" value="Send" class="alt_btn">
			  <input name="Reset" type="reset" value="Reset">
		  </div>
	  </footer>
	</article><!-- end of content manager article --><!-- end of messages article -->
	<input type="hidden" name="MM_insert" value="newCat">
        </form>
	  <div class="clear"></div><!-- end of post new article --><!-- end of styles article -->
	  <div class="spacer"></div>
	</section>


</body>

</html>
<?php
mysql_free_result($msg);
?>
