
jQuery(document).ready(function($) {
	
	var $usersTableBody = $('#users-table tbody')
	var $detailsModalBody = $('.modal-body')

	initializeTableLoader($usersTableBody)

	$.getJSON('https://jsonplaceholder.typicode.com/users', function(result){ 
		initializeTbody($usersTableBody, result)
	}).fail(function(){
		$usersTableBody.empty()
		$usersTableBody.append(`<tr><td colspan='4'>` + buildErrorElements() + `</td></tr>`)
	})

	$('body').on('click', '.details-view', function() {
		$('#details-modal').click();
		let user_id = $(this).data('user-id')
		initializeModalBody(user_id)
	})

	// functions 

	function initializeModalBody(user_id) {
		initializeModalLoader($detailsModalBody)
		$.getJSON('https://jsonplaceholder.typicode.com/users/' + user_id, function(result){ 
			initializeMbody($detailsModalBody, result)
		}).fail(function(){
			$detailsModalBody.empty()
			$detailsModalBody.append(buildErrorElements())
		})
	}

	function initializeTableLoader(el) {
		html = `<tr>
			<td colspan='5'>
				<p class='loader-container'>
					<img src='inpsyde-wp/../wp-content/plugins/ldps-show-users/assets/images/loader.gif' alt='loader image'>
					Fetching data...
				</p>
			</td>
		</tr>`

		el.append(html)
	}  

	function initializeModalLoader(el) {
		el.empty();
		html = `<p class='loader-container'>
			<img src='inpsyde-wp/../wp-content/plugins/ldps-show-users/assets/images/loader.gif' alt='loader image'>
			Fetching data...
		</p>`

		el.append(html)
	}

	function initializeMbody(el, data) {
		el.empty()	
		let html = buildModalElements(data)
		el.append(html)
	}

	function initializeTbody(el, data) {	
		el.empty()	
		let html = buildTableElements(data)
		el.append(html)
	}

	function buildErrorElements() {
		return `<div class='broken-panel'>
			<img src= 'inpsyde-wp/../wp-content/plugins/ldps-show-users/assets/images/broken.png'>
			Oops... Something went wrong.
		</div>`
	}

	function buildTableElements(data) {
		let html = '';
		for (let i = 0; i < data.length; i++) {
			let id = data[i].id
			html = html + `<tr>
				<td>` + wrapLink(id, id) + `</td>
				<td>` + wrapLink(data[i].name, id) + `</td>
				<td>` + wrapLink(data[i].username, id) + `</td>
				<td>` + wrapLink(data[i].email, id) + `</td>
			<tr>`
		}
		return html
	}

	function buildModalElements(data) {
		return 	`<h6>`+ data.name +`</h6>
			<div>`+ data.company.name +`<br>
			<span>`+ data.company.catchPhrase +`</span></div><hr>
		    <p><span class='boldy'>Username:</span> `+ data.username +`</p>
		    <p><span class='boldy'>Email:</span> `+ data.email +`</p>
		    <p><span class='boldy'>Address:</span> `+ [data.address.street, data.address.suite, data.address.city, data.address.zipcode].join(', ') +`</p>
		    <p><span class='boldy'>Phone:</span> `+ data.phone +`</p>
		    <p><span class='boldy'>Website:</span> `+ data.website +`</p>
		    `
	}

	function wrapLink(item, id) {
		return `<a href='javascript:void(0)' data-user-id='`+id+`' class='details-view'>` + item + `</a>`
	}

});