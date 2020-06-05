
jQuery(document).ready(function($) {
	
	var $usersTableBody = $('#users-table tbody')

	initializeLoader($usersTableBody)

	$.getJSON('https://jsonplaceholder.typicode.com/users', function(result){ 
		initializeTbody($usersTableBody, result)
	})


	// functions 

	function initializeLoader(el) {
		console.log(el)
		html = `<tr>
			<td colspan='5'>
				<p>
					<img src='inpsyde-wp/../wp-content/plugins/inpsyde-show-users/assets/images/loader.gif' alt='loader image'>
					Fetching data...
				</p>
			</td>
		</tr>`

		el.append(html)
	}

	function initializeTbody(el, data) {
		el.empty()	
		let html = 	buildTableElements(data)
		el.append(html)
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

	function wrapLink(item, id) {
		return `<a href='javascript:void(0)' data-user-id='`+id+`' class='details-view'>` + item + `</a>`
	}


});