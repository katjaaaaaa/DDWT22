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

/* Create Router instance */
$router = new \Bramus\Router\Router();

// Add routes here
$router->mount('/api', function() use ($router, $db) {
    header(http_content_type('application/json'));
    /* GET for reading all series */
    $router->get('/series', function() use($db) {
        $series_arr = get_series($db);
        echo json_encode($series_arr);
    });
});

$router->set404(function() {
    header('HTTP/1.1 404 Not Found');
    echo 'Do not scream or panic! There is no page here yet.
    Just calmly go back and try again. Please. No panic. RGKSRHBG';
});

/* Run the router */
$router->run();
