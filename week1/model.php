<?php
/**
 * Model
 *
 * Database-driven Webtechnology
 * Taught by Stijn Eikelboom
 * Based on code by Reinard van Dalen
 */

/* Enable error reporting */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * Check if the route exists
 * @param string $route_uri URI to be matched
 * @param string $request_type Request method
 * @return bool
 *
 */
function new_route($route_uri, $request_type){
    $route_uri_expl = array_filter(explode('/', $route_uri));
    $current_path_expl = array_filter(explode('/',parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
    if ($route_uri_expl == $current_path_expl && $_SERVER['REQUEST_METHOD'] == strtoupper($request_type)) {
        return True;
    } else {
        return False;
    }
}

/**
 * Creates a new navigation array item using URL and active status
 * @param string $url The URL of the navigation item
 * @param bool $active Set the navigation item to active or inactive
 * @return array
 */
function na($url, $active){
    return [$url, $active];
}

/**
 * Creates filename to the template
 * @param string $template Filename of the template without extension
 * @return string
 */
function use_template($template){
    return sprintf("views/%s.php", $template);
}

/**
 * Creates breadcrumbs HTML code using given array
 * @param array $breadcrumbs Array with as Key the page name and as Value the corresponding URL
 * @return string HTML code that represents the breadcrumbs
 */
function get_breadcrumbs($breadcrumbs) {
    $breadcrumbs_exp = '<nav aria-label="breadcrumb">';
    $breadcrumbs_exp .= '<ol class="breadcrumb">';
    foreach ($breadcrumbs as $name => $info) {
        if ($info[1]){
            $breadcrumbs_exp .= '<li class="breadcrumb-item active" aria-current="page">'.$name.'</li>';
        } else {
            $breadcrumbs_exp .= '<li class="breadcrumb-item"><a href="'.$info[0].'">'.$name.'</a></li>';
        }
    }
    $breadcrumbs_exp .= '</ol>';
    $breadcrumbs_exp .= '</nav>';
    return $breadcrumbs_exp;
}

/**
 * Creates navigation bar HTML code using given array
 * @param array $navigation Array with as Key the page name and as Value the corresponding URL
 * @return string HTML code that represents the navigation bar
 */
function get_navigation($navigation){
    $navigation_exp = '<nav class="navbar navbar-expand-lg navbar-light bg-light">';
    $navigation_exp .= '<a class="navbar-brand">Series Overview</a>';
    $navigation_exp .= '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">';
    $navigation_exp .= '<span class="navbar-toggler-icon"></span>';
    $navigation_exp .= '</button>';
    $navigation_exp .= '<div class="collapse navbar-collapse" id="navbarSupportedContent">';
    $navigation_exp .= '<ul class="navbar-nav mr-auto">';
    foreach ($navigation as $name => $info) {
        if ($info[1]){
            $navigation_exp .= '<li class="nav-item active">';
        } else {
            $navigation_exp .= '<li class="nav-item">';
        }
        $navigation_exp .= '<a class="nav-link" href="'.$info[0].'">'.$name.'</a>';

        $navigation_exp .= '</li>';
    }
    $navigation_exp .= '</ul>';
    $navigation_exp .= '</div>';
    $navigation_exp .= '</nav>';
    return $navigation_exp;
}

/**
 * Pretty Print Array
 * @param $input
 */
function p_print($input){
    echo '<pre>';
    print_r($input);
    echo '</pre>';
}

/**
 * Creates HTML alert code with information about the success or failure
 * @param array $feedback Associative array with keys type and message
 * @return string
 */
function get_error($feedback){
    return '
        <div class="alert alert-'.$feedback['type'].'" role="alert">
            '.$feedback['message'].'
        </div>';
}

function connect_db($host, $db, $user, $pass){
    $charset = 'utf8mb4';
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (PDOException $e) {
        echo sprintf("Failed to connect. %s",$e->getMessage());
    }
    return $pdo;
}

function count_series($pdo){
    /* Check if book already exists */
    $stmt = $pdo->prepare('SELECT * FROM series');
    $stmt -> execute();
    $stmt -> fetchAll();
    return $stmt->rowCount();
}

function get_series($pdo){
    $series_arr = array();
    $stmt = $pdo->prepare('SELECT * FROM series');
    $stmt -> execute();
    $series = $stmt -> fetchAll();
    foreach ($series as $key => $value){
        foreach ($value as $user_key => $user_input){
            $series_arr[$key][$user_key] = htmlspecialchars($user_input);
        }
    }
    return $series_arr;
}

function get_series_info($pdo, $series_id){
    $series_arr = array();
    $stmt = $pdo->prepare('SELECT * FROM series where id = ?');
    $stmt -> execute([$series_id]);
    $series = $stmt -> fetch();
    foreach ($series as $key => $value){
        $series_arr[$key] = htmlspecialchars($value);
    }
    return $series_arr;
}

function get_series_table($series_arr){
    $table_exp =
        '
<table class="table table-hover">
<thead
<tr>
<th scope="col">Series</th>
<th scope="col"></th>
</tr>
</thead>
<tbody>';
    foreach($series_arr as $key => $value){
        $table_exp .=
            '
<tr>
<th scope="row">'.$value['name'].'</th>
<td><a href="/DDWT22/week1/series/?series_id='.$value['id'].'" role="button" class="btn btn-primary">More info</a></td>
</tr>
';
}
$table_exp .=
'
</tbody>
</table>
';
return $table_exp;
}

function add_series($pdo, $post_arr){

    /* check if series already exists in database */
    $stmt = $pdo->prepare('SELECT * FROM series WHERE name = ?');
    $stmt->execute([$post_arr["s_name"]]);
    $series = $stmt->rowCount();

    if ($series) {
        return array('type' => 'danger', 'message' => "Added series already exists in the database.");
    }
    elseif (
        /* Check if all fields are not empty */
        empty($post_arr["s_name"]) or
        empty($post_arr["creators"]) or
        empty($post_arr["num_seasons"]) or
        empty($post_arr["s_abstract"])
    ){
        return array('type' => 'danger', 'message' => "Some of the fields are not empty");
    }
    elseif (!is_numeric($post_arr['num_seasons'])){
        /* Check if num_seasons's type is a number */
        return array('type' => 'danger', 'message' => "Number of seasons is not numeral.");
    }
    else{
        /* Add new series */
        $stmt = $pdo->prepare("INSERT INTO series (name, creator, seasons, abstract) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $post_arr["s_name"],
            $post_arr["creators"],
            $post_arr["num_seasons"],
            $post_arr["s_abstract"]
        ]);
        $inserted = $stmt->rowCount();
        if ($inserted == 1) {
            return array('type' => 'success', 'message' => 'Series was successfully created.');
        }
        else {
            return array('type' => 'danger', 'message' => "Series wasn't added. There was an error.");
        }
    }
}

function update_series($pdo, $post_arr){

    /* check if series already exists in database */
    $stmt = $pdo->prepare('SELECT * FROM series WHERE name = ?');
    $stmt->execute([$post_arr["s_name"]]);
    $series = $stmt -> fetch();
    if ($series and $series['id'] !== $post_arr['series_id']) {
        return array('type' => 'danger', 'message' => "The series with this name already exists in the database.");
    }
    elseif (
        /* Check if all fields are not empty */
        empty($post_arr["s_name"]) or
        empty($post_arr["creators"]) or
        empty($post_arr["num_seasons"]) or
        empty($post_arr["s_abstract"])
    ){
        return array('type' => 'danger', 'message' => "Some of the fields are not empty");
    }
    elseif (!is_numeric($post_arr['num_seasons'])){
        /* Check if num_seasons's type is a number */
        return array('type' => 'danger', 'message' => "Number of seasons is not numeral.");
    }
    else{
        /* Add new series */
        $stmt = $pdo->prepare("UPDATE series SET name = ?, creator = ?, seasons = ?, abstract = ? WHERE id = ?");
        $stmt->execute([
            $post_arr["s_name"],
            $post_arr["creators"],
            $post_arr["num_seasons"],
            $post_arr["s_abstract"],
            $post_arr["series_id"]
        ]);
        return array('type' => 'success', 'message' => 'Series was successfully updated.');
    }
}

function remove_series($pdo, $series_id){
    $stmt = $pdo->prepare("DELETE FROM series WHERE id = ?");
    $stmt->execute([$series_id]);
    $deleted = $stmt->rowCount();
    if ($deleted == 1){
        return array('type' => 'success', 'message' => "Series was successfully deleted.");
    }
    else{
        return array('type' => 'danger', 'message' => "Series wasn't deleted, an error is occurred.");
    }
}
