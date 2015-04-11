<?php if (!defined('BASE_PATH')) exit('No direct script access allowed'); ?>

<?php $app = \Liten\Liten::getInstance(); ?>
<?php $app->view->extend('_layouts/admin'); ?>
<?php $app->view->block('admin'); ?>

<div class="container clearfix">
    <form name="form" method="post" action="<?= url('/admin/categories/'); ?>">
        <div class="box one_third"> 
            <!--Title-->
            <div class="header">
                <h2>Add Category</h2>
            </div>
            <!--Content-->
            <div class="content padding">
                <?= (isset($_COOKIE['cat_status']) ? '<div class="content padding clearfix">' . $app->cookies->get('cat_status') . '</div>' : ''); ?>
                <?php $app->cookies->remove('cat_status'); ?>
                <div class="accordion">
                    <div class="content">
                        <div class="field">
                            <label>Category:</label>
                            <input name="catName" type="text" class="large" style="width: 200px !important;" value="<?= _h($post['catName']); ?>" required/>
                        </div>
                        <button type="submit" name="Submit">Save Category</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="box two_third last"> 
        <!--Title-->
        <div class="header">
            <h2>Categories</h2>
        </div>
        <!--Content-->
        <div class="content padding">
            <table class="datatable">
                <thead>
                    <tr>
                        <th class="sorting"> Cat ID </th>
                        <th class="sorting"> Category </th>
                        <th class="sorting"> Slug </th>
                        <th class="sorting"> Action </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $cat) : ?>
                        <tr class="gradeA">
                            <td><?= _h($cat["catID"]); ?></td>
                            <td><a href="<?= url('/admin/categories/' . $cat['catID'] . '/'); ?>"><?= _h($cat["catName"]); ?></a></td>
                            <td><?= _h($cat["catSlug"]); ?></td>
                            <td><a href="<?= url('/admin/categories/d/' . $cat['catID'] . '/'); ?>">Delete</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $app->view->stop(); ?>