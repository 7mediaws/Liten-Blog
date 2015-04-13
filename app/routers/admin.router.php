<?php
if (!defined('BASE_PATH'))
    exit('No direct script access allowed');

function hasPermission($permKey)
{
    $app = \Liten\Liten::getInstance();
    $id = $app->db->role()
        ->select('role.ID')
        ->_join('user_roles', 'role.ID = b.roleID', 'b')
        ->where('b.userID = ?', get_userdata('userID'))->_and_()
        ->whereLike('role.permission', "%$permKey%");
    $q = $id->find(function($data) {
        $array = [];
        foreach ($data as $d) {
            $array[] = $d;
        }
        return $array;
    });
    if (count($q) > 0) {
        return true;
    }
    return false;
}

function get_userdata($field)
{
    $app = \Liten\Liten::getInstance();
    $userID = $app->cookies->getSecureCookie('auth_token');
    $value = $app->db->users()
        ->select('users.*')
        ->where('users.userID = ?', $userID);
    $q = $value->find(function($data) {
        $array = [];
        foreach ($data as $d) {
            $array[] = $d;
        }
        return $array;
    });
    foreach ($q as $r) {
        return _h($r[$field]);
    }
}

function isUserLoggedIn()
{
    $app = \Liten\Liten::getInstance();

    $user = $app->db->users()->select('userID')
        ->where('users.userID = ?', get_userdata('userID'));
    $q = $user->find(function($data) {
        $array = [];
        foreach ($data as $d) {
            $array[] = $d;
        }
        return $array;
    });
    if ($app->cookies->verifySecureCookie('auth_token') && count($q) > 0) {
        return true;
    }
    return false;
}

function unique_slug($name, $table, $field)
{
    $app = \Liten\Liten::getInstance();
    $app->inst->singleton('db', function () {
        $pdo = new \PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        return new \Liten\Orm($pdo);
    });
    $orm = $app->inst->db;
    $slug = slugify($name);

    $titles = [];

    $table = $orm->$table();
    $q = $table->select("$field")
        ->where("$field RLIKE ?", "$slug(-[0-9]+)?$");
    $results = $q->find(function($data) {
        $array = [];
        foreach ($data as $d) {
            $array[] = $d;
        }
        return $array;
    });

    if (count($results) > 0) {
        foreach ($results as $item) {
            $titles[] = $item["$field"];
        }
    }

    $total = count($titles);
    $last = end($titles);

    /**
     * No equal results, return $slug
     */
    if ($total == 0)
        return $slug;

    /**
     * If we have only one result, we look if it has a number at the end
     */
    elseif ($total == 1) {
        /**
         * Take the only value of the array, because there is only 1
         */
        $exists = $titles[0];

        /**
         * Kill the slug and see what happens
         */
        $exists = str_replace($slug, "", $exists);

        /**
         * If there is no light about, there was no number at the end.
         * We added it now
         */
        if ("" == trim($exists))
            return $slug . "-1";

        /**
         * If not..........
         */
        else {
            /**
             * Obtain the number because of REGEX it will be there... ;-)
             */
            $number = str_replace("-", "", $exists);

            /**
             * Number plus one.
             */
            $number++;

            return $slug . "-" . $number;
        }
    }

    /**
     * If there is more than one result, we need the last one
     */ else {
        /**
         * Last value
         */
        $exists = $last;

        /**
         * Delete the actual slug and see what happens
         */
        $exists = str_replace($slug, "", $exists);

        /**
         * Obtain the number, easy.
         */
        $number = str_replace("-", "", $exists);

        /**
         * Increment number +1
         */
        $number++;

        return $slug . "-" . $number;
    }
}
$app->before('GET|POST', '/login.*', function() use($app) {
    if (isUserLoggedIn()) {
        redirect(url('/admin/'));
    }
});

