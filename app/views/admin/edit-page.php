<?php if (!defined('BASE_PATH')) exit('No direct script access allowed'); ?>

<?php $app = \Liten\Liten::getInstance(); ?>
<?php $app->view->extend('_layouts/admin'); ?>
<?php $app->view->block('admin'); ?>

<?php foreach ($single as $page) : ?>
    <div class="container clearfix">
        <form name="form" method="post" action="<?= url('/admin/page/' . $page['pageID'] . '/'); ?>">
            <div class="box two_third"> 
                <!--Title-->
                <div class="header">
                    <h2>Edit Page</h2>
                </div>
                <!--Content-->
                <div class="content padding">
                    <?= (isset($_COOKIE['page_status']) ? '<div class="content padding clearfix">' . $app->cookies->get('page_status') . '</div>' : ''); ?>
                    <?php $app->cookies->remove('page_status'); ?>
                    <div class="accordion">
                        <div class="content">
                            <div class="field">
                                <label>Page Title:</label>
                                <input name="page_title" type="text" class="medium" value="<?= _h($page['page_title']); ?>" required/>
                            </div>
                            <div class="field">
                                <label>Page Content:</label>
                                <textarea class="tinymce" name="page_content" style="width:98%;height:400px;" required><?= _h($page['page_content']); ?></textarea>
                            </div>
                            <?php $app->hook->do_action('footer_page_form'); ?>
                            <input name="pageID" type="hidden" value="<?= _h($page['pageID']); ?>">
                            <button type="submit" name="Submit">Update Page</button>
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
                                <option value="publish"<?= selected('publish', _h($page['page_status']), false); ?>>Publish</option>
                                <option value="draft"<?= selected('draft', _h($page['page_status']), false); ?>>Draft</option>
                                </select>
                            </div>
                            <div class="field">
                                <label>Publish Date/Time:</label>
                                <input name="page_date" type="text" class="large" style="width: 200px !important;" value="<?= _h($page['page_date']); ?>" required/>
                            </div>
                            <div class="field">
                                <label>Page Order:</label>
                                <input name="page_sort" type="text" class="large" value="<?= _h($page['page_sort']); ?>" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
<?php endforeach; ?>

<?php $app->view->stop(); ?>