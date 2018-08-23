<?php

  include $app->componentsPath . '/SelectListItem.php';
  include $app->componentsPath . '/DropdownSelect.php';

  function getTrxEntityName($clients, $suppliers, $entities, $trx)
  {
    if ($trx->entity_group_id == 1) {
      $client = array_get($clients, $trx->entity_id);
      return $client ? $client->name : '-';
    }
    if ($trx->entity_group_id == 2) {
      $supplier = array_get($suppliers, $trx->entity_id);
      return $supplier ? $supplier->name : '-';
    }    
    $entity = array_get($entities, $trx->entity_id);
    return $entity ?: '-';
  }
  

  
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

  include $page->modelFilePath;
  $model = new BankModel();

  // Get account names
  $bankAccountNames = $model->listBankAccountNames();
  $accountShortNames = array_keys($bankAccountNames);
  if ( ! $accShortName) { $accShortName = reset($accountShortNames); }

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

      // $alerts[] = ['info', 'Hey, you posted some data.', 3000];

      if ($request->action == 'run-auto-detect')
      {
        $alerts[] = ['warning', 'Lekker manne, kom ons looi daai ding.', 0];

        // Note: Db/Cr can be determined from the sign of the trx amount! The sign dictates
        // Db or Cr on the current account and the reverse on the ledger acc.

        // Get chart_of_accounts
        $chart_of_accounts = $model->listChartOfAccounts();

        // Get bank-transfer entities
        $transferEntities = $model->listTransferEntities();
        
        // Detect Bank Transfers FIRST
        foreach ($transactions as $trx)
        {
          foreach ($transferEntities as $entity)
          {
            if (preg_match($entity->regex, $trx->description))
            {
              $app->log->bank_detect('Found transfer from: ' . $entity->name);
              $ledger_acc = array_get($chart_of_accounts, $entity->ledger_acc_id);
              $model->updateBankTrx($accShortName, $trx->id, [
                'entity_group_id' => $entity->group_id,
                'entity_id'       => $entity->id,
                'category_id'     => $ledger_acc->category_id,
                'subcategory_id'  => $ledger_acc->subcategory_id,
                'ledger_acc_id'   => $ledger_acc->id,
                'status_id'       => 1 // TRF assign
              ]);
            }
          }
        }

        // Clients by INxxx
        foreach ($transactions as $trx) {
          if ($trx->ledger_acc_id) { continue; }
          $regex = '/IN(?:V|O)?\s*(\d{5,6})/i';
          if (preg_match($regex, $trx->description, $matches)) {
            $invoice_no = (int) $matches[1];
            $app->log->bank_detect('matches = ' . print_r($matches, true));
            $invoice = $model->getInvoice($invoice_no);
            $entity = $model->getClient($invoice->client_id);
            if ( ! $entity) { continue; }
            $ledger_acc = array_get($chart_of_accounts, $entity->ledger_acc_id?:143);
            $model->updateBankTrx($accShortName, $trx->id, [
              'entity_group_id' => 1, // Clients
              'entity_id'       => $entity->id,
              'category_id'     => $ledger_acc->category_id,
              'subcategory_id'  => $ledger_acc->subcategory_id,
              'ledger_acc_id'   => $ledger_acc->id,
              'status_id'       => 2 // INxxxx Assign
            ]);
          }
        }
        
        // Clients by Dxxx
        foreach ($transactions as $trx) {
          if ($trx->ledger_acc_id) { continue; }
          $regex = '/D\s*\d{5}/i';
          if (preg_match($regex, $trx->description, $matches)) {
            $client_acc_no = $matches[0];
            $client_acc_no = str_replace(' ', '', $client_acc_no);
            $app->log->bank_detect('matches = ' . print_r($matches, true) . ', acc_no = ' . $client_acc_no);
            $entity = $model->getClientByAccNo($client_acc_no);
            // $app->log->bank_detect('entity = ' . print_r($entity, true));
            if ( ! $entity) { continue; }
            $ledger_acc = array_get($chart_of_accounts, $entity->ledger_acc_id?:143);            
            $model->updateBankTrx($accShortName, $trx->id, [
              'entity_group_id' => 1, // Clients
              'entity_id'       => $entity->id,
              'category_id'     => $ledger_acc->category_id,
              'subcategory_id'  => $ledger_acc->subcategory_id,
              'ledger_acc_id'   => $ledger_acc->id,
              'status_id'       => 3 // Dxxxx Assign
            ]);
          }
        }
        
        // Clients By RegEx
        $clients = $model->listClients();
        foreach ($clients as $entity) {
          $regex = $entity->regex;
          if ( ! $regex) { continue; }
          foreach ($transactions as $trx) {
            if ($trx->ledger_acc_id) { continue; }
            if (preg_match($regex, $trx->description)) {
              $ledger_acc = array_get($chart_of_accounts, $entity->ledger_acc_id?:143); // 143 = Sales - General
              $model->updateBankTrx($accShortName, $trx->id, [
                'entity_group_id' => 1, // Clients
                'entity_id'       => $entity->id,
                'category_id'     => $ledger_acc->category_id,
                'subcategory_id'  => $ledger_acc->subcategory_id,
                'ledger_acc_id'   => $ledger_acc->id,
                'status_id'       => 4 // Regex Assign (Client)
              ]);
            } 
          }
        }
        
        // Suppliers By RegEx
        $suppliers = $model->listSuppliers();
        foreach ($suppliers as $entity) {
          $regex = $entity->regex;
          if ( ! $regex) { continue; }
          foreach ($transactions as $trx) {
            if ($trx->ledger_acc_id) { continue; }
            if ($entity->ledger_acc_id and preg_match($regex, $trx->description)) {
              $ledger_acc = array_get($chart_of_accounts, $entity->ledger_acc_id?:187); // 187 = Cost of Sales - General 
              $model->updateBankTrx($accShortName, $trx->id, [
                'entity_group_id' => 2, // Suppliers
                'entity_id'       => $entity->id,
                'category_id'     => $ledger_acc->category_id,
                'subcategory_id'  => $ledger_acc->subcategory_id,
                'ledger_acc_id'   => $ledger_acc->id,
                'status_id'       => 5 // Regex Assign (Supplier)
              ]);
            } 
          }
        }
        
        // Other Entities By RegEx
        $otherEntities = $model->listOtherEntities();
        foreach ($otherEntities as $entity) {
          $regex = $entity->regex;
          if ( ! $regex) { continue; }
          foreach ($transactions as $trx) {
            if ($trx->ledger_acc_id) { continue; }
            $ledger_acc = array_get($chart_of_accounts, $entity->ledger_acc_id);
            if (preg_match($regex, $trx->description)) {
              $model->updateBankTrx($accShortName, $trx->id, [
                'entity_group_id' => $entity->group_id,
                'entity_id'       => $entity->id,
                'category_id'     => $ledger_acc->category_id,
                'subcategory_id'  => $ledger_acc->subcategory_id,
                'ledger_acc_id'   => $ledger_acc->id,
                'status_id'       => 6 // Regex Assign (Other)
              ]);
            } 
          }
        }
        
        break;
      }
      
      if ($request->action == 'run-auto-assign') {
        $alerts[] = ['danger', 'SHIT Manne, kom ons probeer hom reg kry...', 0];
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
    
    $clients = $model->listClients();
    
    $suppliers = $model->listSuppliers();
    
    $entities = $model->listEntities();
    
    $trx_statuses = $model->listStatuses();
    // $acc_subcategories = $model->listAccSubCategories();
    $chart_of_accounts = $model->listChartOfAccounts();

    // Get Bank Accounts Dropdown
    $accNamesDropdown = new DropdownSelect('acc', $bankAccountNames, $accShortName, true, true, '- Select Account -');

    // Get Months Dropdown
    include $app->modelsPath . '/collections/TaxMonths.php';
    $taxMonths = new TaxMonths($taxYear);    
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
