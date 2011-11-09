<?

if (!eregi("index.php", $_SERVER['PHP_SELF'])) {
	die();
}
/*If cooke is currently set*/
if(isset($_COOKIE['User_Name']) && isset($_COOKIE['User_Pass']) && isset($_COOKIE['User_Exist'])) {
	if ($_REQUEST['tab'] == "mysf") {
		switch ($uprov) {
			case "AB":	$ab = "selected";	break;
			case "BC":	$bv = "selected";	break;
			case "MB":	$mb = "selected";	break;
			case "NB":	$nb = "selected";	break;
			case "NL":	$nl = "selected";	break;
			case "NT":	$nt = "selected";	break;
			case "NS":	$ns = "selected";	break;
			case "NU":	$nu = "selected";	break;
			case "ON":	$on = "selected";	break;
			case "PE":	$pe = "selected";	break;
			case "QC":	$qc = "selected";	break;
			case "SK":	$sk = "selected";	break;
			case "YT":	$yt = "selected";	break;
		}
		$profile = "class='active'";
		eval ("\$USER_Info = \"".import_tpl("user_profile")."\";");
	} else if ($_REQUEST['tab'] == "mdfy") {
		$UserQuery = $aobs->query("SELECT * FROM ".$aobs->tbl['user']." WHERE user_name='".$_POST['username']."'");
		if ($aobs->num_rows($UserQuery) > 0) {
			if (!empty($_POST['confirm'])) {
				if ((!empty($_POST['username'])) && (!empty($_POST['email'])) && (!empty($_POST['password'])) && (!empty($_POST['firstname'])) && (!empty($_POST['lastname'])) && (!empty($_POST['address'])) && (!empty($_POST['city'])) && (!empty($_POST['province'])) && (!empty($_POST['postal']))) {
					$aobs->query("UPDATE ".$aobs->tbl['user']." SET ".
						"user_email = '".$_POST['email']."',
						 firstname = '".$_POST['firstname']."',
						 lastname = '".$_POST['lastname']."',
						 address = '".$_POST['address']."',
						 city = '".$_POST['city']."',
						 province = '".$_POST['province']."',
						 postal = '".$_POST['postal']."'
						 WHERE user_name = '".$_POST['username']."'");
					$aobs_tag = '<meta http-equiv="refresh" content="5; url=?aobs=user&amp;tab=mypf" />';
					$MSG_Title = "Successfully updated!";
					$MSG_Content =	$aobs_msg['change_done'];
					eval ("\$USER_Info = \"".import_tpl("tpl_message")."\";");
				} else {
					$aobs_tag = '<meta http-equiv="refresh" content="5; url=javascript:history.go(-1)" />';
					$MSG_Title = "All fields must be filled!";
					$MSG_Content =	$aobs_msg['reg_fields'];
					eval ("\$USER_Info = \"".import_tpl("tpl_message")."\";");
				}
			} else {
				$aobs_tag = '<meta http-equiv="refresh" content="5; url=javascript:history.go(-1)" />';
				$MSG_Title = "You must confirm your request!";
				$MSG_Content =	$aobs_msg['change_agree'];
				eval ("\$USER_Info = \"".import_tpl("tpl_message")."\";");
			}
		} else {
			$aobs_tag = '<meta http-equiv="refresh" content="5; url=?aobs=auth&amp;tab=mypf" />';
			$MSG_Title = "Unble to update!";
			$MSG_Content =	$aobs_msg['change_failed'];
			eval ("\$USER_Info = \"".import_tpl("tpl_message")."\";");
		}
	} 
}
?>