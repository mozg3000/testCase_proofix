<?php
include "../ClassLoader.php";

spl_autoload_register([ClassLoader::class, 'load']);

	
$db = \infrastructure\Db::getInstance();
############
# Заполнение таблицы регионов
/*
$regionRepository = new \repository\RegionRepository($db, 'regions');
$rows = file('../data/regions.csv');
//var_dump($rows);
$regionRepository->batchInsert($rows);
*/
#############
# Заполнение таблицы курьеров
/*
$rows = file('../data/curiers.csv');

$curierRepository = new \repository\CurierRepository($db);
$curierRepository->batchInsert($rows);
//*/
###############
# Заполнение таблицы расписания поездок
/*
$rows = file('../data/curiers.csv');
$scheduleRepository = new \repository\ScheduleRepository($db);

$scheduleRepository->batchInsert($rows);
//*/
############
# Работа с формой
//*
$curierRepository = new \repository\CurierRepository($db);
$curiers = $curierRepository->getAll();

$regionRepository = new \repository\RegionRepository($db);
$regions = $regionRepository->getAll();
############
# работа с таблицей расписания
$scheduleRepository = new \repository\ScheduleRepository($db);
$schedule = [];
if($_SERVER['REQUEST_METHOD'] === "POST"){
	$startDate = $_POST["departure_date"]??(new DateTime())->format('Y-m-d');
	$endDate = $_POST["delivery_date"]??(new DateTime())->format('Y-m-d');
	$schedule = $scheduleRepository->getScheduleBetweenDate($startDate, $endDate);
}else{
	$schedule = $scheduleRepository->getScheduleOnDate((new DateTime())->format('Y-m-d'));
}
//*/
?>
<!DOCTYPE html>
<html >

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Proofix'</title>
        <meta name="description" content="Тестовое задание Proofix.">
        <link rel="stylesheet" href="./assert/css/main.css">
    </head>
    <body>
			<form 
				name="addTrip" 
				method="post" 
				action="#"
				class="form"
				id="addTrip"
			>
				<div 
					class="form__group"
				>
					<label for="curier_selection">
						Курьер:
					</label>
					<select 
						class="form__curier-select"
						name="curier_selection"
					>
						<?php foreach($curiers as $curier):?>
							<option data-id="<?php echo $curier['id']?>">
								<?php echo $curier['name']?>
							</option>
						<?php endforeach;?>
					</select>
				</div>
				<div 
					class="form__group"
				>
					<label for="region_selection">
						Регион доставки:
					</label>
					<select
						class="form__region-select"
						name="regions_selection"
					>
						<?php foreach($regions as $region):?>
							<option 
								data-id="<?php echo $region['id']?>"
								data-duration="<?php echo $region['duration']?>"
							>
								<?php echo $region['name']?>
							</option>
						<?php endforeach;?>
					</select>
				</div>
				<div
					class="form__group"
				>
					<label 
						for="departure_date"
					>
						Отправление в:
					</label>
					<input 
						type="date"
						name="departure_date"
						class="form__date-input"
					>
				</div>
				<div
					class="form__group"
				>
					<label 
						for="delivery_date"
					>
						Прибытие в:
					</label>
					<input 
						type="text"
						name="delivery_date"
						class="form__date-input"
						disabled
						value=""
					>
				</div>
				<div
					class="form__group form__group_centered"
				>
					<input
						class="form__btn"
						type="submit"
						value="Запланировать"
					>
				</div>
			</form>
			<form
				method="post"
				action="index.php"
			>
				<div
					class="box-control"
				>
					<div
						class="box-control__group"
					>
						<label 
							for="departure_date"
						>
							Отправление в:
						</label>
						<input 
							type="date"
							name="departure_date"
							class="form__date-input"
							value="<?php echo $startDate?>"
						>
					</div>
					<div
						class="box-control__group"
					>
						<label 
							for="delivery_date"
						>
							Прибытие в:
						</label>
						<input 
							type="date"
							name="delivery_date"
							class="form__date-input"
							value="<?php echo $endDate?>"
						>
					</div>
					<div
						class="box-control__group form__group_centered"
					>
						<input
							class="form__btn form__btn_schedule"
							type="submit"
							value="Показать"
						>
					</div>
					<div
						class="box-control__group form__group_centered"
					>
						<input
							class="form__btn form__btn_schedule-cancel"
							type="button"
							value="Сбросить"
							id="drop_range"
						>
					</div>
				</div>
			</form>
			<?php if($schedule):?>
			<div 
				class="table"
			>
					<div
						class="table__row  table__row_header"
					>
						<div
							class="table__cell table__cell_header"
						>
							Отправление
						</div>
						<div
							class="table__cell table__cell_header"
						>
							Курьер
						</div>
						<div
							class="table__cell table__cell_header"
						>
							Регион
						</div>
				</div>
				<?php foreach($schedule as $appointment):?>
					<div
						class="table__row"
					>
						<div
							class="table__cell"
						>
							<?php echo $appointment['date']?>
						</div>
						<div
							class="table__cell"
						>
							<?php echo $appointment['curier']?>
						</div>
						<div
							class="table__cell"
						>
							<?php echo $appointment['region']?>
						</div>
					</div>
				<?php endforeach;?>
			</div>
			<?php else:?>
				<p class="table__message">
					Нет запланированных поедок на сегодня
				</p>
			<?php endif;?>
		<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
		<script src="./assert/js/main.js"></script>
    </body>
</html>