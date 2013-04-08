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

$maxRows_cases = 10;
$pageNum_cases = 0;
if (isset($_GET['pageNum_cases'])) {
  $pageNum_cases = $_GET['pageNum_cases'];
}
$startRow_cases = $pageNum_cases * $maxRows_cases;

mysql_select_db($database_water, $water);
$query_cases = "SELECT case.id, parish, district, `categories`.name as catname, case.status, `user`.name as fullname, case.title, case.description FROM `case` left join categories on case.category_id = categories.id left join location on case.location_id=location.id left join user on case.user_id=user.id ";
$query_limit_cases = sprintf("%s LIMIT %d, %d", $query_cases, $startRow_cases, $maxRows_cases);
$cases = mysql_query($query_limit_cases, $water) or die(mysql_error());
$row_cases = mysql_fetch_assoc($cases);

if (isset($_GET['totalRows_cases'])) {
  $totalRows_cases = $_GET['totalRows_cases'];
} else {
  $all_cases = mysql_query($query_cases);
  $totalRows_cases = mysql_num_rows($all_cases);
}
$totalPages_cases = ceil($totalRows_cases/$maxRows_cases)-1;

$maxRows_msg = 10;
$pageNum_msg = 0;
if (isset($_GET['pageNum_msg'])) {
  $pageNum_msg = $_GET['pageNum_msg'];
}
$startRow_msg = $pageNum_msg * $maxRows_msg;

mysql_select_db($database_water, $water);
$query_msg = "SELECT incoming_sms.message, incoming_sms.`number`, incoming_sms.time_received FROM incoming_sms WHERE incoming_sms.status = 'Unread'";
$query_limit_msg = sprintf("%s LIMIT %d, %d", $query_msg, $startRow_msg, $maxRows_msg);
$msg = mysql_query($query_limit_msg, $water) or die(mysql_error());
$row_msg = mysql_fetch_assoc($msg);

if (isset($_GET['totalRows_msg'])) {
  $totalRows_msg = $_GET['totalRows_msg'];
} else {
  $all_msg = mysql_query($query_msg);
  $totalRows_msg = mysql_num_rows($all_msg);
}
$totalPages_msg = ceil($totalRows_msg/$maxRows_msg)-1;

$recdate_msgNo = date('Y-m-d');
if (isset($recdate_msgNo)) {
  $recdate_msgNo = date('Y-m-d');
}
mysql_select_db($database_water, $water);
$query_msgNo = sprintf("SELECT count(*) FROM incoming_sms WHERE incoming_sms.time_received = %s", GetSQLValueString($recdate_msgNo, "int"));
$msgNo = mysql_query($query_msgNo, $water) or die(mysql_error());
$row_msgNo = mysql_fetch_assoc($msgNo);
$totalRows_msgNo = mysql_num_rows($msgNo);

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
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load('visualization', '1', {packages: ['table', 'columnchart']});
    </script>
    <script type="text/javascript">

      function drawVisualization() {
        var dataTable = google.visualization.arrayToDataTable([
          ['Name',   'Number of cases', 'Instrument', 'Color'],
          ['Wandegeya',   9,     'Guitar',    'Blue'],
          ['Kamokya',   4,     'Sitar',     'Red'],
          ['Katanga', 5,     'Guitar',    'Green'],
          ['Kivulu',  2,     'Drums',     'White']
        ]);
      
        
      
        var dataView = new google.visualization.DataView(dataTable);
        dataView.setColumns([0, 1]);
      
        var chart = new google.visualization.ColumnChart(document.getElementById('chart'));
        chart.draw(dataView, {width: 400, height: 200});
      }
      
      

      google.setOnLoadCallback(drawVisualization);
    </script>


</head>


<body>
<?php include('header.php'); ?>
<!-- end of header bar -->
     <section id="secondary_bar">
	   <?php include('notifications.php'); ?>
		<div class="breadcrumbs_container">
			<article class="breadcrumbs"><a href="index.php">Website Admin</a> <div class="breadcrumb_divider"></div> 
			<a class="current">Home</a></article>
		</div>
	</section>
	
