<?php

include_once ("_config.php");
include_once ("_classes.php");

if (phpversion() >= 4.1) {
	$_GET = $_GET;
	$_POST = $_POST;
	$_COOKIE = $_COOKIE;
} else {
	$_GET = $HTTP_GET_VARS;
	$_POST = $HTTP_POST_VARS;
	$_COOKIE = $HTTP_COOKIE_VARS;
}

function import_tpl($aobs_tpl,$aobs_ext="html") {
	global $aobs_dtl;
	if(!isset($aobs_dtl['source'])) $aobs_fdr = "aobs";
	else $aobs_fdr = $aobs_dtl['source'];
	return str_replace("\"", "\\\"", implode("",file($aobs_fdr."/".$aobs_tpl.".".$aobs_ext)));
}

function export_tpl($aobs_tpl) {
	echo $aobs_tpl;
}

function get_number($aobs_num) {
	global $aobs_dtl;
	if (strlen($aobs_num) > $aobs_dtl['intrim']) {
		$dest = substr($aobs_num, -10);
	} else {
		$dest = $aobs_num;
	}
	return $dest;
}

function get_rate($aobs_num, $aobs_ext) {
	global $aobs_dtl;
	if ($aobs_num == $aobs_ext) {
		$code = "ICM";
	} else {
		if (strlen($aobs_num) > $aobs_dtl['intrim']) {
			$code = "LDC";	// Long Distance	
		} else if ($aobs_num == "4000") {
			$code = "CNF";	// Conference call
		} else {
			$code = "INT";	// Week Night
		}
	}
	return $code;
}

function get_charge($aobs_code, $aobs_len) {
	global $aobs_chr;
	if ($aobs_len > 0) {
		if ($aobs_len < 15)	$aobs_cost = 15;
		else if ($aobs_len < 30) $aobs_cost = 30;
		else if ($aobs_len < 60) $aobs_cost = 60;
		else $aobs_cost = $aobs_len;
	} 
	if ($aobs_code == "LDC") { 
		$cost = $aobs_cost * $aobs_chr['LDC'];
	} else if ($aobs_code == "ICM") {
		$cost = $aobs_cost * $aobs_chr['ICM'];
	} else if ($aobs_code == "CNF") {
		$cost = $aobs_cost * $aobs_chr['CNF'];
	} else {
		$cost = $aobs_cost *  $aobs_chr['INT'];
	}
	return number_format(($cost/60), 2);
}

function get_format($aobs_cost) {
	$aobs_num = explode("$", $aobs_cost);
	return "$".number_format($aobs_num[0], 2);
}


function get_page($aobs_query, $aobs_max = 1, $aobs_url) {
	global $aobs_dtl, $PAGE_NUM;
	if (empty($_GET['p'])) $aobs = 0; else $aobs = 4;
	$link=substr($aobs_url,0,(strlen($aobs_url)-$aobs));
	$page = isset($_GET['p'])?$_GET['p']:1;
	$query = mysql_query($aobs_query);
	$start = ($aobs_max * $page) - $aobs_max;
	$total = mysql_num_rows($query);
	if ($total <= $aobs_max) {
		$totalpg = 1;
	} else {
		if (($total % $aobs_max) == 0) $totalpg = ($total/$aobs_max);
		else $totalpg = ($total/$aobs_max) + 1;
		$totalpg = (int) $totalpg;
	}
	if ($total > $aobs_max) {
		$aobs_query = $aobs_query." LIMIT ".$start.",".$aobs_max;
		for ($i = 1; $i <= $totalpg; $i++) {
			if ($i == $_GET['p']) {
				eval ("\$PAGE_NUM .= \"".import_tpl("tpl_current")."\";");
			} else {
				$page = $link."&p=".$i;
				eval ("\$PAGE_NUM .= \"".import_tpl("tpl_list")."\";");
			}
		}
	}
	return $aobs_query;
}
?>