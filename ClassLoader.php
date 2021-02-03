<?php

class ClassLoader{
	public static function load($classname){
		$filename = '../' . str_replace('\\', '/', $classname) . '.php';
		if(file_exists($filename)){
				include $filename;
			}
		
	}
}