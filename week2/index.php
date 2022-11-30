<?php
/**
 * Controller
 *
 * Database-driven Webtechnology
 * Taught by Stijn Eikelboom
 * Based on code by Reinard van Dalen
 */

include 'model.php';

/* Connect to DB */
$db = connect_db('localhost', 'ddwt22_week2', 'ddwt22','ddwt22');

/* REDUNDANT CODE */

/* Get Number of Series */
$nbr_series = count_series($db);
$nbr_users = count_users($db);
$right_column = use_template('cards');
$template = Array(
    1 => Array(
        'name' => 'Home',
        'url' => '/DDWT22/week2/'
    ),
    2 => Array(
        'name' => 'Overview',
        'url' => '/DDWT22/week2/overview/'
    ),
    3 => Array(
        'name' => 'Add series',
        'url' => '/DDWT22/week2/add/'
    ),
    4 => Array(
        'name' => 'My account',
        'url' => '/DDWT22/week2/myaccount/'
    ),
    5 => Array(
        'name' => 'Registration',
        'url' => '/DDWT22/week2/register/'
    ));

$active_id = Array(0,1,2,3,4,5);


/* Landing page */
if (new_route('/DDWT22/week2/', 'get')) {

    /* Page info */
    $page_title = 'Home';
    $breadcrumbs = get_breadcrumbs([
        'DDWT22' => na('/DDWT22/', False),
        'Week 2' => na('/DDWT22/week2/', False),
        'Home' => na('/DDWT22/week2/', True)
    ]);
    $navigation = get_navigation($template, $active_id[1]);

    /* Page content */
    $page_subtitle = 'The online platform to list your favorite series';
    $page_content = 'On Series Overview you can list your favorite series. You can see the favorite series of all Series Overview users. By sharing your favorite series, you can get inspired by others and explore new series.';

    /* Choose Template */
    include use_template('main');
}

/* Overview page */
elseif (new_route('/DDWT22/week2/overview/', 'get')) {

    /* Page info */
    $page_title = 'Overview';
    $breadcrumbs = get_breadcrumbs([
        'DDWT22' => na('/DDWT22/', False),
        'Week 2' => na('/DDWT22/week2/', False),
        'Overview' => na('/DDWT22/week2/overview', True)
    ]);
    $navigation = get_navigation($template, $active_id[2]);

    /* Page content */
    //$right_column = use_template('cards');
    $page_subtitle = 'The overview of all series';
    $page_content = 'Here you find all series listed on Series Overview.';
    $left_content = get_series_table($db);

    /* Choose Template */
    include use_template('main');
}

