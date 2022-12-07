<?php
/**
 * Controller
 *
 * Database-driven Webtechnology
 * Taught by Stijn Eikelboom
 * Based on code by Reinard van Dalen
 */

/* Require composer autoloader */
require __DIR__ . '/vendor/autoload.php';

/* Include model.php */
include 'model.php';

/* Connect to DB */
$db = connect_db('localhost', 'ddwt22_week3', 'ddwt22', 'ddwt22');

/* Get user's cred */
$cred = set_cred('ddwt22','ddwt22');

/* Create Router instance */
$router = new \Bramus\Router\Router();
$router->before('GET|POST|PUT|DELETE', '/api/.*', function() use($cred){
    if (!check_cred($cred)){
        echo json_encode([
            'type' => 'warning',
            'message' => 'Authentication failed'
        ]);
        http_response_code(401);
        exit();
    }
    echo json_encode([
        'type' => 'success',
        'message' => 'Authentication succeed'
    ]);
});

// Add routes here
$router->mount('/api', function() use ($router, $db) {
    header(http_content_type('application/json'));

    /* GET for reading all series */
    $router->get('/series', function() use($db) {
        $series_arr = get_series($db);
        echo json_encode($series_arr);
    });

    /* GET for reading individual series */
    $router->get('/series/(\d+)', function($id) use($db) {
        $series_info = get_series_info($db, $id);
        echo json_encode($series_info);
    });

    /* DELETE individual series */
    $router->delete('/series/(/d+)', function($id) use ($db) {
        $remove_feedback = remove_series($db, $id);
        echo json_encode($remove_feedback);
    });

    /* ADD new series */
    $router->post('/series', function() use ($db){
       $feedback = add_series($db, $_POST);
       echo json_encode($feedback);
    });

    /* UPDATE the series */
    $router->put('/series/(\d+)', function($id) use($db){
        $_PUT = array();
        parse_str(file_get_contents('php://input'), $_PUT);
        $series_info = $_PUT + ["series_id" => $id];
        $feedback = update_series($db, $series_info);
        echo json_encode($feedback);
    });

});

$router->set404(function() {
    header('HTTP/1.1 404 Not Found');
    echo 'Do not scream or panic! There is no page here yet.
    Just calmly go back and try again. Please. No panic. RGKSRHBG';
});

/* Run the router */
$router->run();
