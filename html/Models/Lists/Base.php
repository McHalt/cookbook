<?php


namespace Models\Lists;


use Models\Db;

abstract class Base extends \Models\Base
{
	public string $elementsClass;
	public string $table;
	public array $objects = [];
	public string $columnToLoadObj = "id";
	public string $additionalSql = '';
	
	public function __construct(array $inputs = [])
	{
		if (!empty($inputs['additionalSql'])) {
			$this->additionalSql = $inputs['additionalSql'];
		}
		$elementsClass = $this->elementsClass;
		if (($inputs['empty'] ?? 0) == 1) {
			return;
		}
		foreach (Db::query($this->getLoadQry()) as $item) {
			if (empty($item[$this->columnToLoadObj])) {
				continue;
			}
			$element = new $elementsClass([$this->columnToLoadObj => $item[$this->columnToLoadObj]]);
			foreach ($item as $key => $val) {
				if (property_exists($element, $key)) {
					$element->$key = $val;
				}
			}
			$this->add($element);
		}
	}
	
	public function add($element) {
		if ($element instanceof $this->elementsClass) {
			$this->objects[] = $element;
		}
	}
	
	public function getLoadQry()
	{
		return "SELECT * FROM $this->table $this->additionalSql";
	}
}