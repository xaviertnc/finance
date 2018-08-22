<?php

  include $app->componentsPath . '/SelectListItem.php';
  include $app->componentsPath . '/DropdownSelect.php';


  // ----------------------
  // ------ REQUEST -------
  // ----------------------

  $client_id = array_get($_GET, 'id');



  // ----------------------
  // -------- PAGE --------
  // ----------------------

  $page = new stdClass();
  $page->breadcrumbs = ['Clients' => 'clients'];
  $page->title = 'Edit Client';
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
  $model = new ClientModel();
  $client = $model->getClient($client_id);



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

      if ($request->action == 'update-client')
      {
        if ($model->updateClient($client_id, array_get($_POST, 'client', [])))
        {
          $alerts[] = ['success', 'Update succesful.', 0];
        }
        else
        {
          $alerts[] = ['danger', 'Oops, something went wrong!', 0];
        }
        break;
      }

      if ($request->action == 'add-template')
      {
        if ($model->addInvoiceTemplate($client_id, array_get($_POST, 'template', [])))
        {
          $alerts[] = ['success', 'Invoice Template added.', 0];
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

    // Get Chart Of Accounts Dropdown List
    $chartOfAccountsDropdown = new DropdownSelect(
      'client[ledger_acc_id]', $model->listChartOfAccounts(), $client->ledger_acc_id,
      true, true, '- Select Ledger Account -');

    // Get billing periods
    $billingPeriods = $model->listBillingPeriods();

    // Get client invoice templates
    $templates = $model->listInvoiceTemplates($client_id);

    // Get client invoice template items
    foreach ($templates as $template)
    {
      $template->items = $model->listInvoiceTemplateItems($template->id);

      // Get template invoice totals
      $templateTotals = new stdClass();
      $templateTotals->subtotal = 0;
      foreach ($template->items as $item) {
        $templateTotals->subtotal += ($item->cost_price * $item->quantity);
      }
      $templateTotals->vat = $template->vat_invoice ? $templateTotals->subtotal * ($app->VAT/100) : 0;
      $templateTotals->grandTotal = round($templateTotals->subtotal + $templateTotals->vat, 2);
      $template->totals = $templateTotals;
    }

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
