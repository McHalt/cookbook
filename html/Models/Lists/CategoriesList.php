<?php

namespace Models\Lists;

use Models\Objects\Category;

class CategoriesList extends Base
{
	public string $elementsClass = "\\Models\\Objects\\Category";
	public string $table = "categories";
	public bool|int $load4Recipe = false;
	
	public function __construct(array $inputs = [])
	{
		if (!empty($inputs['load4Recipe'])) {
			$this->load4Recipe = $inputs['load4Recipe'];
		}
		parent::__construct($inputs);
		$parentsIds2Children = [];
		/** @var Category $object */
		foreach ($this->objects as $key => $object) {
			if ($object->parentId > 0) {
				$parentsIds2Children[$object->parentId] = $object;
				unset($this->objects[$key]);
			}
		}
		foreach ($parentsIds2Children as $parentId => $child) {
			foreach ($this->objects as $object) {
				if ($object->id != $parentId) {
					continue;
				}
				if (!isset($object->children)) {
					$object->children = new CategoriesList(['empty' => true]);
				}
				$object->children->add($child);
			}
		}
	}
	
	public function getLoadQry()
	{
		if ($this->load4Recipe) {
			return "
				SELECT c.*
				FROM recipes_to_categories rtc
				INNER JOIN categories c ON rtc.category_id = c.id
				WHERE recipe_id = " . $this->load4Recipe . "
				UNION ALL
				SELECT c2.*
				FROM recipes_to_categories rtc
				INNER JOIN categories c ON rtc.category_id = c.id
				INNER JOIN categories c2 ON c.parent_id = c2.id
				WHERE recipe_id = " . $this->load4Recipe . "
			";
		}
		return parent::getLoadQry();
	}
}