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
$db = connect_db('localhost', 'ddwt22_week1', 'ddwt22','ddwt22');
/* Counting series */
$series_num = count_series($db);
/* Getting data from DB */
$series_arr = get_series($db);
/* Adding data to HTML table */
$table_exp = get_series_table($series_arr);
/* Landing page */
if (new_route('/', 'get')) {
    /* Page info */
    $page_title = 'Home';
    /* Page content */
    $page_content = 'Hello World.';
/* Choose Template */
include use_template('main');
}

/* Landing page */
if (new_route('/DDWT22/week1/', 'get')) {
    /* Page info */
    $page_title = 'Home';
    $breadcrumbs = get_breadcrumbs([
        'DDWT22' => na('/DDWT22/', False),
        'Week 1' => na('/DDWT22/week1/', False),
        'Home' => na('/DDWT22/week1/', True)
    ]);
    $navigation = get_navigation([
        'Home' => na('/DDWT22/week1/', True),
        'Overview' => na('/DDWT22/week1/overview/', False),
        'Add Series' => na('/DDWT22/week1/add/', False)
    ]);

    /* Page content */
    $right_column = use_template('cards');
    $page_subtitle = 'The online platform to list your favorite series';
    $page_content = 'On Series Overview you can list your favorite series. You can see the favorite series of all Series Overview users. By sharing your favorite series, you can get inspired by others and explore new series.';

    /* Choose Template */
    include use_template('main');
}

/* Overview page */
elseif (new_route('/DDWT22/week1/overview/', 'get')) {
    /* Page info */
    $page_title = 'Overview';
    $breadcrumbs = get_breadcrumbs([
        'DDWT22' => na('/DDWT22/', False),
        'Week 1' => na('/DDWT22/week1/', False),
        'Overview' => na('/DDWT22/week1/overview', True)
    ]);
    $navigation = get_navigation([
        'Home' => na('/DDWT22/week1/', False),
        'Overview' => na('/DDWT22/week1/overview', True),
        'Add Series' => na('/DDWT22/week1/add/', False)
    ]);

    /* Page content */
    $right_column = use_template('cards');
    $page_subtitle = 'The overview of all series';
    $page_content = 'Here you find all series listed on Series Overview.';

    /* automatically generating a table */
    if (!empty($table_exp)){
        $left_content = $table_exp;
    }
    else{
        /* adding a pre-made table if no database is connected */
        $left_content = '
    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col">Series</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th scope="row">House of Cards</th>
            <td><a href="/DDWT22/week1/series/" role="button" class="btn btn-primary">More info</a></td>
        </tr>

        <tr>
            <th scope="row">Game of Thrones</th>
            <td><a href="/DDWT22/week1/series/" role="button" class="btn btn-primary">More info</a></td>
        </tr>

        </tbody>
    </table>';
    }

    /* Choose Template */
    include use_template('main');
}

/* Single series */
elseif (new_route('/DDWT22/week1/series/', 'get')) {
    $series_id = $_GET["series_id"];
    $series_info = get_series_info($db, $series_id);
    /* Get series from db */
    $series_name = $series_info["name"];
    $series_abstract = $series_info["abstract"];
    $nbr_seasons = $series_info["seasons"];
    $creators = $series_info["creator"];

    /* Page info */
    $page_title = $series_name;
    $breadcrumbs = get_breadcrumbs([
        'DDWT22' => na('/DDWT22/', False),
        'Week 1' => na('/DDWT22/week1/', False),
        'Overview' => na('/DDWT22/week1/overview/', False),
        $series_name => na('/DDWT22/week1/series/', True)
    ]);
    $navigation = get_navigation([
        'Home' => na('/DDWT22/week1/', False),
        'Overview' => na('/DDWT22/week1/overview', True),
        'Add Series' => na('/DDWT22/week1/add/', False)
    ]);

    /* Page content */
    $right_column = use_template('cards');
    $page_subtitle = sprintf('Information about %s', $series_name);
    $page_content = $series_abstract;

    /* Choose Template */
    include use_template('series');
}

/* Add series GET */
elseif (new_route('/DDWT22/week1/add/', 'get')) {
    /* Page info */
    $page_title = 'Add Series';
    $breadcrumbs = get_breadcrumbs([
        'DDWT22' => na('/DDWT22/', False),
        'Week 1' => na('/DDWT22/week1/', False),
        'Add Series' => na('/DDWT22/week1/new/', True)
    ]);
    $navigation = get_navigation([
        'Home' => na('/DDWT22/week1/', False),
        'Overview' => na('/DDWT22/week1/overview', False),
        'Add Series' => na('/DDWT22/week1/add/', True)
    ]);

    /* Page content */
    $right_column = use_template('cards');
    $page_subtitle = 'Add your favorite series';
    $page_content = 'Fill in the details of you favorite series.';
    $submit_btn = 'Add Series';
    $form_action = '/DDWT22/week1/add/';

    /* Choose Template */
    include use_template('new');
}

