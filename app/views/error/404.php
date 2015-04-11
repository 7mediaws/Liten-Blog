<?php if (!defined('BASE_PATH')) exit('No direct script access allowed'); ?>

<?php $app = \Liten\Liten::getInstance(); ?>
<?php $app->view->extend('_layouts/default'); ?>
<?php $app->view->block('default'); ?>

	<div class="center message">
		<h1>This page doesn't exist!</h1>
		<p>Would you like to try our <a href="<?=url('/');?>">homepage</a> instead?</p>
	</div>

<?php $app->view->stop(); ?>