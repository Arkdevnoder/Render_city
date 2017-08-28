<?php

class mysql extends renderer {
	public $mysql;
	public function __construct()
	{
		$this->mysql = new \mysqli
		(
			$this->get_configuration("db_server"), $this->get_configuration("db_user"),
			$this->get_configuration("db_password"), $this->get_configuration("db_name")
		);
		$this->mysql->set_charset("utf8");
	}
	public function get_field_by_index($table, $index, $indexvalue, $field){
		$sql_object = $this->get_sqlobj();
		$table = $sql_object->escape_string((string)$table);
		$index = $sql_object->escape_string((string)$index);
		$indexvalue = $sql_object->escape_string((string)$indexvalue);
		$field = $sql_object->escape_string((string)$field);
		$query = "SELECT * FROM `{$table}` WHERE `{$index}` = {$indexvalue}";
		$query = $sql_object->query($query);		if($query->num_rows !== 0)
		{
			return $query->fetch_assoc()[$field];
		} 
		else 
		{
			return null;
		}
	}
	public function set_field_by_index($table, $index, $indexvalue, $field, $value)
	{
		$sql_object = $this->get_sqlobj();
		$table = $sql_object->escape_string((string)$table);
		$index = $sql_object->escape_string((string)$index);
		$indexvalue = $sql_object->escape_string((string)$indexvalue);
		$field = $sql_object->escape_string((string)$field);
		$value = $sql_object->escape_string((string)$value);
		$field_check = $this->get_field_by_index($table, $index, $indexvalue, $field);
		if($field_check !== null)
		{
			$querystr = "
				UPDATE  `{$table}` 
				SET `{$field}` = '{$value}' 
				WHERE `{$index}` = '{$indexvalue}' LIMIT 1
			";
			$query = $sql_object->query($querystr);
			return $query;
		} 
		else 
		{
			return $this->registration($table, $index, $indexvalue, $field, $value);
		} 
	}
	
	public function registration($table, $index, $indexvalue, $field, $value)
	{
		$sql_object = $this->get_sqlobj();
		$table = $sql_object->escape_string((string)$table);
		$index = $sql_object->escape_string((string)$index);
		$indexvalue = $sql_object->escape_string((string)$indexvalue);
		$field = $sql_object->escape_string((string)$field);
		$value = $sql_object->escape_string((string)$value);

	    	$queryend = "INSERT into `{$table}` (`{$index}`, `{$field}`) VALUES ('{$indexvalue}','{$value}')";

		$query = $this->get_sqlobj()->query($queryend);
		return $query;
	}

	public function delete_row_by_index($table, $index, $indexvalue)
	{
		$queryend = "DELETE FROM `{$table}` WHERE `{$index}` = '{$indexvalue}'";
		$query = $this->get_sqlobj()->query($queryend);
		return $query;
	}

	public function all_table($table)
	{
		$queryend = "SELECT * FROM `{$table}`";
		$query = $this->get_sqlobj()->query($queryend);
		return $query;
	}

	public function max($table, $colomn)
	{
		$querystr = "SELECT MAX(`{$colomn}`) as `max` FROM `{$table}` LIMIT 0,1";
		$query = $this->get_sqlobj()->query($querystr)->fetch_assoc();
		return $query["max"];
	}

	public function get_sqlobj()
	{
		return $this->mysql;
	}
}

?>
