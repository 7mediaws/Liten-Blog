<?php if (!defined('BASE_PATH')) exit('No direct script access allowed'); ?>

<?php $app = \Liten\Liten::getInstance(); ?>
<?php $app->view->extend('_layouts/admin'); ?>
<?php $app->view->block('admin'); ?>

<?php foreach ($category as $cat) : ?>
    <div class="container clearfix">
        <form name="form" method="post" action="<?= url('/admin/categories/' . $cat['catID'] . '/'); ?>">
            <div class="box"> 
                <!--Title-->
                <div class="header">
                    <h2>Category</h2>
                </div>
                <!--Content-->
                <div class="content padding">
                    <?= (isset($_COOKIE['cat_status']) ? '<div class="content padding clearfix">' . $app->cookies->get('cat_status') . '</div>' : ''); ?>
                    <?php $app->cookies->remove('cat_status'); ?>
                    <div class="accordion">
                        <div class="content">
                            <div class="field">
                                <label>Category:</label>
                                <input name="catName" type="text" class="medium" value="<?= _h($cat['catName']); ?>" required/>
                            </div>
                            <input name="catID" type="hidden" value="<?= _h($cat['catID']); ?>" />
                            <button type="submit" name="Submit">Update Category</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
<?php endforeach; ?>

<?php $app->view->stop(); ?>