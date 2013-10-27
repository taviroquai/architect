<?php

// add main route
r('/', function() {
	// add content
    c(new \Arch\View(BASE_PATH.'/theme/demo/default.php'));
});

// add 404 route
r('/404', function()  {
   	// set content
    c('<h1>404</h1>');
});

r('/demo', function() {
    // demo of file upload
    if ($file = f(0)) {
        app()->upload($file, BASE_PATH.'/theme/data');
    }    
    // demo of download file
    if (g('dl')) {
        app()->download(BASE_PATH.'/theme/default/img/'.g('dl'));
    }
    if (g('img')) {
        app()->download(g('img'), false);
    }
    // show demo view
    c(new \Arch\Demo\ViewMain());
});

r('/demo/install', function() {
    // initialization
    \Arch\Demo\ModelUser::checkDatabase();
});

// add routes
r('/register', function() {
    // trigger before view
    tr('register.form.before.view');
    // add view to content
    $view = new \Arch\Demo\ViewRegister();
    $view->set('registerUrl', u('/register'));
    c($view);
});

// add routes
r('/register-success', function() {
    c('<p>Thank you for registering</p>');
});

// add login route
r('/login', function() {
    // trigger before view
    tr('login.form.before.view');
    // add view to content
    $view = new \Arch\Demo\ViewLogin();
    $view->set('loginUrl', app()->url('/login'));
    $view->set('logoutUrl', app()->url('/logout'));
    c($view);
});

// add logout route
r('/logout', function() {
    // destroy current session and redirect
    app()->session->destroy();
    app()->redirect();
});

// add event save post
e('register.form.before.view', function() {

    if (p('register') && app()->getCaptcha()) {

        // load model
        $model = new \Arch\Demo\ModelUser();
        $user = $model->register(p());
        
        if ($user) {
            // trigger after post
            tr('register.form.after.post', $user);
            // redirect to success page
            app()->redirect(u('/register-success'));
        } else {
            sleep(2);
        }
    }
});

// add event try to login
e('login.form.before.view', function() {

    if (p('login')) {
        
        // login user
        $model = new \Arch\Demo\ModelUser();
        $user = $model->login(p('email'), p('password'));

        if ($user) {
            // start user session
            app()->session->login = $user->email;
            // trigger after login
            tr('login.form.after.post', $user);
            app()->redirect();
        }
    }
});

r('/demo/crud/user/(:num)/edit', function($id) {
    $stm = q('demo_user')->s()->w('id = ? ', array($id))->run();
    $data = array('user' => $stm->fetchObject());
    $v = new \Arch\View(BASE_PATH.'/theme/demo/userform.php', $data);
    o($v);
});

r('/demo/crud/user/list', function() {
    $data = array('stm' => q('demo_user')->s()->run());
    $v = new \Arch\View(BASE_PATH.'/theme/demo/userlist.php', $data);
    o($v);
});

r('/demo/crud/group/list', function() {
    $data = array('stm' => q('demo_group')->s()->run());
    $v = new \Arch\View(BASE_PATH.'/theme/demo/grouplist.php', $data);
    o($v);
});

r('/demo/crud/user/(:num)/group/list', function($id) {
    $stm = q('demo_group')
        ->s('demo_group.*')
        ->j('demo_usergroup', 'demo_usergroup.id_group = demo_group.id')
        ->w('demo_usergroup.id_user = ?', array($id))
        ->run();
    $data = array('stm' => $stm);
    $v = new \Arch\View(BASE_PATH.'/theme/demo/grouplist.php', $data);
    o($v);
});

r('/demo/crud/user/save', function() {
    $result = array('result' => true, 'id' => null);
    $data = array('email' => p('email'));
    if (p('password') != '') $data['password'] = s(p('password'));
    if (p('id') > 0) {
        q('demo_user')->u($data)->w('id = ? ', array(p('id')))->run();
        $result['id'] = p('id');
    }
    else {
        q('demo_user')->i($data)->run();
        $result['id'] = q('demo_user')->id();
    }
    j($result);
});

r('/demo/crud/user/(:num)/delete', function($id) {
    $result = array('result' => true, 'id' => $id);
    q('demo_usergroup')->d('id_user = ?', array($id))->run();
    q('demo_user')->d('id = ? ', array($id))->run();
    j($result);
});

r('/demo/crud/user/add_group', function() {
    $data = array('id_user' => p('id_user'), 'id_group' => p('id_group'));
    q('demo_usergroup')->i($data)->run();
    j(array('result' => true, 'data' => p()));
});

r('/demo/crud/user/del_group', function() {
    $data = array('id_user' => p('id_user'), 'id_group' => p('id_group'));
    q('demo_usergroup')->d('id_user = ? and id_group = ?', $data)->run();
    j(array('result' => true, 'data' => p()));
});
