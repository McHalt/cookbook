<?php


namespace App\Pages;


use Models\Data;
use Models\Lists\MenuItemsList;

abstract class Base extends \App\Base
{
	public Data $data;
	public static bool $preventView = false;
	
	public function __construct()
	{
		$this->data = new Data();
		$this->prepareCommonData();
		$this->prepareData();
		if (!static::$preventView) {
			$this->render($this->data);
		}
	}
	
	public abstract function prepareData();
	
	final public function prepareCommonData() {
		$this->data->menuItems = (new MenuItemsList(["parentId" => 0]))->objects;
	}
}