/* Single Series */
elseif (new_route('/DDWT22/week2/series/', 'get')) {

    /* Get series from db */
    $series_id = $_GET['series_id'];
    $series_info = get_series_info($db, $series_id);

    /* Page info */
    $page_title = $series_info['name'];
    $breadcrumbs = get_breadcrumbs([
        'DDWT22' => na('/DDWT22/', False),
        'Week 2' => na('/DDWT22/week2/', False),
        'Overview' => na('/DDWT22/week2/overview/', False),
        $series_info['name'] => na('/DDWT22/week2/series/?series_id='.$series_id, True)
    ]);
    $navigation = get_navigation($template, $active_id[2]);

    /* Page content */
    $page_subtitle = sprintf("Information about %s", $series_info['name']);
    $page_content = $series_info['abstract'];
    $nbr_seasons = $series_info['seasons'];
    $creators = $series_info['creator'];
    $added_by = get_user_name($db, $series_id);

    /* Getting info from edit post request */
    if (isset($_GET['error_msg'])){
        $changed_series_id = $_GET['series_id'];
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Choose Template */
    include use_template('series');
}

/* Add series GET */
elseif (new_route('/DDWT22/week2/add/', 'get')) {

    /* Page info */
    $page_title = 'Add Series';
    $breadcrumbs = get_breadcrumbs([
        'DDWT22' => na('/DDWT22/', False),
        'Week 2' => na('/DDWT22/week2/', False),
        'Add Series' => na('/DDWT22/week2/new/', True)
    ]);
    $navigation = get_navigation($template, $active_id[3]);

    /* Page content */
    //$right_column = use_template('cards');
    $page_subtitle = 'Add your favorite series';
    $page_content = 'Fill in the details of you favorite series.';
    $submit_btn = "Add Series";
    $form_action = '/DDWT22/week2/add/';

    if (isset ($_GET['error_msg'])){
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Choose Template */
    include use_template('new');
}

/* Add series POST */
elseif (new_route('/DDWT22/week2/add/', 'post')) {

    /* Page info */
    $page_title = 'Add Series';
    $breadcrumbs = get_breadcrumbs([
        'DDWT22' => na('/DDWT22/', False),
        'Week 2' => na('/DDWT22/week2/', False),
        'Add Series' => na('/DDWT22/week2/add/', True)
    ]);
    $navigation = get_navigation($template, $active_id[3]);

    /* Page content */
    //$right_column = use_template('cards'); 5.2
    $page_subtitle = 'Add your favorite series';
    $page_content = 'Fill in the details of you favorite series.';
    $submit_btn = "Add Series";
    $form_action = '/DDWT22/week2/add/';

    /* Add series to database */
    $feedback = add_series($db, $_POST);
    $error_msg = urlencode(json_encode($feedback));
    if($feedback['type'] === 'error'){
        /* redirect to get request */
        redirect(sprintf('/DDWT22/week2/add/', $error_msg));
    }

    include use_template('new');
}

/* Edit series GET */
elseif (new_route('/DDWT22/week2/edit/', 'get')) {

    /* Get series info from db */
    $series_id = $_GET['series_id'];
    $series_info = get_series_info($db, $series_id);

    /* Page info */
    $page_title = 'Edit Series';
    $breadcrumbs = get_breadcrumbs([
        'DDWT22' => na('/DDWT22/', False),
        'Week 2' => na('/DDWT22/week2/', False),
        sprintf("Edit Series %s", $series_info['name']) => na('/DDWT22/week2/new/', True)
    ]);
    $navigation = get_navigation($template, $active_id[0]);

    /* Page content */
    //$right_column = use_template('cards');
    $page_subtitle = sprintf("Edit %s", $series_info['name']);
    $page_content = 'Edit the series below.';
    $submit_btn = "Edit Series";
    $form_action = '/DDWT22/week2/edit/';

    /* Choose Template */
    include use_template('new');
}

/* Edit series POST */
elseif (new_route('/DDWT22/week2/edit/', 'post')) {

    /* Get series info from db */
    $series_id = $_POST['series_id'];
    $series_info = get_series_info($db, $series_id);

    /* Update series in database */
    $feedback = update_series($db, $_POST);
    $error_msg = json_encode($feedback);

    if($feedback['type'] === 'error'){
        /* redirect to get request */
        redirect(sprintf('/DDWT22/week2/series/', $error_msg, $series_id));
    }

    /* Page info */
    $page_title = $series_info['name'];
    $breadcrumbs = get_breadcrumbs([
        'DDWT22' => na('/DDWT22/', False),
        'Week 2' => na('/DDWT22/week2/', False),
        'Overview' => na('/DDWT22/week2/overview/', False),
        $series_info['name'] => na('/DDWT22/week2/series/?series_id='.$series_id, True)
    ]);
    $navigation = get_navigation($template, $active_id[0]);

    /* Page content */
    $page_subtitle = sprintf("Information about %s", $series_info['name']);
    $page_content = $series_info['abstract'];
    $nbr_seasons = $series_info['seasons'];
    $creators = $series_info['creator'];

    /* Choose Template */
    include use_template('series');
}

/* Remove series */
elseif (new_route('/DDWT22/week2/remove/', 'post')) {

    /* Remove series in database */
    $series_id = $_POST['series_id'];
    $feedback = remove_series($db, $series_id);
    $error_msg = get_error($feedback);

    /* Page info */
    $page_title = 'Overview';
    $breadcrumbs = get_breadcrumbs([
        'DDWT22' => na('/DDWT22/', False),
        'Week 2' => na('/DDWT22/week2/', False),
        'Overview' => na('/DDWT22/week2/overview', True)
    ]);
    $navigation = get_navigation($template, $active_id[2]);

    /* Page content */
    $page_subtitle = 'The overview of all series';
    $page_content = 'Here you find all series listed on Series Overview.';
    $left_content = get_series_table(get_series($db));

    /* Choose Template */
    include use_template('main');
}
/* My account page */
elseif (new_route('/DDWT22/week2/myaccount/', 'GET')){
    $page_title = 'My account';
    $breadcrumbs = get_breadcrumbs([
        'DDWT22' => na('/DDWT22/', False),
        'Week 2' => na('/DDWT22/week2/', False),
        'Overview' => na('/DDWT22/week2/overview', False)
    ]);
    $navigation = get_navigation($template, $active_id[4]);
    /* Page content */
    session_start();
    if (isset($_SESSION['name'])){
        $page_subtitle = 'This is your account page';
        $page_content = 'Something something';
        $user = get_user_name($db, $_SESSION['name']);
        //$left_content = get_series_table(get_series($db));
    }
    else{
        $page_subtitle = 'Bro you need to log in';
        $page_content = 'Yeah';
    }

    /* Choose Template */
    include use_template('account');
}

/* Register page */

elseif (new_route('/DDWT22/week2/register/', 'GET')){
    $page_title = 'Register a new user';
    $breadcrumbs = get_breadcrumbs([
        'DDWT22' => na('/DDWT22/', False),
        'Week 2' => na('/DDWT22/week2/', False),
        'Overview' => na('/DDWT22/week2/overview', False)
    ]);
    $navigation = get_navigation($template, $active_id[5]);
    /* Page content */
    $page_subtitle = 'Registration';
    $page_content = 'Hell yeah';
    if (isset($_GET['error_msg'])){
        $error_msg = get_error($_GET['error_msg']);
    }

    /* Choose Template */
    include use_template('register');
}

elseif (new_route('/DDWT22/week2/register/', 'POST')){
    $page_title = 'Register a new user';
    $breadcrumbs = get_breadcrumbs([
        'DDWT22' => na('/DDWT22/', False),
        'Week 2' => na('/DDWT22/week2/', False),
        'Overview' => na('/DDWT22/week2/overview', False)
    ]);
    $navigation = get_navigation($template, $active_id[5]);

    /* Page content */
    $page_subtitle = 'Registration';
    $page_content = 'Hell yeah';
    $form_data = $_POST;
    $feedback = register_user($db, $_POST);
    $error_msg = json_encode($feedback);
    if ($feedback['type'] === 'success'){
        session_start();
        redirect(sprintf('/DDWT22/week2/myaccount/', $error_msg, $_SESSION['username']));
    }
    else{
        redirect(sprintf('/DDWT22/week2/register/', $error_msg));
    }


    /* Choose Template */
    include use_template('register');
}

/* Log In page */
elseif( new_route('/DDWT22/week2/login', 'GET')){
    $page_title = 'Log in';
    $breadcrumbs = get_breadcrumbs([
        'DDWT22' => na('/DDWT22/', False),
        'Week 2' => na('/DDWT22/week2/', False),
        'Overview' => na('/DDWT22/week2/overview', False)
    ]);
    $navigation = get_navigation($template, $active_id[0]);
    /* Page content */
    $page_subtitle = 'Please log in';
    $page_content = 'Hell yeah';


    /* Choose Template */
    include use_template('login');
}

elseif( new_route('/DDWT22/week2/login', 'POST')){
    $page_title = 'Log in';
    $breadcrumbs = get_breadcrumbs([
        'DDWT22' => na('/DDWT22/', False),
        'Week 2' => na('/DDWT22/week2/', False),
        'Overview' => na('/DDWT22/week2/overview', False)
    ]);
    $navigation = get_navigation($template, $active_id[0]);
    /* Page content */
    $page_subtitle = 'Please log in';
    $page_content = 'Hell yeah';


    /* Choose Template */
    include use_template('login');
}

/* Log Out page */
elseif( new_route('/DDWT22/week2/logout/', 'GET')){
    echo "You logged out, congratulations";
}

else {
    http_response_code(404);
    echo '404 Not Found';
}
