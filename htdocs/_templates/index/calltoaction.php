<section class="d-flex justify-content-center align-items-center vh-100">
	<div class="row py-lg-5 w-75">
			<div class="col-lg-8 col-md-10 mx-auto p-4 border rounded-1 text-center shadow">
				<h1 class="fw-bold">Add and Access in same place, <?= htmlspecialchars(Session::getUser()->getUsername()) ?></h1>
				<p class="lead text-primary">Connect and control it all in one place</p>
				<p>
        <a href="/device" class="btn btn-dark my-2">Add Devices</a>
        <a href="/control" class="btn btn-dark my-2">Control Devices</a>
				</p>
			</div>
	</div>
</section>
