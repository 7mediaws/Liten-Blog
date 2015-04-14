<?xml version="1.0" encoding="UTF-8" ?>
<?php if (!defined('BASE_PATH')) exit('No direct script access allowed'); ?>

<?php $app = \Liten\Liten::getInstance(); ?>
<rss version="2.0">
    <channel>
        <title><?=_h($app->hook->get_option('blog_title'));?></title>
        <link><?=url('/');?></link>
        <description><?=_h($app->hook->get_option('blog_description'));?></description>
        <?php foreach($rss as $rss2) : ?>
        <item>
            <title><?=_h($rss2['post_title']);?></title>
            <link><?=_h( url('/' . _h($rss2['catSlug']) . '/' . _h($rss2['post_slug']) . '/') );?></link>
            <description><?=_h($rss2['post_content']);?></description>
        </item>
        <?php endforeach; ?>
    </channel>
</rss>
