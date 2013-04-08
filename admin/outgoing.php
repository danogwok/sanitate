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

$maxRows_outgoing = 10;
$pageNum_outgoing = 0;
if (isset($_GET['pageNum_outgoing'])) {
  $pageNum_outgoing = $_GET['pageNum_outgoing'];
}
$startRow_outgoing = $pageNum_outgoing * $maxRows_outgoing;

mysql_select_db($database_water, $water);
$query_outgoing = "SELECT * FROM outgoing_sms order by id desc";
$query_limit_outgoing = sprintf("%s LIMIT %d, %d", $query_outgoing, $startRow_outgoing, $maxRows_outgoing);
$outgoing = mysql_query($query_limit_outgoing, $water) or die(mysql_error());
$row_outgoing = mysql_fetch_assoc($outgoing);

if (isset($_GET['totalRows_outgoing'])) {
  $totalRows_outgoing = $_GET['totalRows_outgoing'];
} else {
  $all_outgoing = mysql_query($query_outgoing);
  $totalRows_outgoing = mysql_num_rows($all_outgoing);
}
$totalPages_outgoing = ceil($totalRows_outgoing/$maxRows_outgoing)-1;
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
			<article class="breadcrumbs"><a href="index.php">Website Admin</a> <div class="breadcrumb_divider"></div> <a class="current">Outgoing messages</a></article>
		</div>
	</section>
	
	<!-- end of secondary bar -->
	<?php include('menu.php'); ?>
	<!-- end of sidebar -->
	
	<section id="main" class="column"><!-- end of stats article -->
		
		<article class="module width_3_quarter">
		<header>
		<h3 class="tabs_involved">OUTGOING messages</h3>
		
		</header>

		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
   					<th width="60%">Message</th> 
    				<th width="9%">Phone</th> 
    				<th width="11%">Time sent</th>
    				<th width="7%">Status</th> 
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
			</div><!-- end of #tab1 -->
			
			
			
		</div><!-- end of .tab_container -->
		
		</article><!-- end of content manager article --><!-- end of messages article -->
		
	  <div class="clear"></div><!-- end of post new article --><!-- end of styles article -->
	  <div class="spacer"></div>
	</section>


</body>

</html>
<?php
mysql_free_result($outgoing);
?>
