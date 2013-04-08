<?php require_once('Connections/water.php'); ?>
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
  $insertSQL = sprintf("INSERT INTO `case` (location_attribute, location_id, user_id, category_id, title, `description`) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['location_attribute'], "text"),
                       GetSQLValueString($_POST['location_id'], "int"),
                       GetSQLValueString($_POST['user_id'], "int"),
                       GetSQLValueString($_POST['category_id'], "int"),
                       GetSQLValueString($_POST['title'], "text"),
                       GetSQLValueString($_POST['description'], "text")); echo $insertSQL;

  mysql_select_db($database_water, $water);
  $Result1 = mysql_query($insertSQL, $water) or die(mysql_error());
  $id = mysql_insert_id();

  $insertGoTo = "case_details.php?caseID=$id&msgID=" . $_GET['sms_id'];
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

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

$smsId_incoming = "0";
if (isset($_GET['sms_id'])) {
  $smsId_incoming = $_GET['sms_id'];
}
mysql_select_db($database_water, $water);
$query_incoming = sprintf("SELECT * FROM incoming_sms WHERE incoming_sms.id= %s", GetSQLValueString($smsId_incoming, "int"));
$incoming = mysql_query($query_incoming, $water) or die(mysql_error());
$row_incoming = mysql_fetch_assoc($incoming);
$totalRows_incoming = mysql_num_rows($incoming);

mysql_select_db($database_water, $water);
$query_cats = "SELECT * FROM categories";
$cats = mysql_query($query_cats, $water) or die(mysql_error());
$row_cats = mysql_fetch_assoc($cats);
$totalRows_cats = mysql_num_rows($cats);

mysql_select_db($database_water, $water);
$query_users = "SELECT * FROM `user`";
$users = mysql_query($query_users, $water) or die(mysql_error());
$row_users = mysql_fetch_assoc($users);
$totalRows_users = mysql_num_rows($users);

mysql_select_db($database_water, $water);
$query_locations = "SELECT * FROM location";
$locations = mysql_query($query_locations, $water) or die(mysql_error());
$row_locations = mysql_fetch_assoc($locations);
$totalRows_locations = mysql_num_rows($locations);
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
			<a class="current">Case edit</a></article>
		</div>
	</section>
	
	<!-- end of secondary bar -->
	<?php include('menu.php'); ?>
	<!-- end of sidebar -->
	
	<section id="main" class="column"><!-- end of stats article -->
    <article class="module width_3_quarter">
		<header>
		<h3 class="tabs_involved">Original message</h3>
		
		</header>

		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
   					<th width="60%">Message</th> 
    				<th width="9%">Phone</th> 
    				<th width="11%">Received</th>
    				<th width="7%">Status</th> 
   				</tr> 
			</thead> 
			<tbody> 
				
			    <tr> 
				    <td><?php echo $row_incoming['message']; ?></td> 
				    <td><?php echo $row_incoming['number']; ?></td> 
				    <td><?php echo $row_incoming['time_received']; ?></td>
				    <td><?php echo $row_incoming['status']; ?></td> 
		      </tr>
				    
			</tbody> 
			</table>
			</div><!-- end of #tab1 -->
			
			
			
		</div><!-- end of .tab_container -->
		
		</article>
         <div class="clear"></div>
	  <form action="<?php echo $editFormAction; ?>" method="POST" name="newCat" id="newCat">
		<article class="module width_full">
			<header>
			  <h3>Create new case</h3></header>
				<div class="module_content">
						<fieldset>
							<label>Case  Title</label>
							<input name="title" type="text" id="title">
						</fieldset>
                        <fieldset>
							<label>Case  Description</label>
							<textarea name="description" rows="12"></textarea>
						</fieldset>
                        <fieldset>
							<label>Location description</label>
							<textarea name="location_attribute" rows="12"></textarea>
						</fieldset>
                        <fieldset style="width:40%; float: left; margin-right: 3%;">
							<label>Category</label>
							<select name="category_id" id="category_id" style="width:92%;">
							  <?php
do {  
?>
							  <option value="<?php echo $row_cats['id']?>"><?php echo $row_cats['name']?></option>
							  <?php
} while ($row_cats = mysql_fetch_assoc($cats));
  $rows = mysql_num_rows($cats);
  if($rows > 0) {
      mysql_data_seek($cats, 0);
	  $row_cats = mysql_fetch_assoc($cats);
  }
?>
                          </select>
				  		</fieldset>
                         <fieldset style="width:40%; float: left; margin-right: 3%;">
							<label>Location</label>
							<select name="location_id" id="location_id" style="width:92%;">
							  <?php
do {  
?>
							  <option value="<?php echo $row_locations['id']?>"><?php echo $row_locations['parish']?></option>
							  <?php
} while ($row_locations = mysql_fetch_assoc($locations));
  $rows = mysql_num_rows($locations);
  if($rows > 0) {
      mysql_data_seek($locations, 0);
	  $row_locations = mysql_fetch_assoc($locations);
  }
?>
                           </select>
				  		</fieldset>
                         <fieldset style="width:40%; margin-right: 3%;">
							<label>Assign</label>
							<select name="user_id" id="user_id" style="width:92%;">
							  <?php
do {  
?>
							  <option value="<?php echo $row_users['id']?>"><?php echo $row_users['name']?></option>
							  <?php
} while ($row_users = mysql_fetch_assoc($users));
  $rows = mysql_num_rows($users);
  if($rows > 0) {
      mysql_data_seek($users, 0);
	  $row_users = mysql_fetch_assoc($users);
  }
?>
                           </select>
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
		<input type="hidden" name="MM_insert" value="newCat">
      </form>
		<!-- end of content manager article --><!-- end of messages article -->
        
		
	  <div class="clear"></div><!-- end of post new article --><!-- end of styles article -->
	  <div class="spacer"></div>
	</section>


</body>

</html>
<?php
mysql_free_result($incoming);

mysql_free_result($cats);

mysql_free_result($users);

mysql_free_result($locations);
?>