/* Add series POST */
elseif (new_route('/DDWT22/week1/add/', 'post')) {
    /* Page info */
    $page_title = 'Add Series';
    $breadcrumbs = get_breadcrumbs([
        'DDWT22' => na('/DDWT22/', False),
        'Week 1' => na('/DDWT22/week1/', False),
        'Add Series' => na('/DDWT22/week1/add/', True)
    ]);
    $navigation = get_navigation([
        'Home' => na('/DDWT22/week1/', False),
        'Overview' => na('/DDWT22/week1/overview', False),
        'Add Series' => na('/DDWT22/week1/add/', True)
    ]);

    /* Page content */
    $right_column = use_template('cards');
    $page_subtitle = 'Add your favorite series';
    $page_content = 'Fill in the details of you favorite series.';
    $submit_btn = 'Add Series';
    $form_action = '/DDWT22/week1/add/';

    $post_arr = $_POST;
    $error_msg = get_error(add_series($db, $post_arr));

    include use_template('new');
}

/* Edit series GET */
elseif (new_route('/DDWT22/week1/edit/', 'get')) {
    /* Get series info from db */
    $series_id = $_GET["series_id"];
    $series_info = get_series_info($db, $series_id);
    $series_name = $series_info["name"];

    /* Page info */
    $page_title = 'Edit Series';
    $breadcrumbs = get_breadcrumbs([
        'DDWT22' => na('/DDWT22/', False),
        'Week 1' => na('/DDWT22/week1/', False),
        sprintf('Edit Series %s', $series_name) => na('/DDWT22/week1/new/', True)
    ]);
    $navigation = get_navigation([
        'Home' => na('/DDWT22/week1/', False),
        'Overview' => na('/DDWT22/week1/overview', False),
        'Add Series' => na('/DDWT22/week1/add/', False)
    ]);

    /* Page content */
    $right_column = use_template('cards');
    $page_subtitle = sprintf('Edit %s', $series_name);
    $page_content = 'Edit the series below.';
    $form_action = '/DDWT22/week1/edit/';
    $submit_btn = 'Confirm Editing';
    /* Choose Template */
    include use_template('new');
}

/* Edit series POST */
elseif (new_route('/DDWT22/week1/edit/', 'post')) {
    /* Get series info from db */

    $post_arr = $_POST;
    $feedback_msg = update_series($db, $post_arr);
    $error_msg = get_error($feedback_msg);

    $series_id = $post_arr['series_id'];
    /* Page info */
    if ($feedback_msg['type'] === 'success'){
        $page_title = $post_arr['s_name'];
        $series_name = $post_arr['s_name'];
        $series_abstract = $post_arr['s_abstract'];
        $nbr_seasons = $post_arr['num_seasons'];
        $creators = $post_arr['creators'];
    }
    else{
        $series_info = get_series_info($db, $series_id);
        $series_name = $series_info["name"];
        $page_title = $series_info["name"];
        $series_abstract = $series_info["abstract"];
        $nbr_seasons = $series_info["seasons"];
        $creators = $series_info["creator"];
    }

    $breadcrumbs = get_breadcrumbs([
        'DDWT22' => na('/DDWT22/', False),
        'Week 1' => na('/DDWT22/week1/', False),
        'Overview' => na('/DDWT22/week1/overview/', False),
        $series_name => na('/DDWT22/week1/series/', True)
    ]);
    $navigation = get_navigation([
        'Home' => na('/DDWT22/week1/', False),
        'Overview' => na('/DDWT22/week1/overview', False),
        'Add Series' => na('/DDWT22/week1/add/', False)
    ]);

    /* Page content */
    $right_column = use_template('cards');
    $page_subtitle = sprintf('Information about %s', $series_name);
    $page_content = $post_arr['s_abstract'];

    $error_msg = get_error(update_series($db, $post_arr));
    /* Choose Template */
    include use_template('series');
}

/* Remove series */
elseif (new_route('/DDWT22/week1/remove/', 'post')) {
    /* Remove series in database */
    $series_id = $_POST['series_id'];
    $feedback_msg = remove_series($db, $series_id);
    $error_msg = get_error($feedback_msg);

    /* Page info */
    $page_title = 'Overview';
    $breadcrumbs = get_breadcrumbs([
        'DDWT22' => na('/DDWT22/', False),
        'Week 1' => na('/DDWT22/week1/', False),
        'Overview' => na('/DDWT22/week1/overview', True)
    ]);
    $navigation = get_navigation([
        'Home' => na('/DDWT22/week1/', False),
        'Overview' => na('/DDWT22/week1/overview', True),
        'Add Series' => na('/DDWT22/week1/add/', False)
    ]);

    /* Page content */
    $right_column = use_template('cards');
    $page_subtitle = 'The overview of all series';
    $page_content = 'Here you find all series listed on Series Overview.';
    $left_content = '
    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col">Series</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th scope="row">House of Cards</th>
            <td><a href="/DDWT22/week1/series/" role="button" class="btn btn-primary">More info</a></td>
        </tr>

        <tr>
            <th scope="row">Game of Thrones</th>
            <td><a href="/DDWT22/week1/series/" role="button" class="btn btn-primary">More info</a></td>
        </tr>

        </tbody>
    </table>';

    /* Choose Template */
    include use_template('main');
}

else {
    http_response_code(404);
    echo '404 Not Found';
}
