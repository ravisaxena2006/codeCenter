<?php
ob_start();
session_start();
error_reporting(0);
include('include/PageRankChecker.php');
$pageUrl 	= $_REQUEST['pageurl'];
$cptValue	= $_REQUEST['captchaValue'];
if($cptValue == $_SESSION['cap_code']){
	$gpr 		= new GooglePR();
	$pageRank 	= $gpr->getPagerank($pageUrl);
	$pageRank 	= $pageRank == '' ? '<span class="error_msg">ERROR: Can not connect to the server...</span>' : $pageRank.'/10';
	?>
	<div class="pagerank_area">
		<div class="rank_header">
			<div class="left_box">URL</div>
			<div class="right_box">Page Rank</div>
		</div>
		
		<div class="rank_body">
			<div class="left_box"><?php echo $pageUrl; ?></div>
			<div class="right_box"><?php echo $pageRank; ?></div>
		</div>
	</div>
<?php
}else{
	echo 0;
}
?>