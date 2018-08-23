<?php

  include $app->componentsPath . '/SelectListItem.php';
  include $app->componentsPath . '/DropdownSelect.php';


  // ----------------------
  // ------ REQUEST -------
  // ----------------------

  $transaction_id = array_get($_GET, 'id');



  // ----------------------
  // -------- PAGE --------
  // ----------------------

  $page = new stdClass();
  $page->breadcrumbs = ['Bank Transactions' => 'banktrxs'];
  $page->title = 'Bank Transaction';
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
  $model = new TransactionModel();    

  

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

      if ($request->action == 'update-transaction')
      {
        if ($model->updateTransaction($transaction_id, array_get($_POST, 'transaction', [])))
        {
          $alerts[] = ['success', 'Update succesful.', 0];
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

    $transaction = $model->getTransaction($transaction_id);

    // Handle Ajax requests to update the select-dropdowns
    if ($request->isAjax)
    {
      switch (array_get($_GET, 'do'))
      {
          case 'update-dropdowns':
            // Get Entities Dropdown List
            $groupsDropdown = new DropdownSelect(
              'transaction[entity_id]',
              $model->listEntities(array_get($_GET, 'entity-group')), 
              null, true, true, '- Select Entity -');
          
            // Get Chart Of Accounts Dropdown List
            $chartOfAccountsDropdown = new DropdownSelect(
              'transaction[ledger_acc_id]',
              $model->listChartOfAccounts(array_get($_GET, 'entity-group')),
              null, true, true, '- Select Ledger Account -');
              
            die ('<html>' . $groupsDropdown . $chartOfAccountsDropdown . '</html>');
            
          default:
            http_response_code(500);
            die('Oops! Servy have NO CLUE. :/');          
      }      
    }
    
    // Get Entity Groups Dropdown List
    $groupsDropdown = new DropdownSelect(
      'transaction[entity_group_id]', $model->listEntityGroups(), $transaction->entity_group_id, true, true, '- Select Group -');



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
