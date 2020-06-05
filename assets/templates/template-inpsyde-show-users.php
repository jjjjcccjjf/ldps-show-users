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
<?php wp_head() ?>
<head>
	<title>Show users</title>
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
<!-- 		<tr>
			<td colspan='5'>
				<p>
					<img src='<?php echo site_url() ?>/../wp-content/plugins/inpsyde-show-users/assets/images/loader.gif' alt='loader image'>
					Fetching data...
				</p>
			</td>
		</tr> -->
	</tbody>
</table>

<?php wp_footer(); ?>
</body>
</html>


<!-- <script src="./../wp-content/plugins/inpsyde-show-users/assets/js/script.js"></script> -->