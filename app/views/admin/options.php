<?php if (!defined('BASE_PATH')) exit('No direct script access allowed'); ?>

<?php $app = \Liten\Liten::getInstance(); ?>
<?php $app->view->extend('_layouts/admin'); ?>
<?php $app->view->block('admin'); ?>

<?php
// Handle plugin admin pages
if (isset($_GET['page']) && !empty($_GET['page'])) {
    $app->hook->plugin_admin_page($_GET['page']);
}

?>

<?php $app->view->stop(); ?>