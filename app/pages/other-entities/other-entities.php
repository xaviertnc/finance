<?php

  include $app->componentsPath . '/SelectListItem.php';
  include $app->componentsPath . '/DropdownSelect.php';


  // ----------------------
  // -------- PAGE --------
  // ----------------------

  $page = new stdClass();
  $page->title = 'Other Transaction Entities';
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
  // -------- MODEL --------
  // ----------------------

  DB::connect($app->dbConnection);

  include $page->modelFilePath;
  $model = new EntitiesModel();

  

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

      if ($request->action == 'add-entity') {
        $model->insertEntity(array_get($_POST, 'entity'));
        $alerts[] = ['success', 'Congrats on a nice new entity!', 0];
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

    // Get Entities List
    $entities = $model->listEntities();
    
    // Get next supplier account number
    if ($entities) {
      $lastEntity = end($entities);
      $nextAccNo = 'E' . str_pad(substr($lastEntity->acc_no, 1) + 1, 5, '0', STR_PAD_LEFT);
    } else { 
      $nextAccNo = 'C00001';
    }
    
    // Get Entity Groups Dropdown List
    $groupsDropdown = new DropdownSelect(
      'entity[group_id]', $model->listEntityGroups(), null, true, true, '- Select Group -');

    // Get Chart Of Accounts Dropdown List
    $chartOfAccountsDropdown = new DropdownSelect(
      'entity[ledger_acc_id]', $model->listChartOfAccounts(), null, true, true, '- Select Ledger Account -');

    // Render View!
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
