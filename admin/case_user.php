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
$query_cases = "SELECT count(*) FROM `case` WHERE `case`.status != 'Assigned'";
$cases = mysql_query($query_cases, $water) or die(mysql_error());
$row_cases = mysql_fetch_assoc($cases);
$totalRows_cases = mysql_num_rows($cases);

$maxRows_caseList = 10;
$pageNum_caseList = 0;
if (isset($_GET['pageNum_caseList'])) {
  $pageNum_caseList = $_GET['pageNum_caseList'];
}
$startRow_caseList = $pageNum_caseList * $maxRows_caseList;

mysql_select_db($database_water, $water);
$query_caseList = "SELECT `case`.title, `case`.status, `case`.id, categories.name as category, location.parish, location.district, `user`.name as fullname, `case`.dated FROM `case` left join categories on `case`.category_id=categories.id  left join location on `case`.location_id = location.id  left join`user` on `case`.user_id=`user`.id ";
$query_limit_caseList = sprintf("%s LIMIT %d, %d", $query_caseList, $startRow_caseList, $maxRows_caseList);
$caseList = mysql_query($query_limit_caseList, $water) or die(mysql_error());
$row_caseList = mysql_fetch_assoc($caseList);

if (isset($_GET['totalRows_caseList'])) {
  $totalRows_caseList = $_GET['totalRows_caseList'];
} else {
  $all_caseList = mysql_query($query_caseList);
  $totalRows_caseList = mysql_num_rows($all_caseList);
}
$totalPages_caseList = ceil($totalRows_caseList/$maxRows_caseList)-1;
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
		<?php include('notifications1.php'); ?>
		<div class="breadcrumbs_container">
			<article class="breadcrumbs"><a href="index.php">Website </a> <div class="breadcrumb_divider"></div> 
			<a class="current">Cases</a></article>
		</div>
	</section>
	
	<!-- end of secondary bar -->
	<?php include('menu1.php'); ?>
	<!-- end of sidebar -->
	
	<section id="main" class="column">
		
		<h4 class="alert_info"><?php echo $row_cases['count(*)']; ?> Un-assigned cases</h4><!-- end of stats article -->
		
		<article class="module width_3_quarter">
		<header>
		<h3 class="tabs_involved">cases</h3>
		
		</header>

		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr>
				  <th width="9%">ID</th> 
   					<th width="19%">Title</th> 
    				<th width="13%">Category</th> 
    				<th width="12%">Location</th>
    				<th width="12%">Status</th>
    				<th width="14%">Assigned</th>
    				<th width="12%">Date </th> 
    				<th width="9%">Actions</th> 
				</tr> 
			</thead> 
			<tbody>
              <?php do { ?>
                <tr>
                  <td><?php echo $row_caseList['id']; ?></td>
                  <td><?php echo $row_caseList['title']; ?></td>
                  <td><?php echo $row_caseList['category']; ?></td>
                  <td><?php echo $row_caseList['parish']; ?>, <?php echo $row_caseList['district']; ?></td>
                  <td><?php echo $row_caseList['status']; ?></td>
                  <td><?php echo $row_caseList['fullname']; ?></td>
                  <td><?php echo $row_caseList['dated']; ?></td>
                  <td><a href="case_details.php?caseID=<?php echo $row_caseList['id']; ?>" title="Details"><img src="images/icn_categories.png" ></a>&nbsp;
                    <a href="case_details.php?trash=<?php echo $row_locations['id']; ?>" title="Trash"><img src="images/icn_trash.png" ></a>&nbsp;</td>
                </tr>
                <?php } while ($row_caseList = mysql_fetch_assoc($caseList)); ?>
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
mysql_free_result($cases);

mysql_free_result($caseList);
?>
