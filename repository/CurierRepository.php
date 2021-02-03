<?php
namespace repository;
class CurierRepository extends Repository implements \interfaces\RepositoryInterface{
	public function __construct(\interfaces\DbInterface $db){
		parent::__construct($db, 'curiers');
	}
	public function batchInsert(array $rows){
		$sql = "INSERT INTO {$this->tableName}  (name) VALUES (:name)";
		
		foreach($rows as $row){
			$curierName = explode(',', $row);
			$cn = trim($curierName[0]);
			$this->db->query($sql, [':name' => $cn]);
		}
	}
	public function getOneByName(string $name){
		$sql = "SELECT * FROM {$this->tableName} WHERE name = :name";
		return $this->db->queryOne($sql, [':name' => $name]);
	}
	
}