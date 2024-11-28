<?php
// include '_libs/include/UserSession.class.php';
$login_page  = true;

//TODO: Redirect to a requested URL instead of base path on login_page 
if (isset($_POST['username']) and isset($_POST['password'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  Session::start();
  $result = UserSession::authenticate($username, $password);
  $login_page  = false;
}

if (!$login_page) {
    if ($result) {
        $should_redirect = Session::get('_redirect');
        $redirect_to = get_config('base_path');
        if (isset($should_redirect)) {
            $redirect_to = $should_redirect;
			Session::set('_redirect', false);
		}

    ?>
	<script>	
		window.location.href = "<?=$redirect_to?>"
	</script>
	<!-- <script>window.location.href = ""</script> -->
	<?php
	} else {
		?>
		<script>
			window.location.href = "/login.php?error=1"
		</script>
		
		<?php
	}
} else {
    ?>
<div class="album py-5 bg-light">
	<main class="form-signin w-100 m-auto align-items-center">


		<form method="post" action="login.php">
			<img class="mb-4"
				src="https://th.bing.com/th/id/OIP.wqxTxU-160K2ewL--2H2qwHaEK?w=314&h=180&c=7&r=0&o=5&pid=1.7"
				alt="logo" width="250" height="150">
			<h1 class="h3 mb-3 fw-normal">Please sign in</h1>
			<?
		if(isset($_GET['error'])){
			?>
			<div class="alert alert-danger" role="alert">
				Invalid Credentials
			</div>
			<?
		}
		?>


			<div class="form-floating">
				<input type="text" class="form-control" id="floatingInput" placeholder="user_name" name="username">
				<label for="floatingInput">UserName</label>
			</div>
			<input name="fingerprint" type="hidden" id="fingerprint" value="">
			<div class="form-floating">
				<input type="password" class="form-control" id="floatingPassword" placeholder="Password"
					name="password">
				<label for="floatingPassword">Password</label>
			</div>

			<div class="form-check text-start my-3">
				<input class="form-check-input" type="checkbox" value="remember-me" id="flexCheckDefault">
				<label class="form-check-label" for="flexCheckDefault">
					Remember me
				</label>
			</div>
			<button class="btn btn-primary w-100 py-2" type="submit">Sign in</button>
			<a href="/signup.php" class="w-100 btn btn-link">Not registered? Sign up</a>
		</form>

	</main>
</div>
<?php
}
?>