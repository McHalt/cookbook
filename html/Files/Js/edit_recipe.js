document.querySelector('#add_new_ingredient').addEventListener('click', addIngredient);
document.querySelector('#add_category').addEventListener('click', addCategory);
let cats = document.querySelectorAll('.edit-recipe__categories__category select:first-child');
if (cats) {
	cats.forEach(function(e) {
		e.addEventListener('change', showDeeperCategories);
	});
}

function addIngredient(evt) {
	evt.preventDefault();
	let count = document.querySelectorAll('.edit-recipe__ingredients__ingredient').length;
	let mainDiv = document.createElement('div');
	mainDiv.classList.add('edit-recipe__ingredients__ingredient');
	['name/Nazwa', 'amount/Ilość', 'unit/Jednostka'].forEach(
		function(el){
			let elArr = el.split('/');
			let div = document.createElement('div');
			let label = document.createElement('label');
			label.innerText = elArr[1];
			label.for = "ingredients[" + count + "][" + elArr[0] + "]";
			let input = document.createElement('input');
			input.type = 'text';
			input.name = "ingredients[" + count + "][" + elArr[0] + "]";
			input.id = "ingredients[" + count + "][" + elArr[0] + "]";
			input.autocomplete = "off";
			div.appendChild(label);
			div.appendChild(input);
			mainDiv.appendChild(div);
		}
	);
	document.querySelector('.edit-recipe__ingredients').appendChild(mainDiv);
}

function addCategory(evt) {
	evt.preventDefault();
	let count = document.querySelectorAll('.edit-recipe__categories__category').length;
	let mainDiv = document.createElement('div');
	mainDiv.classList.add('edit-recipe__categories__category');
	let select = document.createElement('select');
	select.name = "categories[" + count + "][0]";
	select.id = "categories[" + count + "][0]";
	select.addEventListener('change', showDeeperCategories);
	let option = document.createElement('option');
	option.value = '-1';
	option.innerText = 'Wybierz kategorię';
	select.appendChild(option);
	Object.values(categories).forEach(function(e) {
		let option = document.createElement('option');
		option.value = e.id;
		option.innerText = e.name
		select.appendChild(option);
	});
	mainDiv.appendChild(select);
	document.querySelector('.edit-recipe__categories').appendChild(mainDiv);
}

function showDeeperCategories(evt) {
	let selectedId = evt.target.value;
	let child = document.getElementById(evt.target.name.replace('][0]', '][1]'));
	if (child) {
		child.remove();
	}
	if (selectedId === -1) {
		// tu usuwamy inne wybory
		return;
	}
	if (!categories[selectedId].children) {
		return;
	}
	let select = document.createElement('select');
	select.name = evt.target.name.replace('][0]', '][1]');
	select.id = evt.target.id.replace('][0]', '][1]');
	let option = document.createElement('option');
	option.value = '-1';
	option.innerText = 'Wybierz kategorię';
	select.appendChild(option);
	Object.values(categories[selectedId].children).forEach(function(e) {
		let option = document.createElement('option');
		option.value = e.id;
		option.innerText = e.name
		select.appendChild(option);
	});
	evt.target.parentElement.appendChild(select);
}