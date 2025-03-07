<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="Jeromel pusparaj" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
	<meta name="generator" content="Hugo 0.84.0">


	<link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/album/">
	<link rel="stylesheet" href="css/main.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
	<link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/headers/">

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<title>
		IoT_Interface
	</title>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script>
    // Initialize the agent at application startup.
    const fpPromise = import('https://openfpcdn.io/fingerprintjs/v3')
        .then(FingerprintJS => FingerprintJS.load())

    // Get the visitor identifier when you need it.
    fpPromise
        .then(fp => fp.get())
        .then(result => {
        // This is the visitor identifier:
        const visitorId = result.visitorId
        console.log(visitorId)
        $('#fingerprint').val(visitorId);
        })
    </script>

	<!-- Bootstrap core CSS -->
	<link href="/assets/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="icon" type="image/x-icon" href="assets/brand/Logo.png">

	<?php if(file_exists($_SERVER['DOCUMENT_ROOT']."/css/".basename($_SERVER['PHP_SELF'], '.php').".css")) {?>
	<link href="/css/<?= basename($_SERVER['PHP_SELF'], '.php');?>.css" rel="stylesheet">

	<?}?>



</head>