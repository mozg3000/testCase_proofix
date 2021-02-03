<?php
namespace repository;
abstract class Repository{
	protected $db;
	protected $tableName;
	public function __construct($db, $tableName){
		$this->db = $db;
		$this->tableName = $tableName;
	}
		
	public function getAll(){
		$sql = "SELECT * FROM {$this->tableName}";
		return $this->db->queryAll($sql, []);
	}
}