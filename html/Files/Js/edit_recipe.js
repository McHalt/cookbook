document.querySelector('#add_new_ingredient').addEventListener('click', addIngredient);

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