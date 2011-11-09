<?php

if (!eregi("index.php", $_SERVER['PHP_SELF'])) {
	die();
}

if (isset($_REQUEST['tab']) && $_REQUEST['tab'] == "lgi") {
	$Query = $aobs->query("SELECT * FROM ".$aobs->tbl['user']." WHERE user_name='".$_POST['username']."'");
	if ($aobs->num_rows($Query) > 0) {
		$User = $aobs->fetch_array($Query);
		if ((strtolower($_POST['username']) == strtolower($User['user_name'])) && ($_POST['password'] == $User['user_pass'])) {
			$User_Name = $_POST['username'];
			$User_Pass = md5($_POST['password']);
			setcookie("User_Name", "$User_Name", time() + $aobs_dtl['cookies'], '/'); // Cookies time is set in config.php
			setcookie("User_Pass", "$User_Pass", time() + $aobs_dtl['cookies'], '/'); // Cookies time is set in config.php
			$aobs_tag = '<meta http-equiv="refresh" content="3; url=?aobs=user&amp;tab=" />';
			$MSG_Title = "Welcome!";
			$MSG_Content =	$aobs_msg['auth_login'];
			eval ("\$LAYOUT_CONTENT = \"".import_tpl("tpl_message")."\";");
		} else {
			$aobs_tag = '<meta http-equiv="refresh" content="3; url=?aobs=auth&amp;tab=" />';
			$MSG_Title = "Incorrect Password!";
			$MSG_Content =	$aobs_msg['auth_password'];
			eval ("\$LAYOUT_CONTENT = \"".import_tpl("tpl_message")."\";");
		}
	} else {
		$aobs_tag = '<meta http-equiv="refresh" content="5; url=?aobs=auth&amp;tab=" />';
		$MSG_Title = "Invalid Username!";
		$MSG_Content =	$aobs_msg['auth_username'];
		eval ("\$LAYOUT_CONTENT = \"".import_tpl("tpl_message")."\";");
	}	
} else if (isset($_REQUEST['tab']) && $_REQUEST['tab'] == "lgo") {
	setcookie("User_Name", "null", time() + 0, '/');
	setcookie("User_Pass", "null", time() + 0, '/');
	$aobs_tag = '<meta http-equiv="refresh" content="2; url=?" />';
	$aobs_title = "Logged Out";
	$MSG_Title = 'Logging out';
	$MSG_Content = $aobs_msg['auth_logout'];
	eval ("\$LAYOUT_CONTENT = \"".import_tpl("tpl_message")."\";");
} else { 
	eval ("\$LAYOUT_CONTENT = \"".import_tpl("tpl_login")."\";");
}
?>