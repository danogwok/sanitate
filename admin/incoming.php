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

mysql_select_db($database_water, $water);
$query_unread = "SELECT count(*) FROM incoming_sms where status = 'Unread'";
$unread = mysql_query($query_unread, $water) or die(mysql_error());
$row_unread = mysql_fetch_assoc($unread);
$totalRows_unread = mysql_num_rows($unread);

$maxRows_incoming = 10;
$pageNum_incoming = 0;
if (isset($_GET['pageNum_incoming'])) {
  $pageNum_incoming = $_GET['pageNum_incoming'];
}
$startRow_incoming = $pageNum_incoming * $maxRows_incoming;

mysql_select_db($database_water, $water);
$query_incoming = "SELECT * FROM incoming_sms order by id desc";
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
			<a class="current">Incoming Messages</a></article>
		</div>
	</section>
	
	<!-- end of secondary bar -->
	<?php include('menu.php'); ?>
	<!-- end of sidebar -->
	
	<section id="main" class="column">
		
		<h4 class="alert_info"><?php echo $row_unread['count(*)']; ?> new messages</h4><!-- end of stats article -->
		
		<article class="module width_3_quarter">
		<header>
		<h3 class="tabs_involved">incoming messages</h3>
		
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
    				<th width="9%">Actions</th> 
				</tr> 
			</thead> 
			<tbody> 
				<?php do { ?>
			    <tr> 
				    <td><?php echo $row_incoming['message']; ?></td> 
				    <td><?php echo $row_incoming['number']; ?></td> 
				    <td><?php echo $row_incoming['time_received']; ?></td>
				    <td><?php echo $row_incoming['status']; ?></td> 
				    <td><a href="case_new.php?sms_id=<?php echo $row_incoming['id']; ?>" title="Create new case"><img src="images/icn_new_article.png" ></a>&nbsp;
                    <a href="case_details.php?trash=<?php echo $row_locations['id']; ?>" title="Assign to case"><img src="images/icn_tags.png" ></a>&nbsp;
                    <a href="case_details.php?trash=<?php echo $row_locations['id']; ?>" title="Trash"><img src="images/icn_trash.png" ></a>&nbsp;</td> 
			      </tr>
				  <?php } while ($row_incoming = mysql_fetch_assoc($incoming)); ?>  
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
mysql_free_result($unread);

mysql_free_result($incoming);
?>
