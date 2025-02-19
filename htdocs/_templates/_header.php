<?php
if (isset($_GET['logout'])) {
    if (Session::isset("session_token")) {
        $Session = new UserSession(Session::get("session_token"));
        if ($Session->removeSession()) {
		?>
			<script>window.location.href = "<?=get_config('base_path')?>"</script>
		<?php
        } else {
			
            echo "<h3>Pervious Session not removing from db </h3>";
        }
    }
    Session::destroy();
    die("Session destroyed, <a href='logintest2.php'>Login Again</a>");
}

?>
<header>
	<div class="collapse bg-dark" id="navbarHeader">
		<div class="container">
			<div class="row">
				<div class="col-sm-8 col-md-7 py-4">
					<h4 class="text-white"></h4>
					<p class="text-muted"></p>
				</div>
				<div class="col-sm-4 offset-md-1 py-4">
					<h4 class="text-white">Contact</h4>
					<ul class="list-unstyled">
						<li><a href="#" class="text-white">Follow on Twitter</a></li>
						<li><a href="#" class="text-white">Like on Facebook</a></li>
						<?if(Session::isAuthenticated()){?>
						<li><a href="/?logout" class="text-white">Logout</a></li>
						<?} else {?>
							<li><a href="/login.php" class="text-white">Login</a></li>
						<?}?>

					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="navbar navbar-dark bg-dark shadow-sm">
		<div class="container">
			<a href="/" class="navbar-brand d-flex align-items-center">
			<!-- <img src="../assets/computer-hardware-monitor-svgrepo-com.svg" alt="Description of SVG"> -->
				<!-- <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor"
					stroke-linecap="round" stroke-linejoin="round" stroke-width="2" aria-hidden="true" class="me-2"
					viewBox="0 0 24 24">
					<path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z" />
					<circle cx="12" cy="13" r="4" />
				</svg> -->
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor"
    stroke-linecap="round" stroke-linejoin="round" stroke-width="2" aria-hidden="true" class="me-2"
    viewBox="0 0 24 24">
    <rect x="3" y="4" width="18" height="14" rx="2" ry="2" />
    <line x1="8" y1="20" x2="16" y2="20" />
    <line x1="12" y1="16" x2="12" y2="20" />
				</svg>

				<strong>IoT_Interface</strong>
			</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader"
				aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
		</div>
	</div>

	
</header>

