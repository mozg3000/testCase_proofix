<?php
include "../ClassLoader.php";

spl_autoload_register([ClassLoader::class, 'load']);

header('Content-Type: application/json');
$date = $_POST['departure_date']??time();
$curierName = $_POST['curier_selection']??'';
$regionName = $_POST['regions_selection']??'';

$db = \infrastructure\Db::getInstance();
$curierRepository = new \repository\CurierRepository($db);
$curier = $curierRepository->getOneByName($curierName);

$regionRepository = new \repository\RegionRepository($db);
$region = $regionRepository->getOneByName($regionName);

$scheduleRepository = new \repository\ScheduleRepository($db);
// var_dump($date);

$AlredyScheduled = $scheduleRepository->hasCurierTrip($curier['id'], $date);
if($AlredyScheduled){
	echo json_encode($result = [
		'status' => 'error',
		'msg' => 'alredy scheduled'
	]);
}else{
	$scheduleRepository->insert([
		'date' => $date,
		'id_curiers' => $curier['id'],
		'id_regions' => $region['id']
	]);

	$id = $db->lastInsertId();
	$result = [];
	//echo json_encode([ 'd' => $curier]);
	//return;
	if($id){
		$result = [
			'status' => 'OK',
			'id' => $id
		];
	}else{
		$result = [
			'status' => 'error'
		];
	}
	echo  json_encode($result);
}
// echo json_encode(['date' => $curier['id']]);
// echo json_encode([
	// 'date' => $date,
	// 'id_curiers' => $curier['id'],
	// 'id_regions' => $region['id']
// ]);
// echo json_encode($curier);
//echo json_encode($_POST['id_curiers'], JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK);