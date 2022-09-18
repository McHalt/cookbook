<?php

namespace Models\Scrapers;

use Models\Objects\Ingredient;

class KwestiaSmaku extends Base
{
	
	public function prepareHTML($html)
	{
		return str_replace(
			[
				'class="field field-name-field-przygotowanie',
				'<h1 class="przepis page-header">',
				'class="view view-zdjecia view-id-zdjecia',
				'class="field field-name-field-skladniki'
			],
			[
				'id="przygotowanie" class="field field-name-field-przygotowanie',
				'<h1 id="przepis" class="przepis page-header">',
				'id="view-id-zdjecia" class="view view-zdjecia view-id-zdjecia',
				'id="skladniki" class="field field-name-field-skladniki'
			],
			$html
		);
	}
	
	public function getContent()
	{
		$i = 0;
		$text = '';
		/** @var \DOMElement $node */
		foreach ($this->document->getElementById('przygotowanie')->childNodes as $node) {
			if ($i++ != 1) {
				continue;
			}
			
			if (!$node->hasChildNodes()) {
				return $node->textContent;
			}
			
			$j = 1;
			/** @var \DOMElement $node2 */
			foreach ($node->childNodes as $node2) {
				if (empty(trim($node2->textContent))) {
					continue;
				}
				$text .= $j++ . ". " . trim($node2->textContent) . "\n\n";
			}
		}
		return trim($text);
	}
	
	public function getImage()
	{
		foreach(
			$this->document
			->getElementById('view-id-zdjecia')
			->getElementsByTagName('img')->item(0)->attributes
		as $attr) {
			if ($attr->name == 'src') {
				return $attr->value;
			}
		}
		return '';
	}
	
	public function getIngredients()
	{
		$ingredient = new Ingredient();
		$ingredients = [];
		$i = 0;
		/** @var \DOMElement $node */
		foreach ($this->document->getElementById('skladniki')->childNodes as $node) {
			if ($i++ != 1) {
				continue;
			}
			/** @var \DOMElement $node2 */
			foreach ($node->childNodes as $node2) {
				if (empty(trim($node2->textContent))) {
					continue;
				}
				$elements = explode(' ', trim($node2->textContent));
				if (!is_numeric($elements[0])) {
					$ingredients[] = [
						'name' => implode(' ', $elements),
						'amount' => 1,
						'unit' => 'szt'
					];
					continue;
				}
				$amount = $elements[0];
				unset($elements[0]);
				if (
					in_array($elements[1], $ingredient->baseUnits)
					|| in_array($elements[1], array_keys($ingredient->units2MultiplierNBaseUnit))
				) {
					$unit = $elements[1];
					unset($elements[1]);
				} else {
					$unit = "szt";
				}
				$name = implode(" ", $elements);
				$ingredients[] = [
					'name' => strtolower($name),
					'amount' => $amount,
					'unit' => $unit
				];
			}
		}
		return $ingredients;
	}
	
	public function getName()
	{
		return $this->document->getElementById('przepis')->textContent;
	}
}