<?php if (!defined('BASE_PATH')) exit('No direct script access allowed'); ?>

<?php $app = \Liten\Liten::getInstance(); ?>
<?php $app->view->extend('_layouts/admin'); ?>
<?php $app->view->block('admin'); ?>

<div class="container clearfix"> 
    <div class="box"> 
        <!--Title-->
        <div class="header">
            <h2>Pages</h2>
        </div>
        <!--Content-->
        <div class="content padding">
            <?= (isset($_COOKIE['page_status']) ? '<div class="content padding clearfix">' . $app->cookies->get('page_status') . '</div>' : ''); ?>
            <?php $app->cookies->remove('page_status'); ?>
            <form action="" method="post" name="">
                <table class="datatable">
                    <thead>
                        <tr>
                            <th class="sorting"> Page ID </th>
                            <th class="sorting"> Status </th>
                            <th class="sorting"> Title </th>
                            <th class="sorting"> Date</th>
                            <th class="sorting"> Last Modified </th>
                            <th class="sorting"> Delete </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data as $page) : ?>
                        <tr class="gradeA">
                            <td><?=_h($page["pageID"]);?></td>
                            <td><?=ucfirst(_h($page["page_status"]));?></td>
                            <td><a href="<?=url('/admin/page/'.$page['pageID'].'/');?>"><?=_h($page["page_title"]);?></a></td>
                            <td><?=date('d F Y h:m A', strtotime(_h($page["page_date"])));?></td>
                            <td><?=date('d F Y h:m A', strtotime(_h($page["last_modified"])));?></td>
                            <td><a href="<?=url('/admin/page/d/'.$page['pageID'].'/');?>">Delete</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>

<?php $app->view->stop(); ?>