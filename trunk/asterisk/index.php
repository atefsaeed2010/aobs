<?php

require_once("_function.php");
require_once("_classes.php");

global $aobs_dtl, $aobs_admin, $aobs_msg;
global $ADMIN_Error, $ADMIN_Message;

if ($_GET['aobs'] == 'user') {
	include_once('php/usercp.php');
} else if ($_GET['aobs'] == 'reg') {
	include_once('php/register.php');
} else {
	include_once('php/login.php');
}

/*if(isset($_COOKIE['Admin_Name']) && isset($_COOKIE['Admin_Pass'])) {
	$aobsADMIN = '| <a href="?intl='.$aobs_dtl['lang'].'"><b>cPanel</b></a>';
	eval ("\$LAYOUT_MENU = \"".import_tpl("tpl_adminnavi")."\";");
} else */if(isset($_COOKIE['User_Name']) && isset($_COOKIE['User_Pass'])) {
	eval ("\$LAYOUT_MENU = \"".import_tpl("tpl_usernavi")."\";");
} eval ("export_tpl(\"".import_tpl("tpl_index")."\");");
?>