$app->match('GET|POST', '/login', function () use($app, $orm) {
    $hasher = new \app\src\PasswordHash(8, FALSE);
    
    /**
     * Shows the login form.
     */
    if ($app->req->isGet()) {
        $app->view->display('login/index');
    }
    
    /**
     * Submits post data from the login form.
     */
    if ($app->req->isPost()) {
        $user = $app->db->users()->select('userID,uname,password')
            ->where('users.uname = ?', $app->req->_post('uname'));
        $q = $user->find(function($data) {
            $array = [];
            foreach ($data as $d) {
                $array[] = $d;
            }
            return $array;
        });
        $a = [];
        foreach ($q as $r) {
            $a[] = $r;
        }
        
        /**
         * Checks if the submitted username exists in
         * the database.
         */
        if ($app->req->_post('uname') !== $r['uname']) {
            $app->flash('login_error', '<p class="message invalid">The username does not exist. Please try again. <span class="close">X</span></p>');
            redirect(url('/login/'));
            return;
        }
        
        /**
         * Checks if the password is correct.
         */
        if ($hasher->checkPassword($app->req->_post('password'), $r['password'])) {
            $app->cookies->setSecureCookie('auth_token', $r['userID'], 86400);
            redirect(url('/admin/'));
        } else {
            $app->flash('login_error', '<p class="message invalid">The password you entered was incorrect. <span class="close">X</span></p>');
            redirect(url('/login/'));
        }
    }
});

/**
 * Before admin router to check if the user is logged in.
 */
$app->before('GET|POST', '/admin.*', function() use($app) {
    if (!isUserLoggedIn()) {
        $app->flash('login_error', '<p class="message invalid">Login required <span class="close">X</span></p>');
        redirect(url('/login/'));
        exit();
    }
});

