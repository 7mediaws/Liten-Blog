<?php if (!defined('BASE_PATH')) exit('No direct script access allowed'); ?>

<?php $app = \Liten\Liten::getInstance(); ?>
<?php $app->view->extend('_layouts/admin'); ?>
<?php $app->view->block('admin'); ?>

<?php foreach ($single as $post) : ?>
    <div class="container clearfix">
        <form name="form" method="post" action="<?= url('/admin/post/' . $post['postID'] . '/'); ?>">
            <div class="box two_third"> 
                <!--Title-->
                <div class="header">
                    <h2>Edit Post</h2>
                </div>
                <!--Content-->
                <div class="content padding">
                    <?= (isset($_COOKIE['post_status']) ? '<div class="content padding clearfix">' . $app->cookies->get('post_status') . '</div>' : ''); ?>
                    <?php $app->cookies->remove('post_status'); ?>
                    <div class="accordion">
                        <div class="content">
                            <div class="field">
                                <label>Post Title:</label>
                                <input name="post_title" type="text" class="medium" value="<?= _h($post['post_title']); ?>" required/>
                            </div>
                            <div class="field">
                                <label>Post Content:</label>
                                <textarea class="tinymce" name="post_content" style="width:98%;height:400px;" required><?= _h($post['post_content']); ?></textarea>
                            </div>
                            <?php $app->hook->do_action('footer_post_form'); ?>
                            <button type="submit" name="Submit">Update Post</button>
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
                                <select class="large" style="width: 200px !important;" name="post_status" required/>
                                <option value=""></option>
                                <option value="publish"<?= selected('publish', _h($post['post_status']), false); ?>>Publish</option>
                                <option value="draft"<?= selected('draft', _h($post['post_status']), false); ?>>Draft</option>
                                </select>
                            </div>
                            <div class="field">
                                <label>Publish Date/Time:</label>
                                <input name="post_date" type="text" class="large" style="width: 200px !important;" value="<?= _h($post['post_date']); ?>" required/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box one_third last"> 
                <!--Title-->
                <div class="header">
                    <h2>Category</h2>
                </div>
                <!--Content-->
                <div class="content padding">
                    <div class="accordion">
                        <div class="content">
                            <div class="field">
                                <select class="large" style="width: 200px !important;" name="catID" required/>
                                <option value=""></option>
                                <?php foreach ($categories as $cat) : ?>
                                    <option value="<?= _h($cat['catID']); ?>"<?= selected(_h($cat['catID']), _h($post['catID']), false); ?>><?= _h($cat['catName']); ?></option>
                                <?php endforeach; ?>
                                </select>
                            </div>
                            <input name="postID" type="hidden" value="<?= _h($post['postID']); ?>">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
<?php endforeach; ?>

<?php $app->view->stop(); ?>