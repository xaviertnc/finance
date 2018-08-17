<?php

  DB::connect($app->dbConnection);
  
  $page = new stdClass();
  $page->title = 'Transactions';
  $page->dir = $app->controllerPath;
  $page->id = 'page_' . $app->currentPage;  
  $page->state = $app->session->get($page->id, []);
  $page->errors = $app->session->get('errors', []);
  $page->alerts = $app->session->get('alerts', []);
  $page->lastCsrfToken = $app->session->get('csrfToken');
  $page->basename = substr(__FILE__, 0, strlen(__FILE__)-4);
  $page->modelFilePath = $page->dir . '/model.php';
  $page->viewFilePath = $page->basename . '.html';
  $page->csrfToken = md5(uniqid(rand(), true)); //time();

  $app->page = $page;
  

  
  // ----------------------
  // -------- POST --------
  // ----------------------
  if ($request->method == 'POST')
  {
    do {

      $errors = [];
      $alerts = [];

      $request->action = array_get($_POST, '__ACTION__');
      $request->params = array_get($_POST, '__PARAMS__');

      $alerts[] = ['info', 'Hey, you posted some data.', 3000];

      if ($request->action == 'refresh') {
        $alerts[] = ['success', 'Congrats on a nice refresh!', 5000];
        break;
      }

      if ($request->action == 'delete-item') {
        $alerts[] = ['danger', 'Aaww! You just deleted little Timmy #' . $request->params .' :-(', 0];
        break;
      }

    } while (0);

    $app->session->flash('errors', $errors);
    $app->session->flash('alerts', $alerts);
    
    $response->redirectTo = $request->back ?: $request->uri;
  }


  
  // ----------------------
  // -------- GET ---------
  // ----------------------
  else {

    include $page->modelFilePath;
    
    include $app->partialsPath . '/head.html';
    include $view->partialFile($app->page->dir, $app->page->viewFilePath, 'html', 3, null, '        ');
    include $app->partialsPath . '/foot.html';

    // Save the APP-STATE before we exit.
    $app->session->flash('csrfToken', $page->csrfToken);
    $app->session->put($page->id, $page->state);

  }
