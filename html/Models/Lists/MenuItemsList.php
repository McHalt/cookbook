<?php

namespace Models\Lists;

use Models\Objects\MenuItem;
use Models\Db;

class MenuItemsList extends Base
{
	public string $elementsClass = "\\Models\\Objects\\MenuItem";
	public string $table = "menu_items";
	
	public function __construct(array $inputs = [])
	{
		if (isset($inputs['parentId'])) {
			$this->additionalSql = "WHERE parent_id = " . intval($inputs['parentId']);
		}
		parent::__construct($inputs);
		/** @var MenuItem $item */
		foreach ($this->objects as $item) {
			if ($item->name == 'Kategorie') {
				$this->loadCategories($item);
			}
		}
	}
	
	public function loadCategories(MenuItem $categoriesMenuItem)
	{
		$id = Db::query('SELECT MAX(id) max FROM menu_items')[0]['max'];
		/** @var MenuItem $ids2Items */
		$ids2Items = [];
		foreach (Db::query('SELECT * FROM categories ORDER BY parent_id, id') as $cat) {
			$item = new MenuItem(['empty' => true]);
			$item->name = $cat['name']; 
			$item->url = '?p=Category&id=' . $cat['id'];
			$item->id = $id++;
			$ids2Items[$cat['id']] = $item;
			$ids2Items[$cat['id']]->children = new MenuItemsList(['empty' => 1]);
			
			if (!empty($cat['parent_id'])) {
				$ids2Items[$cat['parent_id']]->children->add($item);
			} else {
				$categoriesMenuItem->children->add($item);
			}
		}
		$item = new MenuItem(['empty' => true]);
		$item->name = "Dodaj nową";
		$item->url = "?p=EditCategory";
		$item->id = $id;
		$categoriesMenuItem->children->add($item);
	}
}