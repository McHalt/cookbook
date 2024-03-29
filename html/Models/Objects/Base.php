<?php


namespace Models\Objects;


use Models\Db;
use Models\Tool;

abstract class Base extends \Models\Base
{
	public string $table;
	public string $loadVia = "id";
	public bool $stop = false;
	public array $properties2Save = [];
	
	public function __construct(array $inputs = [])
	{
		if (!empty($inputs['loadVia'])) {
			$this->loadVia = $inputs['loadVia'];
		}
		if (empty($inputs[$this->loadVia])) {
			$this->stop = true;
			return;
		}
		$this->{$this->loadVia} = $inputs[$this->loadVia];
		$result = Db::query($this->getLoadQry());
		if (count($result) > 1) {
			trigger_error(
				"I don't know which object (" . Tool::getBasename(get_called_class()) . ") to load,
				 because it is more than 1 where  $this->loadVia = '" . $inputs[$this->loadVia] . "'"
				, E_USER_WARNING
			);
			return;
		} else if (count($result) < 1) {
			$this->stop = true;
			return;
		}
		foreach ($result[0] as $key => $value) {
			$key = lcfirst(
					implode(
						'', 
						array_map(
							'ucfirst', 
							explode(
								"_", 
								$key
							)
						)
					)
			);
			$this->$key = $value;
		}
	}
	
	public function getLoadQry(){
		return "SELECT * FROM $this->table WHERE $this->loadVia = '" . $this->{$this->loadVia} . "'";
	}
	
	public function save(array $additionalData = [])
	{
		$values = [];
		if (empty($this->id)) {
			$this->loadVia = 'id';
		}
		if (
			empty($this->{$this->loadVia}) 
			|| ($additionalData['forceInsert'] ?? false)
		) {
			$keys = array_diff($this->properties2Save, [$this->loadVia]);
			foreach ($keys as $key) {
				$values[$key] = htmlspecialchars($this->$key);
			}
			$qryAddon = '';
			if ($additionalData['onDuplicateUpdate'] ?? false) {
				$qryAddon = "
					ON DUPLICATE KEY UPDATE
				";
				foreach ($values as $key => $value) {
					$qryAddon .= "
					`$key` = '$value',";
				}
				$qryAddon = substr($qryAddon, 0, -1);
			}
			$qry = "
				INSERT INTO $this->table
				(`" . implode("`,`", $keys) . "`)
				VALUES ('" . implode("','", $values) . "') $qryAddon;
			";
		} else {
			foreach ($this->properties2Save as $property) {
				if ($this->loadVia == $property) {
					continue;
				}
				$values[] = "`$property`='" . htmlspecialchars($this->$property) . "'";
			}
			$qry = "
				UPDATE $this->table
				SET " . implode(",", $values) . "
				WHERE $this->loadVia = '" . $this->{$this->loadVia} . "'
			";
		}
		Db::exec($qry);
		if (empty($this->{$this->loadVia})) {
			$this->{$this->loadVia} = Db::getLastInsertId();
		}
	}
}