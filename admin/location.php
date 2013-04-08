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

if ((isset($_GET['trash'])) && ($_GET['trash'] != "")) {
  $deleteSQL = sprintf("DELETE FROM location WHERE id=%s",
                       GetSQLValueString($_GET['trash'], "int"));

  mysql_select_db($database_water, $water);
  $Result1 = mysql_query($deleteSQL, $water) or die(mysql_error());

  $deleteGoTo = "location.php?i=Deleted";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?"; //echo $deleteGoTo;
	str_replace('trash', 'trashed', $_SERVER['QUERY_STRING']);
    $deleteGoTo .= str_replace('trash', 'trashed', $_SERVER['QUERY_STRING']); //echo $deleteGoTo; die;
  }
  header(sprintf("Location: %s", $deleteGoTo));
}

$maxRows_locations = 10;
$pageNum_locations = 0;
if (isset($_GET['pageNum_locations'])) {
  $pageNum_locations = $_GET['pageNum_locations'];
}
$startRow_locations = $pageNum_locations * $maxRows_locations;

mysql_select_db($database_water, $water);
$query_locations = "SELECT id, district, parish FROM location";
$query_limit_locations = sprintf("%s LIMIT %d, %d", $query_locations, $startRow_locations, $maxRows_locations);
$locations = mysql_query($query_limit_locations, $water) or die(mysql_error());
$row_locations = mysql_fetch_assoc($locations);

if (isset($_GET['totalRows_locations'])) {
  $totalRows_locations = $_GET['totalRows_locations'];
} else {
  $all_locations = mysql_query($query_locations);
  $totalRows_locations = mysql_num_rows($all_locations);
}
$totalPages_locations = ceil($totalRows_locations/$maxRows_locations)-1;
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
			<article class="breadcrumbs"><a href="index.php">Website Admin</a> <div class="breadcrumb_divider"></div> <a class="current">Locations</a></article>
		</div>
	</section>
	
	<!-- end of secondary bar -->
	<?php include('menu.php'); ?>
	<!-- end of sidebar -->
	
	<section id="main" class="column">
		
		
		
		<article class="module width_3_quarter">
		<header>
		<h3 class="tabs_involved">LOCATIONS</h3>
		
		</header>

		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
   					<th width="9%">ID </th> 
    				<th width="12%">DISTRICT</th>
    				<th width="70%">PARISH</th>
    				<th width="9%">Actions</th> 
				</tr> 
			</thead> 
			<tbody> 
				 
   					<?php do { ?><tr>
				    <td><?php echo $row_locations['id']; ?></td> 
   					  <td><?php echo $row_locations['district']; ?></td>
   					  <td><?php echo $row_locations['parish']; ?></td>
   					  <td>
                      <a href="<?php echo $_SERVER['PHP_SELF']; ?>?edit=<?php echo $row_locations['id']; ?>" title="Edit"><img src="images/icn_edit.png" ></a>
                      &nbsp; 
                      <a href="<?php echo $_SERVER['PHP_SELF']; ?>?trash=<?php echo $row_locations['id']; ?>" title="Trash"><img src="images/icn_trash.png" ></a>
                      </td></tr>
   					  <?php } while ($row_locations = mysql_fetch_assoc($locations)); ?>
                  
			</tbody> 
			</table>
			</div><!-- end of #tab1 -->
			
			
			
		</div><!-- end of .tab_container -->
		
		</article><!-- end of content manager article --><!-- end of messages article -->
		
	  <div class="clear"></div><!-- end of post new article --><!-- end of styles article -->
	  <div class="spacer"></div>
	</section>


</body>

</html>
<?php
mysql_free_result($locations);
?>
