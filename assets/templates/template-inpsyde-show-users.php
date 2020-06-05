<?php 
/**
 * Template Name: Inpsyde Template Show Users
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */
?>

<!DOCTYPE html>
<html>
<head>
	<title>Show users</title>
	<?php wp_head() ?>
</head>
<body>
<h2>Show users</h2>
<table id="users-table">
	<thead>
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Username</th>
			<th>Email</th>
		</tr>
	</thead>
	<tbody>
		<!-- Content loaded dynamically -->
	</tbody>
</table>


<input class="modal-state" id="details-modal" type="checkbox" />
<div class="modal">
	<label class="modal__bg" for="details-modal"></label>
		<div class="modal__inner">
			<label class="modal__close" for="details-modal"></label>
			<h5>Details</h5>
			<hr>	
			<div class="modal-body">
				<!-- Content loaded dynamically -->
			</div>
		</div>
</div>

<?php wp_footer(); ?>
</body>
</html>


