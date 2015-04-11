<?php if (!defined('BASE_PATH')) exit('No direct script access allowed'); ?>

<?php $app = \Liten\Liten::getInstance(); ?>
<?php $app->view->extend('_layouts/default'); ?>
<?php $app->view->block('default'); ?>

<?php foreach ($posts as $d): ?>
    <div class="post">
        <h2><a href="<?=url('/'._h($d['catSlug']).'/'._h($d['post_slug']).'/');?>"><?=_h($d['post_title']);?></a></h2>

        <div class="date">
            <?=date('d F Y @ h:m A', strtotime(_h($d['post_date'])));?> | 
            Category: <a href="<?=url('/'._h($d['catSlug']).'/');?>"><?=_h($d['catName']);?></a>
            <?php if ($app->hook->get_option('disqus_shortname') != '' && $app->hook->is_plugin_activated('disqus.plugin.php')) : ?>
                | <a href="<?= url('/' . _h($d['catSlug']) . '/' . _h($d['post_slug']) . '/'); ?>#disqus_thread">Comments</a>
            <?php endif; ?>
        </div>

        <?=safe_truncate(_escape($d['post_content']),225,' <a href="'.url('/'._h($d['catSlug']).'/'._h($d['post_slug']).'/').'">Read more . . .</a>');?>

        <?=$page->render();?>
    </div>
<?php endforeach; ?>

<?php $app->view->stop(); ?>