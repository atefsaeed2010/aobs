<?php

class aobs_Asterisk {

	var $connection;
	var $query;
	var $result;
	var $db;
	var $port;
	
	function aobs_asterisk() {
		global $aobs_db;
		$this->db = $aobs_db;
		list($host,$port) = explode(":",$this->db['dbHost']);
		$this->port = $port;
		if (!$this->port) $this->port = 3306;
	}
	
	function connect() {
		$this->connection = mysql_connect($this->db['dbHost'].":".$this->port,$this->db['dbUser'],$this->db['dbPass']);
		//$this->connection = mysql_connect("142.55.54.11:3306","asterisk","asterisk");
		if (!$this->connection) $this->error("Connection Error");
		if (!mysql_select_db($this->db['dbName'], $this->connection)) $this->error("Database Error");
		return $this->connection;
	}
	
	function query($string) {
		$this->result = mysql_query($string,$this->connection);
		if (!$this->result) $this->error("Query Error");
		return $this->result;
	}
	
	function num_rows($value = -1) {
		if ($value != -1) $this->query = $value;
		return mysql_num_rows($this->query);
	}
	
	function fetch_array($value) {
        $this->result = mysql_fetch_array($value);
        return $this->result;
    }
	
	function error($error_title) {
		global $aobs_admin;
		$error = '';
		$error .= "<br>Error #".mysql_errno();
		$error .= "<br>Error: ".mysql_error();
		$error .= "<br>IP Address: ".getenv("REMOTE_ADDR");
		$error .= "<br>Request: ".getenv("REQUEST_URI");
		$error .= "<br>Referrer: ".getenv("HTTP_REFERRER");
		print "$error";
		exit;
    }
	
}

$aobs = new aobs_Asterisk();
$aobs->connect();
$aobs->tbl = $aobs_tbl;
?>