<?php include('menu.php'); ?>
<!-- end of sidebar -->
	
	<section id="main" class="column">
		
		<h4 class="alert_info"><?php echo $row_msgNo['count(*)']; ?> new messages</h4>
		
		<article class="module width_full">
			<header><h3>Stats</h3></header>
			<div class="module_content">
				<article class="stats_graph">
					<div id="chart"></div>
				</article>
				
				<article class="stats_overview">
					<div class="overview_today">
						<p class="overview_day">Today</p>
						<p class="overview_count">12</p>
						<p class="overview_type">messages</p>
						<p class="overview_count">2</p>
						<p class="overview_type">cases</p>
					</div>
					<div class="overview_previous">
						<p class="overview_day">Yesterday</p>
						<p class="overview_count">7</p>
						<p class="overview_type">messages</p>
						<p class="overview_count">1</p>
						<p class="overview_type">cases</p>
					</div>
				</article>
				<div class="clear"></div>
			</div>
		</article><!-- end of stats article -->
		
		<article class="module width_3_quarter" style="width:66%">
		<header>
		<h3 class="tabs_involved">Latest updateS</h3>
		<ul class="tabs">
   			<li><a href="#tab1">Cases</a></li>
    		<li><a href="#tab2"> Messages</a></li>
		</ul>
		</header>

		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr>
				  <th width="11%">ID</th> 
   					<th width="12%">Case title</th> 
    				<th width="17%">Category</th> 
    				<th width="18%">Location</th>
    				<th width="17%">Assigned</th>
    				<th width="15%">Status</th> 
    				<th width="10%">Actions</th> 
				</tr> 
			</thead> 
			<tbody> 
				
				  <?php do { ?><tr>
			      <td><?php echo $row_cases['id']; ?></td> 
				    <td><?php echo $row_cases['title']; ?></td> 
				    <td><?php echo $row_cases['catname']; ?></td> 
				    <td><?php echo $row_cases['parish']; ?>, <?php echo $row_cases['district']; ?></td>
				    <td><?php echo $row_cases['fullname']; ?></td>
				    <td><?php echo $row_cases['status']; ?></td> 
				    <td><input type="image" src="images/icn_edit.png" title="Edit"><input type="image" src="images/icn_trash.png" title="Trash"></td>
				     </tr> 
					<?php } while ($row_cases = mysql_fetch_assoc($cases)); ?>
                
			</tbody> 
			</table>
			</div><!-- end of #tab1 -->
			
			<div id="tab2" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
   					<th width="37%">Message</th> 
    				<th width="25%">Phone</th> 
    				<th width="27%">Received On</th> 
    				<th width="11%">Actions</th> 
				</tr> 
			</thead> 
			<tbody> 
				
   					<?php do { ?>
                    <tr> 
				      <td><?php echo $row_msg['message']; ?></td> 
   					  <td><?php echo $row_msg['number']; ?></td> 
   					  <td><?php echo $row_msg['time_received']; ?></td> 
   					  <td><input type="image" src="images/icn_edit.png" title="Edit"><input type="image" src="images/icn_trash.png" title="Trash"></td>
                     </tr> 
   					  <?php } while ($row_msg = mysql_fetch_assoc($msg)); ?> 
			</tbody> 
			</table>

			</div><!-- end of #tab2 -->
			
		</div><!-- end of .tab_container -->
		
		</article><!-- end of content manager article -->
		
		<article class="module width_quarter">
			<header>
			  <h3>internal Messages </h3></header>
			<div class="message_list">
				<div class="module_content">
					<div class="message"><p>Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor.</p>
					<p><strong>KCCA</strong></p></div>
					<div class="message"><p>Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor.</p>
					<p><strong>NEMA</strong></p></div>
					<div class="message"><p>Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor.</p>
					<p><strong>ADMIN</strong></p></div>
					<div class="message"><p>Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor.</p>
					<p><strong>NWSC</strong></p></div>
					<div class="message"><p>Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor.</p>
					<p><strong>John Doe</strong></p></div>
				</div>
			</div>
			<footer>
				<form class="post_message">
					<input type="text" value="Message" onFocus="if(!this._haschanged){this.value=''};this._haschanged=true;">
					<input type="submit" class="btn_post_message" value=""/>
				</form>
			</footer>
		</article><!-- end of messages article -->
		
		<div class="clear"></div><!-- end of post new article --><!-- end of styles article -->
	  <div class="spacer"></div>
	</section>


</body>

</html>
<?php
mysql_free_result($cases);

mysql_free_result($msg);

mysql_free_result($msgNo);
?>
