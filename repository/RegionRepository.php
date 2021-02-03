<?php
namespace repository;
class RegionRepository extends Repository implements \interfaces\RepositoryInterface{
	public function __construct(\interfaces\DbInterface $db){
		parent::__construct($db, 'regions');
	}
	public function batchInsert(array $rows){
		$sql = "INSERT INTO {$this->tableName}  (name, duration) VALUES (:name, :duration)";
		foreach($rows as $row){
			$regions = explode(',', $row);
			//print_r($params);
			$this->db->query($sql, [':name' => $regions[0], ':duration' => $regions[1]]);
		}
	}
	public function getOneByName(string $name){
		$sql = "SELECT * FROM {$this->tableName} WHERE name = :name";
		
		return $this->db->queryOne($sql, [':name' => $name]);
	}
}