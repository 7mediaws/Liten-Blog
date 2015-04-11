<?php if (!defined('BASE_PATH')) exit('No direct script access allowed'); ?>

<?php $app = \Liten\Liten::getInstance(); ?>
<?php $app->view->extend('_layouts/default'); ?>
<?php $app->view->block('default'); ?>

<?php foreach ($post as $d): ?>
    <div class="post">
        <h2><?= _h($d['post_title']); ?></h2>

        <div class="date">
            <?= date('d F Y @ h:m A', strtotime(_h($d['post_date']))); ?> | 
            Category: <a href="<?=url('/'._h($d['catSlug']).'/');?>"><?=_h($d['catName']);?></a>
            <?php if ($app->hook->get_option('disqus_shortname') != '' && $app->hook->is_plugin_activated('disqus.plugin.php')) : ?>
                | <a href="<?= url('/' . _h($d['catSlug']) . '/' . _h($d['post_slug']) . '/'); ?>#disqus_thread">Comments</a>
            <?php endif; ?>
        </div>

        <?= _escape($d['post_content']); ?>

        <?php $app->hook->do_action('post_content_footer'); ?>

    </div>
<?php endforeach; ?>

<?php $app->view->stop(); ?>