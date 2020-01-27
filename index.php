<?php
	session_start();

	if ( !isset($_SESSION['user_id']) ):
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="public/css/bootstrap.min.css">
	<title>Ecommerce - Login</title>
</head>
<body>
	<div class="container flex-container">
		<div class="flex-center">
			<div class="card" style="width: 25rem; margin:auto">
				<div class="card-header">
					<h3 class="card-title">Sign in</h3>
				</div>
				<div class="card-body">
					<div class="form-group">
						<label for="username">username</label>
						<input type="email" class="form-control" id="username">

						<label for="password">password</label>
						<input type="email" class="form-control" id="password">
					</div>
					<button class="btn btn-block btn-primary" onclick="send_data()">Login</button>
				</div>
			</div>
		</div>
	</div>

	<style>
		.flex-container {
			display: flex;
			align-items: center;
			min-height: 24em;
			justify-content: center;
		}

		.flex-center {
			flex: 1;
		}
	</style>

	<script src="public/js/jquery.min.js"></script>
	<script>
		function send_data () {
			let username = $('#username').val()
			let password = $('#password').val()
			$.post(
				'./controller/user.php?option=authenticate', 
				{'username': username, 'pass': password}, 
				function (res) {
					if (typeof(res.error) != 'undefined') {
						alert(res.error)
						return;
					}
					
					if (typeof(res.data) != 'undefined') {
						localStorage.setItem('user_id', res.data.id);
						localStorage.setItem('cash', res.data.cash);
						localStorage.setItem('username', res.data.username);
						
						window.location = 'webapp.php';
					}
				}
			)
		}
	</script>
</body>
</html>

<?php
	else:
		header('Location: webapp.php');
	endif;
?>