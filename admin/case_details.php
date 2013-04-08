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

$maxRows_incoming = 10;
$pageNum_incoming = 0;
if (isset($_GET['pageNum_incoming'])) {
  $pageNum_incoming = $_GET['pageNum_incoming'];
}
$startRow_incoming = $pageNum_incoming * $maxRows_incoming;

$caseID_incoming = "0";
if (isset($_GET['caseID'])) {
  $caseID_incoming = $_GET['caseID'];
}
mysql_select_db($database_water, $water);
$query_incoming = sprintf("SELECT * FROM incoming_sms WHERE case_id=%s", GetSQLValueString($caseID_incoming, "int"));
$query_limit_incoming = sprintf("%s LIMIT %d, %d", $query_incoming, $startRow_incoming, $maxRows_incoming);
$incoming = mysql_query($query_limit_incoming, $water) or die(mysql_error());
$row_incoming = mysql_fetch_assoc($incoming);

if (isset($_GET['totalRows_incoming'])) {
  $totalRows_incoming = $_GET['totalRows_incoming'];
} else {
  $all_incoming = mysql_query($query_incoming);
  $totalRows_incoming = mysql_num_rows($all_incoming);
}
$totalPages_incoming = ceil($totalRows_incoming/$maxRows_incoming)-1;

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

$caseID_outgoing = "0";
if (isset($_GET['caseID'])) {
  $caseID_outgoing = $_GET['caseID'];
}
mysql_select_db($database_water, $water);
$query_outgoing = sprintf("SELECT * FROM outgoing_sms WHERE case_id=%s", GetSQLValueString($caseID_outgoing, "int"));
$outgoing = mysql_query($query_outgoing, $water) or die(mysql_error());
$row_outgoing = mysql_fetch_assoc($outgoing);
$totalRows_outgoing = mysql_num_rows($outgoing);

$caseID_caseData = "0";
if (isset($_GET['caseID'])) {
  $caseID_caseData = $_GET['caseID'];
}
mysql_select_db($database_water, $water);
$query_caseData = sprintf("SELECT * FROM `case` where `case`.id = %s", GetSQLValueString($caseID_caseData, "int"));
$caseData = mysql_query($query_caseData, $water) or die(mysql_error());
$row_caseData = mysql_fetch_assoc($caseData);
$totalRows_caseData = mysql_num_rows($caseData);
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
			<a class="current">Case details</a></article>
		</div>
	</section>
	
	<!-- end of secondary bar -->
	<?php include('menu.php'); ?>
	<!-- end of sidebar -->
	
	<section id="main" class="column"><!-- end of stats article -->
    <article class="module width_3_quarter" style="width:92%">
		<header>
		<h3 class="tabs_involved">Case messages</h3>
		<ul class="tabs">
   			<li><a href="#tab1">Incoming</a></li>
    		<li><a href="#tab2"> Outgoing</a></li>
		</ul>
		</header>

		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr>
				  <th width="43%" height="18">Message</th> 
    				<th width="13%">Phone</th> 
    				<th width="17%">Received</th>
    				<th width="12%">Status</th> 
    				<th width="15%">Actions</th> 
				</tr> 
			</thead> 
			<tbody> 
				<?php do { ?>
				  <tr>
				    <td><?php echo $row_incoming['message']; ?></td>
				    <td><?php echo $row_incoming['number']; ?></td>
				    <td><?php echo $row_incoming['time_received']; ?></td>
				    <td><?php echo $row_incoming['status']; ?></td>
				    <td><a href="sms_send.php?caseID=<?php echo $row_caseData['id']; ?>&msgID=<?php echo $row_incoming['id']; ?>" title="Reply"><img src="images/icn_jump_back.png" ></a>&nbsp; <a href="#?caseID=<?php echo $row_caseData['id']; ?>" title="Un-assign from case"><img src="images/icn_trash.png" ></a></td>
			    </tr>
				  <?php } while ($row_incoming = mysql_fetch_assoc($incoming)); ?>
            </tbody> 
			</table>
			</div><!-- end of #tab1 -->
			
		  <div id="tab2" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
   					<th width="59%">Message</th> 
    				<th width="13%">Phone</th> 
    				<th width="17%">TIme Sent </th>
    				<th width="11%">Status</th> 
			  </tr> 
			</thead> 
			<tbody>
              <?php do { ?>
              <tr>
                <td><?php echo $row_outgoing['message']; ?></td>
                <td><?php echo $row_outgoing['number']; ?></td>
                <td><?php echo $row_outgoing['time_received']; ?></td>
                <td>Sent</td>
              </tr>
                <?php } while ($row_outgoing = mysql_fetch_assoc($outgoing)); ?>
            </tbody> 
			</table>

		  </div><!-- end of #tab2 -->
			
	  </div><!-- end of .tab_container -->
		
		</article>
         <div class="clear"></div>
	  <form method="POST" name="newCat" id="newCat">
		<article class="module width_full">
			<header>
			  <h3>Create new case</h3></header>
				<div class="module_content">
						<fieldset>
							<label>Case  Title</label>
							<input name="title" type="text" id="title" value="<?php echo $row_caseData['title']; ?>">
						</fieldset>
                        <fieldset>
							<label>Case  Description</label>
							<textarea name="description" rows="12"><?php echo $row_caseData['description']; ?></textarea>
						</fieldset>
                        <fieldset>
							<label>Location description</label>
							<textarea name="location_attribute" rows="12"><?php echo $row_caseData['location_attribute']; ?></textarea>
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
							  <option value="<?php echo $row_locations['id']?>"<?php if (!(strcmp($row_locations['id'], $row_caseData['location_id']))) {echo "selected=\"selected\"";} ?>><?php echo $row_locations['parish']?>, <?php echo $row_locations['district']?></option>
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
							<label>Status</label>
							<select name="status" id="status" style="width:92%;">
							  <option value="Resolved" <?php if (!(strcmp("Resolved", $row_caseData['status']))) {echo "selected=\"selected\"";} ?>>Resolved</option>
							  <option value="Pending" <?php if (!(strcmp("Pending", $row_caseData['status']))) {echo "selected=\"selected\"";} ?>>Pending</option>
							  
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

mysql_free_result($outgoing);

mysql_free_result($caseData);
?>
