<?php

  DB::connect($app->dbConnection);
  
  $page = new stdClass();
  $page->breadcrumbs = ['Suppliers' => 'suppliers']; 
  $page->title = 'Edit Supplier';
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
  // ------ REQUEST -------
  // ----------------------  

  $supplier_id = array_get($_GET, 'id');

  

  // ----------------------
  // -------- MODEL --------
  // ----------------------

  include $page->modelFilePath;  
  $model = new SupplierModel();
  $supplier = $model->getSupplier($supplier_id);


  
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

      $alerts[] = ['info', 'Hey, you posted some data with action: "' .  $request->action . '"', 0];

      if ($request->action == 'update-supplier')
      {
        if ($model->updateSupplier($supplier_id, array_get($_POST, 'supplier', [])))
        {
          $alerts[] = ['success', 'Update succesful.', 0];
        }
        else
        {
          $alerts[] = ['danger', 'Oops, something went wrong!', 0];
        }
        break;
      }
      
      if ($request->action == 'add-package')
      {
        if ($model->addPackage($supplier_id, array_get($_POST, 'package', [])))
        {
          $alerts[] = ['success', 'Package added.', 0];
        }
        else
        {
          $alerts[] = ['danger', 'Oops, something went wrong!', 0];
        }
        break;
      }        

      if ($request->action == 'delete-item') {
        $alerts[] = ['danger', 'Aaww! You just deleted little Timmy #' . $request->params .' :-(', 0];
        break;
      }

    } while (0);

    // FLASH Messages
    $app->session->put('errors', $errors);
    $app->session->put('alerts', $alerts);
    
    $response->redirectTo = $request->back ?: $request->uri;
  }


  
  // ----------------------
  // -------- GET ---------
  // ----------------------
  else {
    
    // Get supplier packages
    $packages = $model->listSupplierPackages($supplier_id);
    
    // Get View
    include $app->partialsPath . '/head.html';
    include $view->partialFile($app->page->dir, $app->page->viewFilePath, 'html', 3, null, '        ');
    include $app->partialsPath . '/foot.html';

    // Save the APP and PAGE state before exit.
    $app->session->put('csrfToken', $page->csrfToken);
    $app->session->put($page->id, $page->state);

    // Remove FLASHED messages
    $app->session->forget('errors');
    $app->session->forget('alerts');

  }
