<?php
namespace models;
class Region extends Model{
	private $name;
	
	public function __construct(string $name){
		$this->name = $name;
	}
}