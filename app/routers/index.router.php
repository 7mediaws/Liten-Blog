<?php
if (!defined('BASE_PATH'))
    exit('No direct script access allowed');

/**
 * Function that returns are categories that
 * are not empty.
 */
function get_categories()
{
    $app = \Liten\Liten::getInstance();
    $categories = $app->inst->db->categories()
        ->select('DISTINCT categories.catName,categories.catSlug')
        ->_join('posts', 'categories.catID = posts.catID')
        ->where("posts.catID <> ''");
    $q = $categories->find(function($data) {
        foreach ($data as $d) {
            echo '<li><a href="' . url('/' . $d['catSlug']) . '/">' . $d['catName'] . '</a></li>';
        }
    });
}

/**
 * Retrieves archives group by the year.
 */
function get_archives()
{
    $app = \Liten\Liten::getInstance();
    $archives = $app->inst->db->posts()->select('YEAR(posts.post_date) as archive')
        ->groupBy('YEAR(posts.post_date)')
        ->orderBy('YEAR(posts.post_date)', 'DESC');
    $q = $archives->find(function($data) {
        foreach ($data as $d) {
            echo '<li><a href="' . url('/' . $d['archive']) . '/">' . $d['archive'] . '</a></li>';
        }
    });
}
/**
 * Group router
 */
