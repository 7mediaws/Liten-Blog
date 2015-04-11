<?php if (!defined('BASE_PATH')) exit('No direct script access allowed'); ?>

<?php $app = \Liten\Liten::getInstance(); ?>
<?php $app->view->extend('_layouts/admin'); ?>
<?php $app->view->block('admin'); ?>

<div class="container clearfix"> 
    <!--Statistics-->
    <div class="box"> 
        <!--Title-->
        <div class="header">
            <h2>Liten Blog Platform</h2>
        </div>
        <!--Content-->
        <div class="content padding">
            <!--Table-->
            <strong><?= 'Welcome to Liten Blog.'; ?> </strong> This blog platform is a sample project to show 
            what can be done with the <a href="http://www.litenframework.com/">Liten Microframework</a>. Feel free to use this as an example or extend it to use 
            in your next project or for personal use.
        </div>
    </div>
</div>

<?php $app->view->stop(); ?>