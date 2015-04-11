<?php if (!defined('BASE_PATH')) exit('No direct script access allowed'); ?>

<?php $app = \Liten\Liten::getInstance(); ?>
<?php $app->view->extend('_layouts/admin'); ?>
<?php $app->view->block('admin'); ?>

<div class="container clearfix"> 
    <div class="box"> 
        <!--Title-->
        <div class="header">
            <h2>Posts</h2>
        </div>
        <!--Content-->
        <div class="content padding">
            <?= (isset($_COOKIE['post_status']) ? '<div class="content padding clearfix">' . $app->cookies->get('post_status') . '</div>' : ''); ?>
            <?php $app->cookies->remove('post_status'); ?>
            <form action="" method="post" name="">
                <table class="datatable">
                    <thead>
                        <tr>
                            <th class="sorting"> Post ID </th>
                            <th class="sorting"> Status </th>
                            <th class="sorting"> Title </th>
                            <th class="sorting"> Date</th>
                            <th class="sorting"> Category </th>
                            <th class="sorting"> Last Modified </th>
                            <th class="sorting"> Delete </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data as $post) : ?>
                        <tr class="gradeA">
                            <td><?=_h($post["postID"]);?></td>
                            <td><?=ucfirst(_h($post["post_status"]));?></td>
                            <td><a href="<?=url('/admin/post/'.$post['postID'].'/');?>"><?=_h($post["post_title"]);?></a></td>
                            <td><?=date('d F Y h:m A', strtotime(_h($post["post_date"])));?></td>
                            <td><?=_h($post["catName"]);?></td>
                            <td><?=date('d F Y h:m A', strtotime(_h($post["last_modified"])));?></td>
                            <td><a href="<?=url('/admin/post/d/'.$post['postID'].'/');?>">Delete</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>

<?php $app->view->stop(); ?>