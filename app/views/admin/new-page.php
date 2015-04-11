<?php if (!defined('BASE_PATH')) exit('No direct script access allowed'); ?>

<?php $app = \Liten\Liten::getInstance(); ?>
<?php $app->view->extend('_layouts/admin'); ?>
<?php $app->view->block('admin'); ?>

<div class="container clearfix">
    <form name="form" method="post" action="<?= url('/admin/page/new/'); ?>">
        <div class="box two_third"> 
            <!--Title-->
            <div class="header">
                <h2>Create Page</h2>
            </div>
            <!--Content-->
            <div class="content padding">
                <?= (isset($_COOKIE['page_status']) ? '<div class="content padding clearfix">' . $app->cookies->get('page_status') . '</div>' : ''); ?>
                <?php $app->cookies->remove('page_status'); ?>
                <div class="accordion">
                    <div class="content">
                        <div class="field">
                            <label>Page Title:</label>
                            <input name="page_title" type="text" class="medium" value="<?= $app->req->_post('page_title'); ?>" required/>
                        </div>
                        <div class="field">
                            <label>Page Content:</label>
                            <textarea class="tinymce" name="page_content" style="width:98%;height:400px;"><?= $app->req->_post('page_content'); ?></textarea>
                        </div>
                        <?php $app->hook->do_action('footer_page_form'); ?>
                        <button type="submit" name="Submit">Save Page</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="box one_third last"> 
            <!--Title-->
            <div class="header">
                <h2>Publish</h2>
            </div>
            <!--Content-->
            <div class="content padding">
                <div class="accordion">
                    <div class="content">
                        <div class="field">
                            <label>Visibility:</label> 
                            <select class="large" style="width: 200px !important;" name="page_status" required/>
                            <option value=""></option>
                            <option value="publish"<?=selected('publish',$app->req->_post('page_status'),false);?>>Publish</option>
                            <option value="draft"<?=selected('draft',$app->req->_post('page_status'),false);?>>Draft</option>
                            </select>
                        </div>
                        <div class="field">
                            <label>Publish Date/Time:</label>
                            <input name="page_date" type="text" class="large" style="width: 200px !important;" value="<?= $app->req->_post('page_date'); ?>" required/>
                        </div>
                        <div class="field">
                            <label>Page Order:</label>
                            <input name="page_sort" type="text" class="large" value="<?= $app->req->_post('page_sort'); ?>" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php $app->view->stop(); ?>