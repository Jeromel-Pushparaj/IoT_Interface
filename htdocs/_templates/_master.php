<!doctype html>
<html lang="en">
<?php Session::loadTemplate('_head'); ?>

<body>

	<?php Session::loadTemplate('_header'); ?>
	<main>
		<?php
        if (Session::$isError) {
            Session::loadTemplate('_error');
        } else {
            Session::loadTemplate(Session::currentScript());
        }
?>
	</main>
	<?php Session::loadTemplate('_footer'); ?>
	<script
		src="<?=get_config('base_path')?>assets/dist/js/bootstrap.bundle.min.js">
	</script>
	<?php if(file_exists($_SERVER['DOCUMENT_ROOT']."/js/".basename($_SERVER['PHP_SELF'], '.php').".js")) { ?>
	<script src="/js/<?=basename($_SERVER['PHP_SELF'], '.php');?>.js"></script>
	<?php }?>


</body>

</html>