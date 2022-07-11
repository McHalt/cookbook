<?php


namespace Models;


class Data extends Base
{
	public array $warnings = [];
	public array $errors = [];
	public function __get($name): string
	{
		return '';
	}
	
	public function getBasicForm(&$arrOrObj) {
		if ($arrOrObj instanceof Lists\Base) {
			$arrOrObj = $arrOrObj->objects ?? [];
		} elseif (is_object($arrOrObj)) {
			$arrOrObj = get_object_vars($arrOrObj);
		}
		if (is_array($arrOrObj)) {
			foreach ($arrOrObj as &$item) {
				$this->getBasicForm($item);
			}
		}
	}
	
	public function getArrayEquivalent(): array
	{
		$vars = get_object_vars($this);
		foreach ($vars as &$var) {
			$this->getBasicForm($var);
		}
		return json_decode(json_encode($vars), true);
	}
	
	public function __construct(array $inputs = [])
	{
		
	}
}