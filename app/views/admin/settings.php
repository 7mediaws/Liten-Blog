<?php if (!defined('BASE_PATH')) exit('No direct script access allowed'); ?>

<?php $app = \Liten\Liten::getInstance(); ?>
<?php $app->view->extend('_layouts/admin'); ?>
<?php $app->view->block('admin'); ?>

<div class="container clearfix">
    <form name="form" method="post" action="<?= url('/admin/settings'); ?>">
        <div class="box"> 
            <!--Title-->
            <div class="header">
                <h2>General settings</h2>
            </div>
            <!--Content-->
            <div class="content padding">
                <div class="accordion">
                    <div class="content">
                        <div class="field">
                            <label>Blog Name:</label>
                            <input name="blog_title" type="text" class="medium" value="<?= $app->hook->get_option('blog_title'); ?>" required/>
                        </div>
                        <div class="field">
                            <label>Blog Description (html allowed):</label>
                            <textarea name="blog_description" style="width:98%;height:120px;"><?= $app->hook->get_option('blog_description'); ?></textarea>
                        </div>
                        <div class="field">
                            <label>Author Bio (html allowed):</label>
                            <textarea name="blog_authorbio" style="width:98%;height:120px;"><?= $app->hook->get_option('blog_authorbio'); ?></textarea>
                        </div>
                        <div class="field">
                            <label>API Key:</label>
                            <input name="api_key" type="text" class="medium" value="<?= $app->hook->get_option('api_key'); ?>" required/>
                        </div>
                        <div class="field">
                            <label># Posts to Show:</label>
                            <input name="num_posts" type="text" class="medium" value="<?= $app->hook->get_option('num_posts'); ?>" required/>
                        </div>
                        <button type="submit" name="Submit">Save Settings</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php $app->view->stop(); ?>