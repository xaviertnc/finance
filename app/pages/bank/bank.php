<?php

  include $app->componentsPath . '/SelectListItem.php';
  include $app->componentsPath . '/DropdownSelect.php';


  // ----------------------
  // ------ REQUEST -------
  // ----------------------

  $taxYear = array_get($_GET, 'year', date('Y'));
  $taxMonth = array_get($_GET, 'month', ($taxYear-1).'-02-01');
  $accShortName = array_get($_GET, 'acc');



  // ----------------------
  // -------- PAGE --------
  // ----------------------

  $page = new stdClass();
  $page->breadcrumbs = [];
  $page->title = 'Bank Transactions';
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

  include $app->modelsPath . '/collections/TaxMonths.php';
  $taxMonths = new TaxMonths($taxYear);

  include $page->modelFilePath;
  $model = new BankModel();

  // Get transactions
  $fromDate = $taxMonth;
  $toDate = date('Y-m-01', strtotime($fromDate . ' +1 MONTH'));
  $transactions = $model->listTransactions($accShortName, $fromDate, $toDate);

  
  
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

    // Get Bank Accounts dropdown
    $accountNames = $model->listAccountNames();
    $accountShortNames = array_keys($accountNames);
    if ( ! $accShortName) { $accShortName = reset($accountShortNames); }
    $accNamesDropdown = new DropdownSelect('acc', $accountNames, $accShortName, true, true, '- Select Account -');

    // Get Months Dropdown
    $taxMonthsDropDown = new DropdownSelect('month', $taxMonths->getAll(), $taxMonth);

    // Render view!
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
