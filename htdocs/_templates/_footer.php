<footer class="text-muted py-5 mt-100">
	<div class="container">
		<p class="float-end mb-1">
			<a href="#">Back to top</a>
			<?php
			 if(session::isset("session_token")){
				?>
				<a href="login.php?logout">Logout</a><?php
			 }
			?>
			
		</p>
		<p class="mb-1">Album example is © Bootstrap, but please download and customize it for yourself!</p>
		<p class="mb-0">New to Bootstrap? <a href="/">Visit the homepage</a> or read our <a
				href="../getting-started/introduction/">getting started guide</a>.</p>
	</div>
</footer>