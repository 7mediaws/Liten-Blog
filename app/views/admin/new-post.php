<?php if (!defined('BASE_PATH')) exit('No direct script access allowed'); ?>

<?php $app = \Liten\Liten::getInstance(); ?>
<?php $app->view->extend('_layouts/admin'); ?>
<?php $app->view->block('admin'); ?>

<div class="container clearfix">
    <form name="form" method="post" action="<?= url('/admin/post/new/'); ?>">
        <div class="box two_third"> 
            <!--Title-->
            <div class="header">
                <h2>Create Post</h2>
            </div>
            <!--Content-->
            <div class="content padding">
                <?= (isset($_COOKIE['post_status']) ? '<div class="content padding clearfix">' . $app->cookies->get('post_status') . '</div>' : ''); ?>
                <?php $app->cookies->remove('post_status'); ?>
                <div class="accordion">
                    <div class="content">
                        <div class="field">
                            <label>Post Title:</label>
                            <input name="post_title" type="text" class="medium" value="<?= $app->req->_post('post_title'); ?>" required/>
                        </div>
                        <div class="field">
                            <label>Post Content:</label>
                            <textarea class="tinymce" name="post_content" style="width:98%;height:400px;"><?= $app->req->_post('post_content'); ?></textarea>
                        </div>
                        <?php $app->hook->do_action('footer_post_form'); ?>
                        <button type="submit" name="Submit">Save Post</button>
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
                            <option value="publish"<?=selected('publish',$app->req->_post('post_status'),false);?>>Publish</option>
                            <option value="draft"<?=selected('draft',$app->req->_post('post_status'),false);?>>Draft</option>
                            </select>
                        </div>
                        <div class="field">
                            <label>Publish Date/Time:</label>
                            <input name="post_date" type="text" class="large" style="width: 200px !important;" value="<?= $app->req->_post('post_date'); ?>" required/>
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
                            <?php foreach($categories as $cat) : ?>
                            <option value="<?=_h($cat['catID']);?>"<?=selected(_h($cat['catID']),$app->req->_post('catID'),false);?>><?=_h($cat['catName']);?></option>
                            <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?php $app->view->stop(); ?>