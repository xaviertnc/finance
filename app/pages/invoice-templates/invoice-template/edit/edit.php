<?php

  include $app->componentsPath . '/SelectListItem.php';
  include $app->componentsPath . '/DropdownSelect.php';


  // ----------------------
  // ------ REQUEST -------
  // ----------------------

  $client_id = array_get($_GET, 'client');
  $template_id = array_get($_GET, 'id');



  // ----------------------
  // -------- PAGE --------
  // ----------------------

  $page = new stdClass();
  $page->breadcrumbs = [
    'Clients' => 'clients',
    'Client'  => 'clients/client/edit?id=' . $client_id
  ];
  $page->title = 'Edit Recurring Invoice';
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
  $model = new InvoiceTemplateModel();
  $template = $model->getInvoiceTemplate($template_id);



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

      if ($request->action == 'update-template')
      {
        if ($model->updateInvoiceTemplate($template_id, array_get($_POST, 'template', [])))
        {
          $alerts[] = ['success', 'Update succesful.', 0];
        }
        else
        {
          $alerts[] = ['danger', 'Oops, something went wrong!', 0];
        }
        break;
      }

      if ($request->action == 'add-template-item')
      {
        if ($model->addInvoiceTemplateItem($template_id, array_get($_POST, 'template-item', [])))
        {
          $alerts[] = ['success', 'Invoice template item added.', 0];
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

    // Get InvoiceTemplate Items List
    $templateItems = $model->listInvoiceTemplateItems($template_id);
    
    // Get Invoice Totals
    $invoiceTotals = new stdClass();
    $invoiceTotals->subtotal = 0;
    foreach ($templateItems as $item) {
      $invoiceTotals->subtotal += ($item->unit_price * $item->quantity);
    }
    $invoiceTotals->vat = $template->vat_invoice ? $invoiceTotals->subtotal * ($app->VAT/100) : 0;
    $invoiceTotals->grandTotal = round($invoiceTotals->subtotal + $invoiceTotals->vat, 2);

    // Get Products Dropdown List
    $billingPeriodsDropdown = new DropdownSelect(
      'template[billing_period_id]', $model->listBillingPeriods(), $template->billing_period_id, true, true, '- Select Billing Period -');
      
    // Get Products Dropdown List
    $productsDropdown = new DropdownSelect(
      'template-item[product_id]', $model->listProducts(), null, true, true, '- Select Product -');

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