$app->group('', function() use ($app, $orm) {

    /**
     * Blog root
     */
    $app->get('/', function () use($app, $orm) {

        $records_per_page = $app->hook->get_option('num_posts');
        $paginate = new \app\src\Paginate();

        $rows = $orm->posts()->count('postID');
        $paginate->records($rows);
        $paginate->records_per_page($records_per_page);

        $posts = $orm->posts();
        $select = $posts->select('posts.post_title,posts.post_content')
            ->select('posts.post_date,posts.post_slug')
            ->select('cat.catSlug,cat.catName')
            ->_join('categories', 'posts.catID = cat.catID', 'cat')
            ->where('posts.post_status = "publish"')
            ->_and_()
            ->where('posts.post_date <= ?', $orm->NOW())
            ->orderBy('posts.post_date','DESC')
            ->limit(($paginate->get_pages() - $paginate->get_page()) * $records_per_page . ', ' . $records_per_page);
        $q = $select->find(function($data) {
            $array = [];
            foreach ($data as $d) {
                $array[] = $d;
            }
            return $array;
        });

        $app->view->display('index/index', [ 'posts' => $q, 'page' => $paginate]);
    });

    /**
     * Retrieves posts based on the requested archive year.
     */
    $app->get('/(\d+)/', function ($archive) use($app, $orm) {

        $records_per_page = $app->hook->get_option('num_posts');
        $paginate = new \app\src\Paginate();

        $rows = $orm->posts()->count('postID');
        $paginate->records($rows);
        $paginate->records_per_page($records_per_page);

        $posts = $orm->posts();
        $select = $posts->select('posts.post_title,posts.post_content')
            ->select('posts.post_date,posts.post_slug')
            ->select('cat.catSlug,cat.catName')
            ->_join('categories', 'posts.catID = cat.catID', 'cat')
            ->where('YEAR(posts.post_date) = ?', $archive)
            ->_and_()
            ->where('posts.post_status = "publish"')
            ->_and_()
            ->where('posts.post_date <= ?', $orm->NOW())
            ->orderBy('posts.post_date', 'DESC')
            ->limit(($paginate->get_pages() - $paginate->get_page()) * $records_per_page . ', ' . $records_per_page);
        $q = $select->find(function($data) {
            $array = [];
            foreach ($data as $d) {
                $array[] = $d;
            }
            return $array;
        });

        /**
         * If the category doesn't exist, then it
         * is false and a 404 page should be displayed.
         */
        if ($q === false) {
            $app->view->display('error/404', [ 'title' => '404 Not Found']);
        }
        /**
         * If the query is legit, but the
         * the category does not exist, then a 404
         * page should be displayed
         */ elseif (empty($q) === true) {
            $app->view->display('error/404', [ 'title' => '404 Not Found']);
        }
        /**
         * If we get to this point, then all is well
         * and it is ok to process the query and print
         * the results in a jhtml format.
         */ else {
            $app->view->display('index/archives', [ 'archives' => $q, 'title' => $archive . ' Archives', 'page' => $paginate]);
        }
    });

    /**
     * Retrieves posts based on the category selected.
     */
    $app->get('/([a-z0-9_-]+)/', function ($category) use($app, $orm) {

        $records_per_page = $app->hook->get_option('num_posts');
        $paginate = new \app\src\Paginate();

        $rows = $orm->posts()->count('postID');
        $paginate->records($rows);
        $paginate->records_per_page($records_per_page);

        $posts = $orm->posts();
        $select = $posts->select('posts.post_title,posts.post_content')
            ->select('posts.post_date,posts.post_slug')
            ->select('cat.catSlug,cat.catName')
            ->_join('categories', 'posts.catID = cat.catID', 'cat')
            ->where('cat.catSlug = ?', $category)
            ->_and_()
            ->where('posts.post_status = "publish"')
            ->_and_()
            ->where('posts.post_date <= ?', $orm->NOW())
            ->orderBy('posts.post_date', 'DESC')
            ->limit(($paginate->get_pages() - $paginate->get_page()) * $records_per_page . ', ' . $records_per_page);
        $q = $select->find(function($data) {
            $array = [];
            foreach ($data as $d) {
                $array[] = $d;
            }
            return $array;
        });
        foreach ($q as $r) {
            $title = $r['catName'];
        }

        /**
         * If the category doesn't exist, then it
         * is false and a 404 page should be displayed.
         */
        if ($q === false) {
            $app->view->display('error/404', [ 'title' => '404 Not Found']);
        }
        /**
         * If the query is legit, but the
         * the category does not exist, then a 404
         * page should be displayed
         */ elseif (empty($q) === true) {
            $app->view->display('error/404', [ 'title' => '404 Not Found']);
        }
        /**
         * If we get to this point, then all is well
         * and it is ok to process the query and print
         * the results in a jhtml format.
         */ else {
            $app->view->display('index/category', [ 'postscat' => $q, 'title' => $title, 'page' => $paginate]);
        }
    });

    /**
     * Retrieves a single post and includes the category
     * for which it belongs.
     */
    $app->get('/([a-z0-9_-]+)/([a-z0-9_-]+)/', function ($category, $single) use($app, $orm) {

        $post = $orm->posts();
        $select = $post->select('posts.post_title,posts.post_content')
            ->select('posts.post_date,posts.post_slug')
            ->select('cat.catSlug,cat.catName')
            ->_join('categories', 'posts.catID = cat.catID', 'cat')
            ->where('cat.catSlug = ?', $category)
            ->_and_()
            ->where('posts.post_slug = ?', $single)
            ->_and_()
            ->where('posts.post_status = "publish"')
            ->_and_()
            ->where('posts.post_date <= ?', $orm->NOW());
        $q = $select->find(function($data) {
            $array = [];
            foreach ($data as $d) {
                $array[] = $d;
            }
            return $array;
        });
        foreach ($q as $r) {
            $title = $r['post_title'];
        }

        /**
         * If the category and post don't exist, then it
         * is false and a 404 page should be displayed.
         */
        if ($q === false) {
            $app->view->display('error/404', [ 'title' => '404 Not Found']);
        }
        /**
         * If the query is legit, but the
         * category and post don't exist, then a 404
         * page should be displayed.
         */ elseif (empty($q) === true) {
            $app->view->display('error/404', [ 'title' => '404 Not Found']);
        }
        /**
         * If we get to this point, then all is well
         * and it is ok to process the query and print
         * the results in a html format.
         */ else {
            $app->view->display('index/post', [ 'post' => $q, 'title' => $title]);
        }
    });
});
