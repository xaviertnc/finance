<?php // Ultra Simple PHP app...

define('__DEBUG__', true);
define('__ENV_PROD__', false);


function array_get(array $array, $key, $default = null) {
  return isset($array[$key]) ? $array[$key] : $default;
}


ob_start();


register_shutdown_function(function(){
  if (error_get_last() !== null) {
    ob_clean();
    http_response_code(500); // Could do a 202 error (if ajax request) and redirect to the relevant error page!
    echo '<div class="error server-error"><h3>Oops, something went wrong!</h3>', PHP_EOL;
    if (__DEBUG__) { echo '<hr><pre>', print_r(error_get_last(), true), '</pre>'; }
    echo PHP_EOL, '</div>';
	}
});


$request = new stdClass();
$request->uri = $_SERVER['REQUEST_URI'];
$request->host = '//nm.localhost';
$request->uriBase = '/finance/';
$request->urlBase = $request->host . $request->uriBase;
$request->method = $_SERVER['REQUEST_METHOD'];
$request->back = array_get($_SERVER, 'HTTP_REFERER');
$request->isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']);
$request->parts = explode('?', $request->uri);
$request->query = isset($request->parts[1]) ? $request->parts[1] : '';
$request->pageref = trim(substr($request->parts[0], strlen($request->uriBase)), '/') ?: 'dashboard';
$request->parts = explode('/', $request->pageref);


$response = new stdClass();


$app = new stdClass();
$app->dbConnection =
[
  'DBHOST' => 'localhost',
  'DBNAME' => 'finance',
  'DBUSER' => 'root',
  'DBPASS' => 'root'
];
$app->VAT = 15;
$app->id = 'MyFinance';
$app->siteName = 'My Finance';
$app->rootPath = 'C:/UniServerZ/vhosts/NM/finance';
$app->request = $request;
$app->response = $response;
$app->appPath = $app->rootPath . '/app';
$app->modelsPath = $app->appPath . '/models';
$app->vendorsPath = $app->appPath . '/vendors';
$app->storagePath = $app->appPath . '/storage';
$app->servicesPath = $app->appPath . '/services';
$app->partialsPath = $app->appPath . '/pages/_partials';
$app->componentsPath = $app->appPath . '/pages/_components';
$app->currentPage = $request->parts[count($request->parts)-1];
$app->controllerPath = $app->appPath . '/pages/' . $request->pageref;
$app->controller = $app->controllerPath . '/' . $app->currentPage . '.php';

if ( ! file_exists($app->controller)) {
  // $app->currentPage = '404';
  // $request->pageRef = 'errors/404';
  $app->controllerPath = $app->appPath . '/pages/errors/404';
  $app->controller = $app->controllerPath . '/404.php';
}


require $app->servicesPath . '/Logger.php';
require $app->servicesPath . '/Session.php';
require $app->servicesPath . '/Database.php';
require $app->servicesPath . '/Format.php';
require $app->servicesPath . '/View.php';


require $app->controller;


// echo '<pre>', print_r($app, true), '</pre>';
// echo '<pre>', print_r($_SERVER, true), '</pre>';
// echo '<pre>', print_r($_SESSION, true), '</pre>';


if (isset($response->redirectTo))
{
  // If you want a HARD REDIRECT after an AJAX POST, just
  // set $request->isAjax == false in the controller.
  if ($request->isAjax)
  {
    // SOFT REDIRECT
    http_response_code(202);
    header('Content-type: application/json');
    header('Cache-Control: no-cache, must-revalidate');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('X-REDIRECT-TO:' . $response->redirectTo);
    echo json_encode(['redirect' => $response->redirectTo]);
    exit;
  }
  // HARD REDIRECT
  header('location:' . $response->redirectTo);
  exit;
}

ob_end_flush();
