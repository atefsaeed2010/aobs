<?php

if (!eregi("index.php", $_SERVER['PHP_SELF'])) {
	die();
}
/*If cooke is currently set*/
if(isset($_COOKIE['User_Name']) && isset($_COOKIE['User_Pass'])) {
	$UserQuery = $aobs->query("SELECT * FROM ".$aobs->tbl['user']." WHERE user_name = '".$_COOKIE['User_Name']."'");
	if ($aobs->num_rows($UserQuery) > 0) {
		$User = $aobs->fetch_array($UserQuery);
		$uid_t  = $User['user_id'];
		$uid 	= "HQ84W0".$User['user_id'];
		$uext 	= $User['user_ext'];
		$user	= $User['user_name'];
		$uemail	= $User['user_email'];
		$ufirst	= $User['firstname'];
		$ulast	= $User['lastname'];
		$uname	= $ufirst." ".$ulast;
		$uaddr	= $User['address'];
		$ucity	= $User['city'];
		$uprov	= $User['province'];
		$upost	= $User['postal'];
		setcookie("User_Exist", "$uid", time() + $aobs_dtl['cookies'], '/'); // Cookies time is set in config.php
		if ($_REQUEST['tab'] == "mysf" || $_REQUEST['tab'] == "mdfy") {
			include_once('userinfo.php');
		} else if ($_REQUEST['tab'] == "bill") {
			include_once('billing.php');
		} else if (($_REQUEST['tab'] == "support") || ($_REQUEST['tab'] == "submit_ticket") || ($_REQUEST['tab'] == "view_ticket")  || ($_REQUEST['tab'] == "update_ticket")) {
			include_once('ticket.php');
		} else if ($_REQUEST['tab'] == "svcs") {
			$services = "class='active'";
			eval ("\$USER_Info = \"".import_tpl("user_services")."\";");
		
		} else {
			$overview = "class='active'";
			eval ("\$USER_Info = \"".import_tpl("user_overview")."\";");
		}
		eval ("\$LAYOUT_CONTENT = \"".import_tpl("usercp")."\";");
	}
} else { /*No cookie.. expired?*/
	$aobs_tag = '<meta http-equiv="refresh" content="5; url=?aobs=auth&amp;tab=" />';
	$MSG_Title = "Your session has been expired!";
	$MSG_Content =	$aobs_msg['auth_cookies'];
	eval ("\$LAYOUT_CONTENT = \"".import_tpl("tpl_message")."\";");
}
?>
