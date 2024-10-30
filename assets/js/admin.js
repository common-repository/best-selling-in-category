var formId = 'ctm-setting-form';
if ( document.getElementById( formId ) ) {
	var form = document.getElementById( formId );
	form.addEventListener( 'click', ( event ) => {

		if ( event.target.classList.contains( 'item-check' ) ) {
			if ( event.target.classList.contains( 'selected' ) ) {
				event.target.classList.remove( 'selected' );
				event.target.previousElementSibling.checked = false;
			} else {
				event.target.classList.add( 'selected' );
				event.target.previousElementSibling.checked = true;
			}
		}
		
	});
}

document.addEventListener( 'click', ( event ) => {
	
	if ( event.target.classList.contains( 'add-next-rule' ) ) {

		var operandDiv = event.target.previousElementSibling;
		var operandSelect = operandDiv.getElementsByTagName( 'select' );
		var operand = operandSelect[0].value;
		var label = operandSelect[0].options[operandSelect[0].selectedIndex].text;

		console.log( operand );
		console.log( label );


		var htmlString = '<div class="admin-box-line line-h-1-1-1"><div class="admin-box-item line-h-1-2"><label class="operant-type" data-operand="a">Type</label>'+ruleTypeSelect+'</div><div class="admin-box-item line-h-1-2"><label>Operator</label>'+basicRuleOperator+'</div><div class="admin-box-item item-with-remove">'+rolesSelect+'<div class="remove-rule"></div></div></div>';
		event.target.parentElement.parentElement.insertAdjacentHTML( 'beforebegin', htmlString );
	}
	if ( event.target.classList.contains( 'remove-rule' ) ) {
		event.target.parentElement.parentElement.remove();
	}
	
});
if ( document.getElementById( 'add-new-rule' ) ) {
	var addNewRuleWrap = document.getElementById( 'add-new-rule' );
	addNewRuleWrap.addEventListener( 'change', ( event ) => {
		
			
		if ( event.target.classList.contains( 'rule-type' ) ) {
			var ruleType = event.target.value;
			console.log( ruleType );
			var targetDiv = event.target.parentElement.nextElementSibling;
			var targetSelect = targetDiv.getElementsByTagName( 'select' );
			var inputTargetDiv = event.target.parentElement.nextElementSibling.nextElementSibling;
			var targetRemove = inputTargetDiv.getElementsByTagName( 'div' );
			console.log( removeButton )
			
			if ( ruleType == 'price' ) {
				targetSelect[0].remove();
				targetDiv.insertAdjacentHTML( 'beforeend', priceRuleOperator );				
				if ( targetRemove.length > 0 ) {
					inputTargetDiv.innerHTML = priceInput+removeButton;
				} else {
					inputTargetDiv.innerHTML = priceInput;
				}
			} else if ( ruleType == 'user-role' ) {
				targetSelect[0].remove();
				targetDiv.insertAdjacentHTML( 'beforeend', basicRuleOperator );
				if ( targetRemove.length > 0 ) {
					inputTargetDiv.innerHTML = rolesSelect+removeButton;
				} else {
					inputTargetDiv.innerHTML = rolesSelect;
				}
			} else if
			 ( ruleType == 'product-category' ) {
				targetSelect[0].remove();
				targetDiv.insertAdjacentHTML( 'beforeend', basicRuleOperator );
				if ( targetRemove.length > 0 ) {
					inputTargetDiv.innerHTML = categorySelect+removeButton;
				} else {
					inputTargetDiv.innerHTML = categorySelect;
				}
			}
		}
});

}