$app->group('/admin', function() use ($app, $orm) {

    $app->hook->do_action('init');

    /**
     * Includes and loads all activated plugins.
     */
    $app->hook->load_activated_plugins();

    /**
     * An action called to add the plugin's link
     * to the menu structure.
     *
     * @uses do_action() Calls 'admin_menu' hook.
     */
    $app->hook->do_action('admin_menu');

    /**
     * An action called to add plugin page links
     * to menu structure.
     *
     * @uses do_action() Calls 'plugin_admin_page' hook.
     */
    $app->hook->do_action('plugin_admin_page');

    $app->get('/', function () use($app) {

        $app->view->display('admin/index');
    });

    /**
     * Show a list of all of our pages in the backend.
     */
    $app->get('/pages/', function () use($app, $orm) {

        $pages = $orm->pages();
        $select = $pages->select('pages.pageID,pages.page_title,pages.page_content')
            ->select('pages.page_status,pages.page_date,pages.page_slug,pages.last_modified')
            ->orderBy('pages.page_date', 'DESC');
        $q = $select->find(function($data) {
            $array = [];
            foreach ($data as $d) {
                $array[] = $d;
            }
            return $array;
        });

        $app->view->display('admin/pages', [ 'data' => $q]);
    });
    
    /**
     * Before route checks to make sure the logged in
     * user has the permission to edit a page.
     */
    $app->before('GET|POST', '/page/.*', function() {
        if (!hasPermission('edit_page')) {
            redirect(url('/admin/'));
        }
    });

    /**
     * Shows the edit form with the requested id.
     */
    $app->match('GET|POST', '/page/(\d+)/', function ($id) use($app, $orm) {

        if ($app->req->isPost()) {
            $page = $orm->pages();
            $page->page_title = $app->req->_post('page_title');
            $page->page_content = $app->req->_post('page_content');
            $page->page_date = $app->req->_post('page_date');
            $page->page_slug = slugify($app->req->_post('page_title'));
            $page->page_status = $app->req->_post('page_status');
            $page->page_sort = (int)$app->req->_post('page_sort');
            $page->where('pageID = ?', (int)$app->req->_post('pageID'));
            if ($page->update()) {
                $app->flash('page_status', '<p class="message valid">The page was updated successfully.</p>');
            } else {
                $app->flash('page_status', '<p class="message invalid">The system was unable to update this page.</p>');
            }
            redirect($_SERVER['HTTP_REFERER']);
        }

        if ($app->req->isGet()) {

            $pages = $orm->pages();
            $select = $pages->select('pages.pageID,pages.page_title,pages.page_content')
            ->select('pages.page_status,pages.page_date,pages.page_slug,pages.page_sort,pages.last_modified')
                ->where('pages.pageID = ?', $id);
            $q = $select->find(function($data) {
                $array = [];
                foreach ($data as $d) {
                    $array[] = $d;
                }
                return $array;
            });
            foreach ($q as $r) {
                $title = $r['page_title'];
            }
        }

        /**
         * If the page doesn't exist, then it
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
            $app->view->display('admin/edit-page', [ 'single' => $q, 'title' => $title]);
        }
    });
    
    /**
     * Before route checks to make sure the logged in user
     * has permission to create a new page.
     */
    $app->before('GET|POST', '/page/new/', function() {
        if (!hasPermission('add_page')) {
            redirect(url('/admin/'));
        }
    });
    /**
     * Shows the add new page form.
     */
    $app->match('GET|POST', '/page/new/', function () use($app, $orm) {

        if ($app->req->isPost()) {
            $page = $orm->pages();
            $page->page_title = $app->req->_post('page_title');
            $page->page_content = $app->req->_post('page_content');
            $page->page_slug = unique_slug($app->req->_post('page_title'), 'pages', 'page_slug');
            $page->page_status = $app->req->_post('page_status');
            $page->page_sort = (int)$app->req->_post('page_sort');
            $page->page_date = $app->req->_post('page_date');
            $page->userID = (int)get_userdata('userID');

            if ($page->save()) {
                $app->flash('page_status', '<p class="message valid">The page was added successfully.</p>');
                redirect(url('/admin/page/' . $orm->lastInsertId() . '/'));
            } else {
                $app->flash('page_status', '<p class="message invalid">The system was unable to create your new page.</p>');
                redirect(url('/admin/page/new/'));
            }
        }
        if ($app->req->isGet()) {
            $app->view->display('admin/new-page');
        }
    });
    
    /**
     * Before route checks to make sure the logged in user
     * is allowed to delete pages.
     */
    $app->before('GET', '/page/d/.*', function() {
        if (!hasPermission('delete_page')) {
            redirect(url('/admin/'));
            exit();
        }
    });

    $app->get('/page/d/(\d+)/', function($id) use($app, $orm) {
        $page = $orm->pages()->where('pageID', $id);
        if ($page->delete()) {
            $app->flash('page_status', '<p class="message valid">The page was deleted successfully.</p>');
        } else {
            $app->flash('page_status', '<p class="message invalid">The system was unable to delete the requested page.</p>');
        }
        redirect(url('/admin/pages/'));
    });
    
     /**
     * Show a list of all of our posts in the backend.
     */
    $app->get('/posts/', function () use($app, $orm) {

        $posts = $orm->posts();
        $select = $posts->select('posts.postID,posts.post_title,posts.post_content')
            ->select('posts.post_status,posts.post_date,posts.post_slug,posts.last_modified')
            ->select('cat.catSlug,cat.catName')
            ->_join('categories', 'posts.catID = cat.catID', 'cat')
            ->orderBy('posts.post_date', 'DESC');
        $q = $select->find(function($data) {
            $array = [];
            foreach ($data as $d) {
                $array[] = $d;
            }
            return $array;
        });

        $app->view->display('admin/posts', [ 'data' => $q]);
    });

    /**
     * Before route checks to make sure the logged in
     * user has the permission to edit a post.
     */
    $app->before('GET|POST', '/post/.*', function() {
        if (!hasPermission('edit_post')) {
            redirect(url('/admin/'));
        }
    });

    /**
     * Shows the edit form with the requested id.
     */
    $app->match('GET|POST', '/post/(\d+)/', function ($id) use($app, $orm) {

        if ($app->req->isPost()) {
            $post = $orm->posts();
            $post->post_title = $app->req->_post('post_title');
            $post->post_content = $app->req->_post('post_content');
            $post->post_date = $app->req->_post('post_date');
            $post->post_slug = slugify($app->req->_post('post_title'));
            $post->post_status = $app->req->_post('post_status');
            $post->catID = (int)$app->req->_post('catID');
            $post->where('postID = ?', (int)$app->req->_post('postID'));
            if ($post->update()) {
                $app->flash('post_status', '<p class="message valid">The post was updated successfully.</p>');
            } else {
                $app->flash('post_status', '<p class="message invalid">The system was unable to update this post.</p>');
            }
            redirect($_SERVER['HTTP_REFERER']);
        }

        if ($app->req->isGet()) {
            $cat = $orm->categories();
            $sql = $cat->find(function($data) {
                $array = [];
                foreach ($data as $d) {
                    $array[] = $d;
                }
                return $array;
            });

            $posts = $orm->posts();
            $select = $posts->select('posts.postID,posts.post_title,posts.post_content')
                ->select('posts.post_status,posts.post_date,posts.post_slug,posts.last_modified')
                ->select('cat.catID,cat.catSlug,cat.catName')
                ->_join('categories', 'posts.catID = cat.catID', 'cat')
                ->where('posts.postID = ?', $id);
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
            $app->view->display('admin/edit-post', [ 'single' => $q, 'title' => $title, 'categories' => $sql]);
        }
    });

    /**
     * Before route checks to make sure the logged in user
     * has permission to create a new post.
     */
    $app->before('GET|POST', '/post/new/', function() {
        if (!hasPermission('add_post')) {
            redirect(url('/admin/'));
        }
    });
    /**
     * Shows the add new post form.
     */
    $app->match('GET|POST', '/post/new/', function () use($app, $orm) {

        $cat = $orm->categories();
        $q = $cat->find(function($data) {
            $array = [];
            foreach ($data as $d) {
                $array[] = $d;
            }
            return $array;
        });

        if ($app->req->isPost()) {
            $post = $orm->posts();
            $post->post_title = $app->req->_post('post_title');
            $post->post_content = $app->req->_post('post_content');
            $post->post_slug = unique_slug($app->req->_post('post_title'), 'posts', 'post_slug');
            $post->post_status = $app->req->_post('post_status');
            $post->post_date = $app->req->_post('post_date');
            $post->catID = (int)$app->req->_post('catID');
            $post->userID = (int)get_userdata('userID');

            if ($post->save()) {
                $app->flash('post_status', '<p class="message valid">The post was added successfully.</p>');
                redirect(url('/admin/post/' . $orm->lastInsertId() . '/'));
            } else {
                $app->flash('post_status', '<p class="message invalid">The system was unable to create your new post.</p>');
                redirect(url('/admin/post/new/'));
            }
        }
        if ($app->req->isGet()) {
            $app->view->display('admin/new-post', [ 'categories' => $q]);
        }
    });

    $app->match('GET|POST', '/categories/', function () use($app, $orm) {

        $cat = $orm->categories();
        $q = $cat->find(function($data) {
            $array = [];
            foreach ($data as $d) {
                $array[] = $d;
            }
            return $array;
        });

        if ($app->req->isPost()) {
            $cat = $orm->categories();
            $cat->catName = $app->req->_post('catName');
            $cat->catSlug = unique_slug($app->req->_post('catName'), 'categories', 'catSlug');

            if ($cat->save()) {
                $app->flash('cat_status', '<p class="message valid">The category was saved successfully.</p>');
                redirect(url('/admin/categories/' . $orm->lastInsertId() . '/'));
            } else {
                $app->flash('cat_status', '<p class="message invalid">The system was unable to create your new category.</p>');
                redirect(url('/admin/categories/'));
            }
        }

        if ($app->req->isGet()) {
            $app->view->display('admin/categories', [ 'categories' => $q]);
        }
    });

    /**
     * Before route checks to make sure the logged in
     * user has the permission to edit a category.
     */
    $app->before('GET|POST', '/categories/.*', function() {
        if (!hasPermission('edit_category')) {
            redirect(url('/admin/'));
        }
    });

    $app->match('GET|POST', '/categories/(\d+)/', function ($id) use($app, $orm) {
        $cat = $orm->categories()->where('catID = ?', $id);
        $q = $cat->find(function($data) {
            $array = [];
            foreach ($data as $d) {
                $array[] = $d;
            }
            return $array;
        });
        foreach ($q as $r) {
            $title = $r['catName'];
        }

        if ($app->req->isPost()) {
            $cat = $orm->categories();
            $cat->catName = $app->req->_post('catName');
            $cat->catSlug = unique_slug($app->req->_post('catName'), 'categories', 'catSlug');
            $cat->where('catID = ?', (int)$app->req->_post('catID'));

            if ($cat->save()) {
                $app->flash('cat_status', '<p class="message valid">The category was updated successfully.</p>');
            } else {
                $app->flash('cat_status', '<p class="message invalid">The system was unable to update the category.</p>');
            }
            redirect($_SERVER['HTTP_REFERER']);
        }
        if ($app->req->isGet()) {
            $app->view->display('admin/viewcat', [ 'category' => $q, 'title' => $title]);
        }
    });

    /**
     * Before route checks to make sure the logged in user
     * is allowed to delete posts.
     */
    $app->before('GET', '/post/d/.*', function() {
        if (!hasPermission('delete_post')) {
            redirect(url('/admin/'));
            exit();
        }
    });

    $app->get('/post/d/(\d+)/', function($id) use($app, $orm) {
        $post = $orm->posts()->where('postID', (int)$id);
        if ($post->delete()) {
            $app->flash('post_status', '<p class="message valid">The post was deleted successfully.</p>');
        } else {
            $app->flash('post_status', '<p class="message invalid">The system was unable to delete the requested post.</p>');
        }
        redirect(url('/admin/posts/'));
    });

    /**
     * Before route checks to make sure the logged in user
     * us allowed to delete categories.
     */
    $app->before('GET', '/categories/d/.*', function() use($app) {
        if (!hasPermission('delete_category')) {
            $app->flash('cat_status', '<p class="message invalid">You are not allowed to delete categories.</p>');
            redirect(url('/admin/categories/'));
            exit();
        }
    });

    $app->get('/categories/d/(\d+)/', function($id) use($app, $orm) {
        $cat = $orm->categories()->where('catID', (int)$id);
        if ($cat->delete()) {
            $app->flash('cat_status', '<p class="message valid">The category was deleted successfully.</p>');
        } else {
            $app->flash('cat_status', '<p class="message invalid">The system was unable to delete the requested category.</p>');
        }
        redirect(url('/admin/categories/'));
    });

    /**
     * Before route checks to make sure the logged in user
     * us allowed to manage options/settings.
     */
    $app->before('GET', '/settings.*', function() {
        if (!hasPermission('manage_options')) {
            redirect(url('/admin/'));
        }
    });

    $app->match('GET|POST', '/settings/', function() use($app, $orm) {
        if ($app->req->isPost()) {
            $options = [ 'blog_title', 'blog_description', 'blog_authorbio', 'api_key', 'num_posts'];
            foreach ($options as $option_name) {
                if (!isset($_POST[$option_name]))
                    continue;
                $value = $_POST[$option_name];
                $app->hook->update_option($option_name, $value);
            }
            // Update more options here
            $app->hook->do_action('update_options');
        }

        $app->view->display('admin/settings', [ 'title' => 'General Settings']);
    });

    $app->get('/plugins/', function() use($app) {
        $app->view->display('admin/plugins', [ 'title' => 'Plugins']);
    });

    $app->get('/plugins/a/(.*)/', function($plugin) use($app) {
        $app->hook->activate_plugin($plugin);
        redirect($_SERVER['HTTP_REFERER']);
    });

    $app->get('/plugins/d/(.*)/', function($plugin) use($app) {
        $app->hook->deactivate_plugin($plugin);
        redirect($_SERVER['HTTP_REFERER']);
    });

    /**
     * Before route checks to make sure the logged in user
     * us allowed to manage plugin options/settings.
     */
    $app->before('GET', '/options.*', function() {
        if (!hasPermission('manage_options')) {
            redirect(url('/admin/'));
        }
    });

    $app->match('GET|POST', '/options/', function() use($app) {
        $app->view->display('admin/options', [ 'title' => 'Plugin Options']);
    });

    $app->match('GET|POST', '/profile/', function() use($app, $orm) {
        $hasher = new \app\src\PasswordHash(8, FALSE);

        if ($app->req->isGet()) {
            $app->view->display('admin/profile', [ 'title' => 'User Profile']);
        }

        if ($app->req->isPost()) {
            $user = $orm->users()->select('password')
                ->where('users.userID = ?', get_userdata('userID'));
            $q = $user->find(function($data) {
                $array = [];
                foreach ($data as $d) {
                    $array[] = $d;
                }
                return $array;
            });
            $a = [];
            foreach ($q as $r) {
                $a[] = $r;
            }
            $user = $orm->users();
            $user->fname = $app->req->_post('fname');
            $user->lname = $app->req->_post('lname');
            $user->email = $app->req->_post('email');
            if ($app->req->_post('password') !== null) {
                if ($hasher->checkPassword($app->req->_post('curr_pass'), $r['password'])) {
                    $user->password = $hasher->hashPassword($app->req->_post('password'));
                }
            }
            $user->where('users.userID = ?', (int)get_userdata('userID'));
            if ($user->save()) {
                $app->flash('profile', '<p class="message valid">Your profile was updated.</p>');
            } else {
                $app->flash('profile', '<p class="message invalid">Your profile could not be updated.</p>');
            }
            redirect(url('/admin/profile/'));
        }
    });

    $app->get('/logout/', function() use($app, $orm) {
        $vars = [];
        parse_str($app->cookies->get('auth_token'), $vars);
        /**
         * Checks to see if the cookie is exists on the server.
         * It it exists, we need to delete it.
         */
        $file = $app->config('cookies.savepath') . 'cookies.' . $vars['data'];
        if (file_exists($file)) {
            unlink($file);
        }
        /**
         * After the cookie is removed from the server,
         * we know need to remove it from the browser and
         * redirect the user to the login page.
         */
        $app->cookies->remove('auth_token');
        redirect(url('/login/'));
    });
    
    /**
     * If the requested page does not exist,
     * return a 404.
     */
    $app->setError(function() use($app) {

        echo $app->res->_format('json', $app->res->HTTP[404]);
    });
});
