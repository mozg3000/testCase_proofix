<?php
namespace repository;
class ScheduleRepository extends Repository implements \interfaces\RepositoryInterface{
	public function __construct(\interfaces\DbInterface $db){
		parent::__construct($db, 'schedules');
	}
	public function batchInsert(array $rows){
	 $sql = "INSERT INTO {$this->tableName}  ( `date`, `id_curiers`, `id_regions`) VALUES (:date, :id_curiers, :id_regions)";
	 
		foreach($rows as $curierName){
			$date = new \DateTime("2015/06/22");
			$curier= explode(',', $curierName)[0];
			$curierRepository = new \repository\CurierRepository($this->db);
			$current_curier = $curierRepository->getOneByName($curier);
			for($i=0; $i <1900; ++$i){
				$date = $date->add(new \DateInterval("P1D"));
				$id_regions = random_int(2,11);
				$this->db->query($sql, [
					':date' => $date->format('Y/m/d'), 
					':id_curiers' => $current_curier['id'],
					':id_regions' => $id_regions
				]);
			}			
		}
		
			//print_r($this->db->connection->errorInfo());
	}
	public function insert(array $schedule_data){
		$sql = "INSERT INTO {$this->tableName}  ( `date`, `id_curiers`, `id_regions`) VALUES (:date, :id_curiers, :id_regions)";
		//$date = new \DateTime($schedule_data['date']);
		
		$this->db->query($sql, [
			// ':date' => $date->format('Y-m-d'), 
			':date' => $schedule_data['date'], 
			':id_curiers' => (int)$schedule_data['id_curiers'],
			':id_regions' => (int)$schedule_data['id_regions']
		]);
	}
	public function hasCurierTrip(int $id, string $date):bool{
		$sql = "SELECT count(*) as count FROM {$this->tableName} WHERE id_curiers = :id_curiers AND date = :date";
	
		$result = $this->db->queryOne($sql, [
			'id_curiers' => $id,
			'date' => $date
		]);
		
		return $result['count']?true:false;
	}
	public function getScheduleOnDate(string $date){
		// $sql = "SELECT * FROM {$this->tableName} WHERE date = :date";
		$sql = "SELECT date, curiers.name as curier, regions.name as region FROM `schedules` left join regions on id_regions=regions.id left join curiers on id_curiers=curiers.id where date=:date";
		return $this->db->queryAll($sql, ['date' => $date]);
	}
	public function getScheduleBetweenDate(string $startDate, string $endDate){
		// $sql = "SELECT * FROM {$this->tableName} WHERE date = :date";
		$sql = "SELECT date, curiers.name as curier, regions.name as region
FROM `schedules` left join regions on id_regions=regions.id left join curiers on id_curiers=curiers.id
where date between :startDate AND :endDate";
		return $this->db->queryAll($sql, ['startDate' => $startDate, 'endDate' => $endDate]);
	}
}