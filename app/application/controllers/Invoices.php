<?php

class Invoices extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->lang->load('calendar');
        $this->load->helper(array('date', 'text', 'typography'));
        $this->load->library('pagination');
        $this->load->model('invoices_model');
        $this->load->model('clients_model');
    }

    // --------------------------------------------------------------------

    function index()
    {
        $data['error'] = "";
        $ccyDec = $this->config->item('currency_decimal');
        $ccyThou = $this->config->item('currency_thousands');

        $data['clientList'] = $this->clients_model->getAllClients(); // activate the option
        $data['extraHeadContent'] = "<script type=\"text/javascript\" src=\"" . base_url() . "js/newinvoice.js\"></script>\n";
        $data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"" . base_url() . "js/search.js\"></script>\n";
        $data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"" . base_url() . "css/invoice.css\" />\n";
        $offset = (int) $this->uri->segment(3, 0);

        $data['query'] = $this->invoices_model->getInvoices('open', $offset,
                5000);

        $data['short_description'] = $this->invoices_model->build_short_descriptions();

        $data['total_rows'] = ($data['query']) ? $data['query']->num_rows() : 0;

        $overdue_count = $this->invoices_model->getInvoices('overdue', 0, 5000);

        $data['overdue_count'] = ($overdue_count) ? $overdue_count->num_rows() : 0;

        $data['message'] = ($this->session->flashdata('message') != '') ? $this->session->flashdata('message') : '';

        $data['status_menu'] = TRUE; // pass status_menu
        $data['page_title'] = $this->lang->line('menu_invoices');

        $this->load->view('invoices/index', $data);
    }

    // --------------------------------------------------------------------

    function overdue($offset = 0)
    {
        $ccyDec = $this->config->item('currency_decimal');
        $ccyThou = $this->config->item('currency_thousands');
        $data['clientList'] = $this->clients_model->getAllClients(); // activate the option
        $data['extraHeadContent'] = "<script type=\"text/javascript\" src=\"" . base_url() . "js/newinvoice.js\"></script>\n";
        $data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"" . base_url() . "css/invoice.css\" />\n";

        $data['query'] = $this->invoices_model->getInvoices('overdue', $offset,
                20);

        $data['total_rows'] = $data['query']->num_rows();
        $config['base_url'] = site_url('invoices/overdue');
        $config['total_rows'] = $this->invoices_model->getInvoices('overdue', 0,
                        10000)->num_rows();
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        $data['status_menu'] = TRUE; // pass status_menu
        $data['page_title'] = $this->lang->line('invoice_overdue');
        $this->load->view('invoices/status_view', $data);
    }

    // --------------------------------------------------------------------

    function open($offset = 0)
    {
        $data['clientList'] = $this->clients_model->getAllClients(); // activate the option
        $data['extraHeadContent'] = "<script type=\"text/javascript\" src=\"" . base_url() . "js/newinvoice.js\"></script>\n";
        $data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"" . base_url() . "css/invoice.css\" />\n";

        $data['query'] = $this->invoices_model->getInvoices('open', $offset, 20);

        $data['total_rows'] = $data['query']->num_rows();

        $config['base_url'] = site_url('invoices/open');
        $config['total_rows'] = $this->invoices_model->getInvoices('open', 0,
                        10000)->num_rows();
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        $data['status_menu'] = TRUE; // pass status_menu
        $data['page_title'] = $this->lang->line('invoice_open');
        $this->load->view('invoices/status_view', $data);
    }

    // --------------------------------------------------------------------

    function closed($offset = 0)
    {
        $ccyDec = $this->config->item('currency_decimal');
        $ccyThou = $this->config->item('currency_thousands');
        $data['clientList'] = $this->clients_model->getAllClients(); // activate the option
        $data['extraHeadContent'] = "<script type=\"text/javascript\" src=\"" . base_url() . "js/newinvoice.js\"></script>\n";
        $data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"" . base_url() . "css/invoice.css\" />\n";

        $data['query'] = $this->invoices_model->getInvoices('closed', $offset,
                20);

        $data['total_rows'] = $data['query']->num_rows();

        $config['base_url'] = site_url('invoices/closed');
        $config['total_rows'] = $this->invoices_model->getInvoices('closed', 0,
                        10000)->num_rows();
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        $data['status_menu'] = TRUE; // pass status_menu
        $data['page_title'] = $this->lang->line('invoice_closed');
        $this->load->view('invoices/status_view', $data);
    }

    // --------------------------------------------------------------------

    function all($offset = 0)
    {
        $ccyDec = $this->config->item('currency_decimal');
        $ccyThou = $this->config->item('currency_thousands');

        $data['clientList'] = $this->clients_model->getAllClients(); // activate the option
        $data['extraHeadContent'] = "<script type=\"text/javascript\" src=\"" . base_url() . "js/newinvoice.js\"></script>\n";
        $data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"" . base_url() . "css/invoice.css\" />\n";

        $data['query'] = $this->invoices_model->getInvoices('all', $offset, 20);
        $data['total_rows'] = $data['query']->num_rows();

        $config['total_rows'] = $this->invoices_model->getInvoices('all', 0,
                        10000)->num_rows();
        $config['base_url'] = site_url('invoices/all');
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        $data['status_menu'] = TRUE; // pass status_menu
        $data['page_title'] = $this->lang->line('invoice_all_invoices');

        $this->load->view('invoices/status_view', $data);
    }

    // --------------------------------------------------------------------

    function recalculate_items()
    {
        $roundFactor = $this->settings_model->getRoundFactor();
        $ccyDec = $this->config->item('currency_decimal');
        $ccyThou = $this->config->item('currency_thousands');

        $amount = 0;
        $tax1_amount = 0;
        $tax2_amount = 0;

        $items = $this->input->post('items');
        $tax1_rate = $this->input->post('tax1_rate');
        $tax2_rate = $this->input->post('tax2_rate');

        foreach ($items as $item)
        {
            $taxable = (isset($item['taxable']) && $item['taxable'] == 1) ? 1 : 0;
            $sub_amount = $item['quantity'] * $item['amount'];
            $amount += $sub_amount;
            $tax1_amount += $sub_amount * (($tax1_rate) / 100) * $taxable;
            $tax2_amount += $sub_amount * (($tax2_rate) / 100) * $taxable;
        }
        //round tax to next 5er
        $tax1_amount = round($tax1_amount / $roundFactor) * $roundFactor;
        //round tax to next 5er
        $tax2_amount = round($tax2_amount / $roundFactor) * $roundFactor;
        $totalAmount = round(($amount + $tax1_amount + $tax2_amount) / $roundFactor) * $roundFactor;
        $totalAmount = number_format($totalAmount, 2, $ccyDec,
                $ccyThou);
        //round tax to next 5er

        echo '{"amount" : "' . number_format($amount, 2, $ccyDec, $ccyThou) . '", "tax1_amount" : "' . number_format($tax1_amount,
                2, $ccyDec, $ccyThou) . '", "tax2_amount" : "' . number_format($tax2_amount,
                2, $ccyDec, $ccyThou) . '", "total_amount" : "' . $totalAmount . '"}';
    }

    // --------------------------------------------------------------------

    function calculateAccountingCurrencyExchangeRate($accountingCurrency,
            $invoiceCurrency, $invoiceDate)
    {
        //first do some sanity checks
        //
            // api_key not set ERGO no calculation
        $api_key = $this->settings_model->get_setting('api_key');
        if ($api_key == null || empty($api_key)) {
            return 0;
        }
        if ($accountingCurrency == "" || $invoiceCurrency == "") {
            //not able to convert yet
            return 0;
        }
        // both currencies are the same no conversion
        if ($invoiceCurrency === $accountingCurrency) {
            return 1;
        }
        //future date cannot convert yet
        if (date("Y-m-d") <= $invoiceDate) {
            //zero indicates not set or not calculated yet 
            return 0;
        }
        // example "http://openexchangerates.org/api/historical/2014-03-03.json?app_id=<api_key>"
        // the free version only supports USD base, therefore we will have to do some conversion.
        //not the same currency now do the grunt work
        $url = "http://openexchangerates.org/api/historical/" . $invoiceDate . ".json?app_id=" . $api_key;
        $jsonResponse = file_get_contents($url);
        if ($jsonResponse == false) {
            return 0;
        }
        $json = json_decode($jsonResponse, true);
        if ($json == null) {
            return 0;
        }
        //because the free version is limited to 50 requests per day 
        //(so they say, my test haven't found a limit yet)
        //we have to check whether we got something back

        try
        {
            if (!array_key_exists('rates', $json) || $json['rates'] == null || $json['rates'][$invoiceCurrency] == null) {
                return 0;
            }
            $invoiceRate = $json['rates'][$invoiceCurrency];
            $accountingRate = $json['rates'][$accountingCurrency];
        } catch (Exception $e)
        {
            //if something goes wrong accessing the variables return 0
            return 0;
        }

        if ($invoiceRate == null || $accountingRate == null) {
            return 0;
        }
        // the free version only supports USD base, therefore we will have to do some conversion.
        $conversionRate = 0;
        if ($accountingCurrency === "USD") {
            $conversionRate = $invoiceRate;
        } else {
            //convert to rate between the two currencies other than USD accounting currency
            $conversionRate = $accountingRate / $invoiceRate;
        }
        $exchange_surplus_middle_exchange_rate = $this->settings_model->get_setting('exchange_surplus_middle_exchange_rate');
        return $conversionRate / (($exchange_surplus_middle_exchange_rate / 100) + 1);
    }

    // --------------------------------------------------------------------

    function newinvoice()
    {
        $ccyDec = $this->config->item('currency_decimal');
        $ccyThou = $this->config->item('currency_thousands');

        $this->load->library('validation');
        $this->load->helper('js_calendar');

        // check if it came from a post, or has a session of clientId
        $client_id = ($this->input->post('client_id') != '') ? $this->input->post('client_id') : $this->session->flashdata('clientId');
        $newName = $this->input->post('newClient');

        if (!isset($client_id)) {
            // if they don't already have a client id, then they need to create the
            // client first, so send them off to do that
            $this->session->set_flashdata('clientName', $newName);
            redirect('clients/newclient');
        }

        $data['row'] = $this->clients_model->get_client_info($client_id); // used to extract name, id and tax info

        $data['tax1_desc'] = $this->settings_model->get_setting('tax1_desc');
        $data['tax1_rate'] = $this->settings_model->get_setting('tax1_rate');
        $data['tax2_desc'] = $this->settings_model->get_setting('tax2_desc');
        $data['tax2_rate'] = $this->settings_model->get_setting('tax2_rate');
        $data['invoice_note_default'] = $this->clients_model->getInvoiceNote($client_id);
        $data['days_payment_due'] = $this->clients_model->getDaysPaymentDue($client_id);

        $last_invoice_number = $this->invoices_model->lastInvoiceNumber($client_id);
        ($last_invoice_number != '') ? $data['lastInvoiceNumber'] = $last_invoice_number : $data['lastInvoiceNumber'] = '';
        $data['suggested_invoice_number'] = (is_numeric($last_invoice_number)) ? $last_invoice_number + 1 : '';

        $taxable = ($data['row']->tax_status == 1) ? 'true' : 'false';

        $data['extraHeadContent'] = "<link type=\"text/css\" rel=\"stylesheet\" href=\"" . base_url() . "css/calendar.css\" />\n";
        $data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"" . base_url() . "css/invoice.css\" />\n";
        $data['extraHeadContent'] .= "<script type=\"text/javascript\">\nvar taxable = " . $taxable . ";\nvar tax1_rate = " . $data['tax1_rate'] . ";\nvar tax2_rate = " . $data['tax2_rate'] . ";\nvar datePicker1 = \"" . date("Y-m-d") . "\";\nvar datePicker2 = \"" . date("F j, Y") . "\";\n</script>\n";
        $data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"" . base_url() . "js/createinvoice.js\"></script>\n";
        $data['extraHeadContent'] .= js_calendar_script('my_form');

        $this->_validation(); // Load the validation rules and fields

        $data['invoiceDate'] = date("Y-m-d");
        $data['row']->currency_symbol = $this->getCurrencySymbol($client_id, $data['row']->currency_symbol);
        $data['row']->currency_type = $this->getCurrencyType($client_id, $data['row']->currency_type);
        $data['currency_symbol'] = $data['row']->currency_symbol;
        $data['currency_type'] = $data['row']->currency_type;
        if ($this->validation->run() == FALSE) {
            $data['error'] = "";
            foreach($this->validation->_error_messages as $msg){
                $err = '<p class="error">'.$msg.'</p>';
                $data['error'] = $data['error'] . $err;
            }
            $this->session->keep_flashdata('clientId');
            $data['invoiceDate'] = $this->validation->dateIssued;
            $data['page_title'] = $this->lang->line('invoice_new_invoice');
            $this->load->view('invoices/newinvoice', $data);
        } else {
            $accCurrExchRate = $this->calculateAccountingCurrencyExchangeRate($this->settings_model->get_setting('currency_type_accounting'),
                    $data['currency_type'], $this->input->post('dateIssued'));
            $invoice_data = array(
                'client_id' => $this->input->post('client_id'),
                'invoice_number' => $this->input->post('invoice_number'),
                'dateIssued' => $this->input->post('dateIssued'),
                'tax1_desc' => $this->settings_model->get_setting('tax1_desc')/*$this->input->post('tax1_description')*/,
                'tax1_rate' => $this->settings_model->get_setting('tax1_rate')/*$this->input->post('tax1_rate')*/,
                'tax2_desc' => $this->settings_model->get_setting('tax2_desc')/*$this->input->post('tax2_description')*/,
                'tax2_rate' => $this->settings_model->get_setting('tax2_rate')/*$this->input->post('tax2_rate')*/,
                'invoice_note' => $this->input->post('invoice_note'),
                'currency_symbol' => $data['currency_symbol'],
                'currency_type' => $data['currency_type'],
                'accounting_invoice_exchange_rate' => $accCurrExchRate,
                'days_payment_due' => $this->clients_model->getDaysPaymentDue($client_id)
            );

            $invoice_id = $this->invoices_model->addInvoice($invoice_data);

            if ($invoice_id > 0) {
                $items = $this->input->post('items');

                $amount = 0;
                foreach ($items as $item)
                {
                    $taxable = (isset($item['taxable']) && $item['taxable'] == 1) ? 1 : 0;

                    $invoice_items = array(
                        'invoice_id' => $invoice_id,
                        'quantity' => $item['quantity'],
                        'amount' => $item['amount'],
                        'work_description' => $item['work_description'],
                        'taxable' => $taxable,
                    );

                    $this->invoices_model->addInvoiceItem($invoice_items);
                }

                redirect('invoices/view/' . $invoice_id);
            } else {
                // clear clientId session
                $data['page_title'] = $this->lang->line('invoice_new_error');
                $this->load->view('invoices/create_fail', $data);
            }
        }
    }

    // --------------------------------------------------------------------

    function newinvoice_first()
    {
        // page for users without javascript enabled
        $data['page_title'] = $this->lang->line('menu_new_invoice');
        $data['clientList'] = $this->clients_model->getAllClients(); // activate the option
        $this->load->view('invoices/newinvoice_first', $data);
    }

    // --------------------------------------------------------------------

    function _construct_invoice_data($id)
    {
        $data = array();

        $invoiceInfo = $this->invoices_model->getSingleInvoice($id);

        if ($invoiceInfo->num_rows() == 0) {
            redirect('invoices/');
        }
        $data['row'] = $invoiceInfo->row();
        $client_id = $data['row']->client_id;
    }

    // --------------------------------------------------------------------

    function view($id)
    {
        $roundFactor = $this->settings_model->getRoundFactor();
        $ccyDec = $this->config->item('currency_decimal');
        $ccyThou = $this->config->item('currency_thousands');

        $this->lang->load('date');
        $this->load->helper('js_calendar');
        $this->load->helper('file');

        $data['message'] = ($this->session->flashdata('message') != '') ? $this->session->flashdata('message') : '';

        $data['extraHeadContent'] = "<link type=\"text/css\" rel=\"stylesheet\" href=\"" . base_url() . "css/calendar.css\" />\n";
        $data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"" . base_url() . "css/invoice.css\" />\n";
        $data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"" . base_url() . "js/emailinvoice.js\"></script>\n";
        $data['extraHeadContent'] .= "<script type=\"text/javascript\">\nvar datePicker1 = \"" . date("Y-m-d") . "\";\nvar datePicker2 = \"" . date("F j, Y") . "\";\n\n</script>";
        $data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"" . base_url() . "js/payinvoice.js\"></script>\n";
        $data['extraHeadContent'] .= js_calendar_script('my_form');
        $data['invoiceDate'] = date("Y-m-d");

        $invoiceInfo = $this->invoices_model->getSingleInvoice($id);

        if ($invoiceInfo->num_rows() == 0) {
            redirect('invoices/');
        }

        $data['row'] = $invoiceInfo->row();
        $client_id = $data['row']->client_id;

        $data['row']->currency_symbol = $this->getCurrencySymbol($client_id,
                $data['row']->currency_symbol);
        $data['row']->currency_type = $this->getCurrencyType($client_id,
                $data['row']->currency_type);
        $data['currency_symbol'] = $data['row']->currency_symbol;
        $data['currency_type'] = $data['row']->currency_type;
        $data['accounting_invoice_exchange_rate'] = $data['row']->accounting_invoice_exchange_rate;

        $data['date_invoice_issued'] = formatted_invoice_date($data['row']->dateIssued);
        $data['date_invoice_due'] = formatted_invoice_date($data['row']->dateIssued,
                $data['row']->days_payment_due);
        $data['days_payment_due'] = $data['row']->days_payment_due;
        if (($data['row']->amount_paid +  + 0.001) >= ($data['row']->total_with_tax)) {
            // paid invoices
            $data['status'] = '<span>' . $this->lang->line('invoice_closed') . '</span>';
        } elseif (mysql_to_unix($data['row']->dateIssued) >= time() - ($data['row']->days_payment_due * 60 * 60 * 24)) {
            // owing less then 30 days
            $data['status'] = '<span>' . $this->lang->line('invoice_open') . '</span>';
        } else {
            // owing more then 30 days
            $due_date = ((int)$data['row']->dateIssued) + ($data['row']->days_payment_due * 60 * 60 * 24);
            $data['status'] = '<span class="error">' . timespan(mysql_to_unix($data['row']->dateIssued) + ($data['row']->days_payment_due * 60 * 60 * 24),
                            now()) . ' ' . $this->lang->line('invoice_overdue') . '</span>';
        }

        $data['items'] = $this->invoices_model->getInvoiceItems($id);

        // begin amount and taxes
        $data['total_no_tax'] = $this->lang->line('invoice_amount') . ': ' . $data['currency_symbol'] . " " . number_format($data['row']->total_notax,
                        2, $ccyDec, $ccyThou) . "<br />\n";

        $data['tax_info'] = $this->_tax_info($data['row']);

        $data['tax1_desc'] = $data['row']->tax1_desc;
        $data['total_tax1'] = number_format($data['row']->total_tax1, 2,
                $ccyDec, $ccyThou);
        $data['tax1_rate'] = $data['row']->tax1_rate;
        $data['tax2_desc'] = $data['row']->tax2_desc;
        $data['total_tax2'] = number_format($data['row']->total_tax2, 2,
                $ccyDec, $ccyThou);
        $data['tax2_rate'] = $data['row']->tax2_rate;
        $data['total_notax_number'] = number_format($data['row']->total_notax,
                2, $ccyDec, $ccyThou);
        $data['total_with_tax_number'] = number_format($data['row']->total_with_tax,
                2, $ccyDec, $ccyThou);

        $data['total_with_tax'] = $this->lang->line('invoice_total') . ': ' . $data['currency_symbol'] . " " . number_format($data['row']->total_with_tax,
                        2, $ccyDec, $ccyThou) . "<br />\n";
        ;
        // end amount and taxes
        // accounting amount
        $acctAmount = $data['row']->total_with_tax * $data['row']->accounting_invoice_exchange_rate;
        $data['total_accounting_amount'] = number_format($acctAmount, 2,
                $ccyDec, $ccyThou);
        // end accounting amount
        // 
        //round to 5er
        $data['row']->amount_paid = round($data['row']->amount_paid / $roundFactor) * $roundFactor;

        if ($data['row']->amount_paid > 0) {
            $data['total_paid'] = $this->lang->line('invoice_amount_paid') . ': ' . $data['currency_symbol'] . " " . number_format($data['row']->amount_paid,
                            2, $ccyDec, $ccyThou) . "<br />\n";
            ;
            $data['total_outstanding'] = $this->lang->line('invoice_amount_outstanding') . ': ' . $data['currency_symbol'] . " " . number_format($data['row']->total_with_tax - $data['row']->amount_paid,
                            2, $ccyDec, $ccyThou);
        } else {
            $data['total_paid'] = '';
            $data['total_outstanding'] = '';
        }

        $data['companyInfo'] = $this->settings_model->getCompanyInfo();
        $data['clientContacts'] = $this->clients_model->getClientContacts($data['row']->client_id);
        $data['invoiceHistory'] = $this->invoices_model->getInvoiceHistory($id);
        $data['paymentHistory'] = $this->invoices_model->getInvoicePaymentHistory($id);
        $data['invoiceOptions'] = TRUE; // create invoice options on sidebar
        $data['company_logo'] = $this->_get_logo();
        $data['page_title'] = 'Invoice Details';


        $this->load->view('invoices/view', $data);
    }

    // --------------------------------------------------------------------
    
    function edit($id)
    {
        $roundFactor = $this->settings_model->getRoundFactor();
        $ccyDec = $this->config->item('currency_decimal');
        $ccyThou = $this->config->item('currency_thousands');
        $this->load->library('validation');
        $this->load->helper('js_calendar');

        // grab invoice info
        $data['row'] = $this->invoices_model->getSingleInvoice($id)->row();
        $client_id = $data['row']->client_id;
        $data['invoice_number'] = $data['row']->invoice_number;
        $data['last_number_suggestion'] = '';
        $data['action'] = 'edit';
        $data['clientId'] = $data['row']->client_id;

        $data['row']->currency_symbol = $this->getCurrencySymbol($client_id,
                $data['row']->currency_symbol);
        $data['row']->currency_type = $this->getCurrencyType($client_id,
                $data['row']->currency_type);

        $data['currency_symbol'] = $data['row']->currency_symbol;
        $data['currency_type'] = $data['row']->currency_type;
        $data['accounting_invoice_exchange_rate'] = $data['row']->accounting_invoice_exchange_rate;
        // some hidden form data
        $data['form_hidden'] = array(
            'id' => $data['row']->id,
            'tax1_rate' => $data['row']->tax1_rate,
            'tax1_description' => $data['row']->tax1_desc,
            'tax2_rate' => $data['row']->tax2_rate,
            'tax2_description' => $data['row']->tax2_desc,
        );

        $taxable = ($this->clients_model->get_client_info($data['row']->client_id,
                        'tax_status')->tax_status == 1) ? 'true' : 'false';

        $data['extraHeadContent'] = "<link type=\"text/css\" rel=\"stylesheet\" href=\"" . base_url() . "css/calendar.css\" />\n";
        $data['extraHeadContent'] .= "<script type=\"text/javascript\">\nvar taxable = " . $taxable . ";\nvar tax1_rate = " . $data['row']->tax1_rate . ";\nvar tax2_rate = " . $data['row']->tax2_rate . ";\nvar datePicker1 = \"" . date("Y-m-d",
                        mysql_to_unix($data['row']->dateIssued)) . "\";\nvar datePicker2 = \"" . date("F j, Y",
                        mysql_to_unix($data['row']->dateIssued)) . "\";\n\n</script>";
        $data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"" . base_url() . "css/invoice.css\" />\n";
        $data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"" . base_url() . "js/createinvoice.js\"></script>\n";
        $data['extraHeadContent'] .= js_calendar_script('my_form');
        $data['clientListEdit'] = $this->clients_model->getAllClients();

        $this->_validation(true); // Load the validation rules and fields

        $data['page_title'] = $this->lang->line('menu_edit_invoice');
        $data['button_label'] = 'invoice_save_edited_invoice';

        if ($this->validation->run() == FALSE) {
            $data['items'] = $this->invoices_model->getInvoiceItems($id);
            $data['error'] = "";
            foreach($this->validation->_error_messages as $msg){
                $err = '<p class="error">'.$msg.'</p>';
                $data['error'] = $data['error'] . $err;
            }
            
            // begin amount and taxes
            $data['total_no_tax'] = $this->lang->line('invoice_amount') . ': ' . $this->clients_model->getCurrencySymbol($client_id) . " " . number_format($data['row']->total_notax,
                            2, $ccyDec, $ccyThou) . "<br />\n";
            $data['tax_info'] = $this->_tax_info($data['row']);

            $data['tax1_desc'] = $data['row']->tax1_desc;
            $data['total_tax1'] = number_format($data['row']->total_tax1, 2,
                    $ccyDec, $ccyThou);
            $data['tax1_rate'] = $data['row']->tax1_rate;
            $data['tax2_desc'] = $data['row']->tax2_desc;
            $data['total_tax2'] = number_format($data['row']->total_tax2, 2,
                    $ccyDec, $ccyThou);
            $data['tax2_rate'] = $data['row']->tax2_rate;
            $data['total_notax_number'] = number_format($data['row']->total_notax,
                    2, $ccyDec, $ccyThou);
            $data['total_with_tax_number'] = number_format($data['row']->total_with_tax,
                    2, $ccyDec, $ccyThou);

            $data['total_with_tax'] = $this->lang->line('invoice_total') . ': ' . $this->clients_model->getCurrencySymbol($client_id) . " " . number_format($data['row']->total_with_tax,
                            2, $ccyDec, $ccyThou);
            // end amount and taxes

            $this->load->view('invoices/edit', $data);
        } else {
            if ($this->invoices_model->uniqueInvoiceNumberEdit($this->input->post('invoice_number'),
                            $this->input->post('id'))) {
                $accCurrExchRate = $data['row']->accounting_invoice_exchange_rate;
                if (    $accCurrExchRate == null || $accCurrExchRate == 0 || 
                        /*invoice date has changed*/
                        $data['row']->dateIssued != $this->input->post('dateIssued')) {
                    $accCurrExchRate = $this->calculateAccountingCurrencyExchangeRate($this->settings_model->get_setting('currency_type_accounting'),
                            $data['currency_type'],
                            $this->input->post('dateIssued'));
                }

                $invoice_data = array(
                    'client_id' => $this->input->post('client_id'),
                    'invoice_number' => $this->input->post('invoice_number'),
                    'dateIssued' => $this->input->post('dateIssued'),
                    'tax1_desc' => $this->input->post('tax1_description'),
                    'tax1_rate' => $this->input->post('tax1_rate'),
                    'tax2_desc' => $this->input->post('tax2_description'),
                    'tax2_rate' => $this->input->post('tax2_rate'),
                    'invoice_note' => $this->input->post('invoice_note'),
                    'currency_type' => $data['currency_type'],
                    'currency_symbol' => $data['currency_symbol'],
                    'accounting_invoice_exchange_rate' => $accCurrExchRate
                );

                $invoice_id = $this->invoices_model->updateInvoice($this->input->post('id'),
                        $invoice_data);

                if (!$invoice_id) {
                    show_error('That invoice could not be updated.');
                }

                $this->invoices_model->delete_invoice_items($invoice_id); // remove old items
                // add them back
                $items = $this->input->post('items');
                foreach ($items as $item)
                {
                    $taxable = (isset($item['taxable']) && $item['taxable'] == 1) ? 1 : 0;

                    $invoice_items = array(
                        'invoice_id' => $invoice_id,
                        'quantity' => $item['quantity'],
                        'amount' => $item['amount'],
                        'work_description' => $item['work_description'],
                        'taxable' => $taxable,
                    );

                    $this->invoices_model->addInvoiceItem($invoice_items);
                }

                // give a session telling them it worked
                $this->session->set_flashdata('message',
                        $this->lang->line('invoice_invoice_edit_success'));
                redirect('invoices/view/' . $invoice_id);
            } else {
                $data['invoice_number_error'] = TRUE;
                $data['items'] = $this->invoices_model->getInvoiceItems($id);

                // begin amount and taxes
                $data['total_no_tax'] = $this->lang->line('invoice_amount') . ': ' . $this->clients_model->getCurrencySymbol($client_id) . " " . number_format($data['row']->total_notax,
                                2, $ccyDec, $ccyThou) . "<br />\n";

                $data['tax_info'] = $this->_tax_info($data['row']);
                $data['tax1_desc'] = $data['row']->tax1_desc;
                $data['total_tax1'] = number_format($data['row']->total_tax1, 2,
                        $ccyDec, $ccyThou);
                $data['tax1_rate'] = $data['row']->tax1_rate;
                $data['tax2_desc'] = $data['row']->tax2_desc;
                $data['total_tax2'] = number_format($data['row']->total_tax2, 2,
                        $ccyDec, $ccyThou);
                $data['tax2_rate'] = $data['row']->tax2_rate;
                $data['total_notax_number'] = number_format($data['row']->total_notax,
                        2, $ccyDec, $ccyThou);
                $data['total_with_tax_number'] = number_format($data['row']->total_with_tax,
                        2, $ccyDec, $ccyThou);

                $data['total_with_tax'] = $this->lang->line('invoice_total') . ': ' . $this->clients_model->getCurrencySymbol($client_id) . " " . number_format($data['row']->total_with_tax,
                                2, $ccyDec, $ccyThou);
                // end amount and taxes

                $this->load->view('invoices/edit', $data);
            }
        }
    }

    // --------------------------------------------------------------------

    function duplicate($id)
    {
        $roundFactor = $this->settings_model->getRoundFactor();
        $ccyDec = $this->config->item('currency_decimal');
        $ccyThou = $this->config->item('currency_thousands');
        $this->load->library('validation');
        $this->load->helper('js_calendar');

        // grab invoice info
        $data['row'] = $this->invoices_model->getSingleInvoice($id)->row();
        
        //we have to take the latest vat rates in case something has changed from year to year
        $data['row']->tax1_desc = $this->settings_model->get_setting('tax1_desc');
        $data['row']->tax2_desc = $this->settings_model->get_setting('tax2_desc');
        $data['row']->tax1_rate = $this->settings_model->get_setting('tax1_rate');
        $data['row']->tax2_rate = $this->settings_model->get_setting('tax2_rate');

        $client_id = $data['row']->client_id;
        $data['action'] = 'duplicate';
        // some hidden form data
        $data['form_hidden'] = array(
            'tax1_rate' => $data['row']->tax1_rate,
            'tax1_description' => $data['row']->tax1_desc,
            'tax2_rate' => $data['row']->tax2_rate,
            'tax2_description' => $data['row']->tax2_desc,
        );

        $taxable = ($this->clients_model->get_client_info($data['row']->client_id,
                        'tax_status')->tax_status == 1) ? 'true' : 'false';
        $data['row']->currency_symbol = $this->getCurrencySymbol($client_id,
                $data['row']->currency_symbol);
        $data['row']->currency_type = $this->getCurrencyType($client_id,
                $data['row']->currency_type);
        $data['currency_symbol'] = $data['row']->currency_symbol;
        $data['currency_type'] = $data['row']->currency_type;
        $data['accounting_invoice_exchange_rate'] = 0;
        $data['days_payment_due'] = $this->clients_model->getDaysPaymentDue($client_id);

        $data['extraHeadContent'] = "<link type=\"text/css\" rel=\"stylesheet\" href=\"" . base_url() . "css/calendar.css\" />\n";
        $data['extraHeadContent'] .= "<script type=\"text/javascript\">\nvar taxable = " . $taxable . ";\nvar tax1_rate = " . $data['row']->tax1_rate . ";\nvar tax2_rate = " . $data['row']->tax2_rate . ";\nvar datePicker1 = \"" . date("Y-m-d",
                        mysql_to_unix($data['row']->dateIssued)) . "\";\nvar datePicker2 = \"" . date("F j, Y",
                        mysql_to_unix($data['row']->dateIssued)) . "\";\n\n</script>";
        $data['extraHeadContent'] .= "<link type=\"text/css\" rel=\"stylesheet\" href=\"" . base_url() . "css/invoice.css\" />\n";
        $data['extraHeadContent'] .= "<script type=\"text/javascript\" src=\"" . base_url() . "js/createinvoice.js\"></script>\n";
        $data['extraHeadContent'] .= js_calendar_script('my_form');
        $data['clientListEdit'] = $this->clients_model->getAllClients();

        $this->_validation(true); // Load the validation rules and fields

        $last_invoice_number = $this->invoices_model->lastInvoiceNumber($id);
        ($last_invoice_number != '') ? $data['lastInvoiceNumber'] = $last_invoice_number : $data['lastInvoiceNumber'] = '';
        $data['invoice_number'] = (is_numeric($last_invoice_number)) ? $last_invoice_number + 1 : '';
        $data['last_number_suggestion'] = '(' . $this->lang->line('invoice_last_used') . ' ' . $last_invoice_number . ')';

        $data['page_title'] = $this->lang->line('menu_duplicate_invoice');
        $data['button_label'] = 'actions_create_invoice';

        if ($this->validation->run() == FALSE) {
            $data['items'] = $this->invoices_model->getInvoiceItems($id);
            $data['error'] = "";
            foreach($this->validation->_error_messages as $msg){
                $err = '<p class="error">'.$msg.'</p>';
                $data['error'] = $data['error'] . $err;
            }
            // begin amount and taxes
            $data['total_no_tax'] = $this->lang->line('invoice_amount') . ': ' . $this->clients_model->getCurrencySymbol($client_id) . " " . number_format($data['row']->total_notax,
                            2, $ccyDec, $ccyThou) . "<br />\n";
            $data['tax_info'] = $this->_tax_info($data['row']);
            $data['tax1_desc'] = $data['row']->tax1_desc;
            $data['total_tax1'] = number_format($data['row']->total_tax1, 2, $ccyDec, $ccyThou);
            $data['tax1_rate'] = $data['row']->tax1_rate;
            $data['tax2_desc'] = $data['row']->tax2_desc;
            $data['total_tax2'] = number_format($data['row']->total_tax2, 2, $ccyDec, $ccyThou);
            $data['tax2_rate'] = $data['row']->tax2_rate;
            $data['total_notax_number'] = number_format($data['row']->total_notax,
                    2, $ccyDec, $ccyThou);
            $data['total_with_tax_number'] = number_format($data['row']->total_with_tax,
                    2, $ccyDec, $ccyThou);

            $data['total_with_tax'] = $this->lang->line('invoice_total') . ': ' . $this->clients_model->getCurrencySymbol($client_id) . " " . number_format($data['row']->total_with_tax,
                            2, $ccyDec, $ccyThou);
            // end amount and taxes

            $this->load->view('invoices/edit', $data);
        } else {
            if ($this->invoices_model->uniqueInvoiceNumber($this->input->post('invoice_number'),
                            $this->input->post('id'))) {
                $accCurrExchRate = $this->calculateAccountingCurrencyExchangeRate($this->settings_model->get_setting('currency_type_accounting'),
                        $data['currency_type'], $this->input->post('dateIssued'));

                $invoice_data = array(
                    'client_id' => $this->input->post('client_id'),
                    'invoice_number' => $this->input->post('invoice_number'),
                    'dateIssued' => $this->input->post('dateIssued'),
                    'tax1_desc' => $this->input->post('tax1_description'),
                    'tax1_rate' => $this->input->post('tax1_rate'),
                    'tax2_desc' => $this->input->post('tax2_description'),
                    'tax2_rate' => $this->input->post('tax2_rate'),
                    'invoice_note' => $this->input->post('invoice_note'),
                    'accounting_invoice_exchange_rate' => $accCurrExchRate,
                    'days_payment_due'  => $this->clients_model->getDaysPaymentDue($client_id),
                    'currency_symbol' => $data['row']->currency_symbol,
                    'currency_type' => $data['row']->currency_type
                );

                $invoice_id = $this->invoices_model->addInvoice($invoice_data);

                if ($invoice_id > 0) {
                    $items = $this->input->post('items');

                    $amount = 0;
                    foreach ($items as $item)
                    {
                        $taxable = (isset($item['taxable']) && $item['taxable'] == 1) ? 1 : 0;

                        $invoice_items = array(
                            'invoice_id' => htmlspecialchars($invoice_id),
                            'quantity' => htmlspecialchars($item['quantity']),
                            'amount' => htmlspecialchars($item['amount']),
                            'work_description' => htmlspecialchars($item['work_description']),
                            'taxable' => htmlspecialchars($taxable),
                        );

                        $this->invoices_model->addInvoiceItem($invoice_items);
                    }
                }

                // give a session telling them it worked
                $this->session->set_flashdata('message',
                        $this->lang->line('invoice_invoice_edit_success'));
                redirect('invoices/view/' . $invoice_id);
            } else {
                $data['invoice_number_error'] = TRUE;
                $data['items'] = $this->invoices_model->getInvoiceItems($id);

                // begin amount and taxes
                $data['total_no_tax'] = $this->lang->line('invoice_amount') . ': ' . $this->clients_model->getCurrencySymbol($client_id) . " " . number_format($data['row']->total_notax,
                                2, $ccyDec, $ccyThou) . "<br />\n";

                $data['tax_info'] = $this->_tax_info($data['row']);
                $data['tax1_desc'] = $data['row']->tax1_desc;
                $data['total_tax1'] = number_format($data['row']->total_tax1, 2,
                        $ccyDec, $ccyThou);
                $data['tax1_rate'] = $data['row']->tax1_rate;
                $data['tax2_desc'] = $data['row']->tax2_desc;
                $data['total_tax2'] = number_format($data['row']->total_tax2, 2,
                        $ccyDec, $ccyThou);
                $data['tax2_rate'] = $data['row']->tax2_rate;
                $data['total_notax_number'] = number_format($data['row']->total_notax,
                        2, $ccyDec, $ccyThou);
                $data['total_with_tax_number'] = number_format($data['row']->total_with_tax,
                        2, $ccyDec, $ccyThou);

                $data['total_with_tax'] = $this->lang->line('invoice_total') . ': ' . $this->clients_model->getCurrencySymbol($client_id) . " " . number_format($data['row']->total_with_tax,
                                2, $ccyDec, $ccyThou);
                // end amount and taxes

                $this->load->view('invoices/edit', $data);
            }
        }
    }

    // --------------------------------------------------------------------

    function notes($id)
    {
        $this->load->model('invoice_histories_model');

        $this->invoice_histories_model->insert_note($id,
                $this->input->post('private_note'));

        $this->session->set_flashdata('message',
                $this->lang->line('invoice_comment_success'));
        redirect('invoices/view/' . $id);
    }

    // --------------------------------------------------------------------

    function email($id)
    {
        $this->load->library('email');
        $this->load->model('clientcontacts_model');
        $this->load->model('invoice_histories_model');
        
	    $invoice_number = $this->pdf($id, FALSE);

        $recipients = $this->input->post('recipients');

        if ($recipients == '') {
            show_error($this->lang->line('invoice_email_no_recipient'));
        } // a rather rude reminder to include a recipient in case js is disabled

        $recipient_emails = array();

        foreach ($recipients as $recipient)
        {
            ($recipient == 1) ? $recipient_emails[] .= $from_email : $recipient_emails[] .= $this->clientcontacts_model->getContactInfo($recipient)->email;
        }

        $recipient_names = array();

        foreach ($recipients as $recipient)
        {
            if ($recipient == 1) {
                $recipient_names[] .= $this->lang->line('invoice_you');
            } else {
                $recipient_names[] .= $this->clientcontacts_model->getContactInfo($recipient)->first_name . ' ' . $this->clientcontacts_model->getContactInfo($recipient)->last_name;
            }
        }

        // send emails

        if (count($recipient_emails) === 1) {
            $this->email->to($recipient_emails[0]);
        } else {
            $this->email->to($recipient_emails[0]);
            $this->email->cc(array_slice($recipient_emails, 1));
        }
        $companyInfo = $this->settings_model->getCompanyInfo();
        // should we blind copy the primary contact? 
        if ($this->input->post('primary_contact') == 'y') {
            $this->email->bcc($companyInfo->primary_contact_email);
        }

        $email_body = $this->input->post('email_body');

        $this->email->from($companyInfo->primary_contact_email,
                $companyInfo->primary_contact);
        $this->email->subject($this->lang->line('invoice_invoice') . " $invoice_number : " . $companyInfo->company_name);
        $this->email->message(stripslashes($email_body));
        
        $invoice_localized = url_title(strtolower($this->lang->line('invoice_invoice')));
        $this->email->attach("./invoices_temp/" . $invoice_localized . "_" . "$invoice_number.pdf");

        // for the demo, I don't want actual emails sent out, so this provides an easy
        // override. 
        if ($this->settings_model->get_setting('demo_flag') == 'n') {
            $this->email->send(FALSE);
        }

        $this->_delete_stored_files(); // remove saved invoice(s)
        // save this in the invoice_history
        $this->invoice_histories_model->insert_history_note($id, $email_body,
                $recipient_names);

        // next line for debugging
        //show_error($this->email->print_debugger());

        if ($this->input->post('isAjax') == 'true') {
            // for future ajax functionality, right now this is permanently set to false
        } else {
            if ($this->settings_model->get_setting('demo_flag') == 'y') {
                $this->session->set_flashdata('message',
                        $this->lang->line('invoice_email_demo'));
            } else {
                $this->session->set_flashdata('message',
                        $this->lang->line('invoice_email_success'));
            }

            redirect('invoices/view/' . $id); // send them back to the invoice view
        }
    }

    // --------------------------------------------------------------------

    function pdf($id, $output = TRUE)
    {
        $roundFactor = $this->settings_model->getRoundFactor();
        $ccyDec = $this->config->item('currency_decimal');
        $ccyThou = $this->config->item('currency_thousands');
        $this->lang->load('date');
        $this->load->helper('to_pdf');
        $this->load->helper('file');

        $data['page_title'] = $this->lang->line('menu_invoices');

        $invoiceInfo = $this->invoices_model->getSingleInvoice($id);

        if ($invoiceInfo->num_rows() == 0) {
            redirect('invoices/');
        }

        $data['row'] = $invoiceInfo->row();
        $client_id = $data['row']->client_id;

        $data['client_note'] = $this->clients_model->get_client_info($client_id)->client_notes;

        $data['row']->currency_symbol = $this->getCurrencySymbol($client_id,
                $data['row']->currency_symbol);
        $data['row']->currency_type = $this->getCurrencyType($client_id,
                $data['row']->currency_type);

        $data['currency_symbol'] = $data['row']->currency_symbol;
        $data['currency_type'] = $data['row']->currency_type;
        $data['accounting_invoice_exchange_rate'] = $data['row']->accounting_invoice_exchange_rate;

        $data['date_invoice_issued'] = formatted_invoice_date($data['row']->dateIssued);
        $data['date_invoice_due'] = formatted_invoice_date($data['row']->dateIssued,
                $data['row']->days_payment_due);
        $data['days_payment_due'] = $data['row']->days_payment_due;

        $data['companyInfo'] = $this->settings_model->getCompanyInfo();
        $data['company_logo'] = $this->_get_logo('_pdf', 'pdf');

        $data['items'] = $this->invoices_model->getInvoiceItems($id);

        $data['total_no_tax'] = $this->lang->line('invoice_amount') . ': ' . $data['currency_symbol'] . " " . number_format($data['row']->total_notax,
                        2, $ccyDec, $ccyThou) . "<br />\n";

        // taxes
        $data['tax_info'] = $this->_tax_info($data['row']);
        $data['tax1_desc'] = $data['row']->tax1_desc;
        $data['total_tax1'] = number_format($data['row']->total_tax1, 2,
                $ccyDec, $ccyThou);
        $data['tax1_rate'] = $data['row']->tax1_rate;
        $data['tax2_desc'] = $data['row']->tax2_desc;
        $data['total_tax2'] = number_format($data['row']->total_tax2, 2,
                $ccyDec, $ccyThou);
        $data['tax2_rate'] = $data['row']->tax2_rate;
        $data['total_notax_number'] = number_format($data['row']->total_notax,
                2, $ccyDec, $ccyThou);
        $data['total_with_tax_number'] = number_format($data['row']->total_with_tax,
                2, $ccyDec, $ccyThou);

        $data['total_with_tax'] = $this->lang->line('invoice_total') . ': ' . $data['currency_symbol'] . " " . number_format($data['row']->total_with_tax,
                        2, $ccyDec, $ccyThou) . "<br />\n";

        //round to 5er
        $data['row']->amount_paid = round($data['row']->amount_paid / $roundFactor) * $roundFactor;

        if ($data['row']->amount_paid > 0) {
            $data['total_paid'] = $this->lang->line('invoice_amount_paid') . ': ' . $data['currency_symbol'] . " " . number_format($data['row']->amount_paid,
                            2, $ccyDec, $ccyThou) . "<br />\n";
            $data['total_outstanding'] = $this->lang->line('invoice_amount_outstanding') . ': ' . $data['currency_symbol'] . " " . number_format($data['row']->total_with_tax - $data['row']->amount_paid,
                            2, $ccyDec, $ccyThou);
        } else {
            $data['total_paid'] = '';
            $data['total_outstanding'] = '';
        }
        // accounting amount
        $acctAmount = $data['row']->total_with_tax * $data['row']->accounting_invoice_exchange_rate;
        $data['total_accounting_amount'] = number_format($acctAmount, 2,
                $ccyDec, $ccyThou);
        // end accounting amount

        $html = $this->load->view('invoices/pdf', $data, TRUE);
        $invoice_localized = url_title(strtolower($this->lang->line('invoice_invoice')));
        $filename = $invoice_localized . '_' . $data['row']->invoice_number . '.pdf';
        if (pdf_create($html, $filename, $output)) {
            show_error($this->lang->line('error_problem_saving'));
        }

        // if this is getting emailed, don't delete just yet
        // instead just give back the invoice number
        if ($output) {
            $this->_delete_stored_files();
        } else {
            return $data['row']->invoice_number;
        }
        return "";
    }

    // --------------------------------------------------------------------
    function updateAccountingExchangeRates()
    {
        $fromYear = $this->uri->segment(3);
        $toYear = $this->uri->segment(4);
        if ($fromYear == '' || $toYear == '') {
            show_error('from and to year must be passed');
        }
        $this->_updateAccountingExchangeRates($year);
    }

    function getCurrencyType($clientId, $currencyType)
    {
        if ($currencyType == NULL || empty($currencyType)) {
            return $this->clients_model->getCurrencyType($clientId);
        }
        return $currencyType;
    }

    function getCurrencySymbol($clientId, $currencySymbol)
    {
        if ($currencySymbol == NULL || empty($currencySymbol)) {
            return $this->clients_model->getCurrencySymbol($clientId);
        }
        return $currencySymbol;
    }

    /**
     * Batch PDF
     *
     * This function is in here for the convenience of people who need it, but is not accessible currently
     * via the "front end".  It is very memory intensive, and unlikely that most servers could handle it
     * even with resetting memory and timeout options... thus, its in here for people who need it, and for
     * me, but not currently publicly accessible.
     */
    function batch_pdf()
    {
        $start_id = $this->uri->segment(3);
        $end_id = $this->uri->segment(4);

        if ($start_id == '' || $end_id == '') {
            show_error('Start and end id\'s must be passed');
        }

        $this->db->select('id');
        $this->db->where('id >= ' . $start_id);
        $this->db->where('id <= ' . $end_id);
        $invoice_set = $this->db->get('invoices');

        foreach ($invoice_set->result() as $invoice)
        {
            echo "$invoice->id<br />";
            $this->pdf($invoice->id, FALSE);
        }
    }

    // --------------------------------------------------------------------

    function payment()
    {
        $id = (int) $this->input->post('id');
        $date_paid = $this->input->post('date_paid');
        $amount = $this->input->post('amount');
        $payment_note = substr($this->input->post('payment_note'), 0, 255);

        if (!preg_match("/(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])/",
                        $date_paid) || !is_numeric($amount)) {
            show_error($this->lang->line('error_date_fill'));
        } else {
            $data = array(
                'invoice_id' => $id,
                'amount_paid' => $amount,
                'date_paid' => $date_paid,
                'payment_note' => $payment_note
            );

            $this->invoices_model->payment($data);

            $this->session->set_flashdata('message',
                    $this->lang->line('invoice_payment_success'));

            redirect('invoices/view/' . $id);
        }
    }

    // --------------------------------------------------------------------

    function delete($id)
    {
        $this->session->set_flashdata('deleteInvoice', $id);
        $data['deleteInvoice'] = $this->invoices_model->getSingleInvoice($id)->row()->invoice_number;
        $data['page_title'] = $this->lang->line('menu_delete_invoice');
        $this->load->view('invoices/delete', $data);
    }

    // --------------------------------------------------------------------

    function delete_confirmed()
    {
        $invoice_id = $this->session->flashdata('deleteInvoice');
        $this->invoices_model->delete_invoice($invoice_id);
        $this->session->set_flashdata('message',
                $this->lang->line('invoice_invoice_delete_success'));
        redirect('invoices/');
    }

    // --------------------------------------------------------------------

    function retrieveInvoices()
    {
        $roundFactor = $this->settings_model->getRoundFactor();
        $ccyDec = $this->config->item('currency_decimal');
        $ccyThou = $this->config->item('currency_thousands');
        $query = $this->invoices_model->getInvoicesAJAX($this->input->post('status'),
                $this->input->post('client_id'));

        $last_retrieved_month = 0; // no month

        $invoiceResults = '{"invoices" :[';

        if ($query->num_rows() == 0) {
            $invoiceResults .= '{ "invoice_number" : "No results available"}, ';
        } else {
            foreach ($query->result() as $row)
            {
                $invoice_date = mysql_to_unix($row->dateIssued);
                if ($last_retrieved_month != date('F', $invoice_date) && $last_retrieved_month !== 0) {
                    $invoiceResults .= '{ "invoiceId" : "monthbreak' . date('F',
                                    $invoice_date) . '" }, ';
                }

                $invoiceResults .= '{ "invoiceId" : "' . $row->id . '", "invoice_number" : "' . $row->invoice_number . '", "dateIssued" : "';
                $shortDescription = $this->invoices_model->build_short_descriptions_with_id($row->id);
                $shortDescription = $shortDescription[$row->id];
                //make sure we don't have double quotes in the short description as this would mess with javascript
                //only take the first entry
                //we do not want to convert \n to <br/> hence we convert it beforehand
                $shortDescription = str_replace(array("\n", "\r", "\t"),
                        array(' ', '', ' '), $shortDescription);
                $shortDescription = convertToHtmlSpecialChars($shortDescription);

                //for short description we change \n to space
                // localized month
                $invoiceResults .= formatted_invoice_date($row->dateIssued);
                if ($row->currency_symbol == NULL || empty($row->currency_symbol)) {
                    $row->currency_symbol = $this->clients_model->getCurrencySymbol($row->client_id);
                }
                if ($row->currency_type == NULL || empty($row->currency_type)) {
                    $row->currency_type = $this->clients_model->getCurrencyType($row->client_id);
                }

                //round tax to next 5er
                $totalAmount = round($row->subtotal / $roundFactor) * $roundFactor;
                $totalAmount = number_format($totalAmount, 2, $ccyDec, $ccyThou);

                $invoiceResults .= '", "clientName" : "' . $row->name . '", "short_description" : "' . $shortDescription . '", "currency_symbol" : "' . $row->currency_symbol . '", "amount" : "' . $totalAmount . '", "status" : "';
                //make both numeric if one is a string comparison does not work
                $row->amount_paid = round($row->amount_paid / $roundFactor) * $roundFactor;
                $row->subtotal = round($row->subtotal / $roundFactor) * $roundFactor;
                if (($row->amount_paid+ 0.001) >= $row->subtotal) {
                    // paid invoices
                    $invoiceResults .= $this->lang->line('invoice_closed');
                } elseif (mysql_to_unix($row->dateIssued) >= strtotime('-' . $row->days_payment_due . ' days')) {
                    // owing less then the overdue days amount
                    $invoiceResults .= $this->lang->line('invoice_open');
                } else {
                    // owing more then the overdue days amount
                    $due_date = $invoice_date + ($row->days_payment_due * 60 * 60 * 24);
                    $invoiceResults .= timespan($due_date, now()) . ' ' . $this->lang->line('invoice_overdue');
                }

                $invoiceResults .= '" }, ';
                $last_retrieved_month = date('F', $invoice_date);
            }
            $invoiceResults = rtrim($invoiceResults, ', ') . ']}';
            echo $invoiceResults;
        }
    }

    // --------------------------------------------------------------------

    function dateIssued($str)
    {
        if (preg_match("/(19|20)\d\d[-](0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])/",
                        $str)) {
            return TRUE;
        } else {
            $this->validation->set_message('dateIssued',
                    $this->lang->line('error_date_format'));
            return FALSE;
        }
    }

    // --------------------------------------------------------------------

    function _delete_stored_files()
    {
        if ($this->settings_model->get_setting('save_invoices') == "n") {
            delete_files("./invoices_temp/");
        }
    }

    // --------------------------------------------------------------------

    function _get_logo($target = '', $context = 'web')
    {
        $this->load->helper('logo');
        $this->load->helper('path');

        return get_logo($this->settings_model->get_setting('logo' . $target),
                $context);
    }

    // --------------------------------------------------------------------

    function _tax_info($data)
    {
        $ccyDec = $this->config->item('currency_decimal');
        $ccyThou = $this->config->item('currency_thousands');
        $tax_info = '';
        $data->tax1_desc = $this->settings_model->get_setting('tax1_desc');
        $data->tax2_desc = $this->settings_model->get_setting('tax2_desc');

        if ($data->total_tax1 != 0) {
            $tax_info .= $data->tax1_desc . " (" . $data->tax1_rate . "%): " . $this->clients_model->getCurrencySymbol($data->client_id) . " " . number_format($data->total_tax1,
                            2, $ccyDec, $ccyThou) . "<br />\n";
        }

        if ($data->total_tax2 != 0) {
            $tax_info .= $data->tax2_desc . " (" . $data->tax2_rate . "%): " . $this->clients_model->getCurrencySymbol($data->client_id) . " " . number_format($data->total_tax2,
                            2, $ccyDec, $ccyThou) . "<br />\n";
        }

        return $tax_info;
    }

    function _validation($edit = 0) {
        $rules['items'] = 'callback_items_check';
        $rules['client_id'] = 'required|numeric';
        if($edit){
            $rules['invoice_number'] = 'trim|required|htmlspecialchars|max_length[12]|alpha_dash';
        }else{
            $rules['invoice_number'] = 'trim|required|htmlspecialchars|max_length[12]|alpha_dash|callback_uniqueInvoice';
        }
        $rules['dateIssued'] = 'trim|htmlspecialchars|callback_dateIssued';
        $rules['invoice_note'] = 'trim|htmlspecialchars|max_length[2000]';
        $rules['tax1_description'] = 'trim|htmlspecialchars|max_length[50]';
        $rules['tax1_rate'] = 'trim|htmlspecialchars';
        $rules['tax2_description'] = 'trim|htmlspecialchars|max_length[50]';
        $rules['tax2_rate'] = 'trim|htmlspecialchars';
        $this->validation->set_rules($rules);

        $fields['client_id'] = $this->lang->line('invoice_client_id');
        $fields['invoice_number'] = $this->lang->line('invoice_number');
        $fields['dateIssued'] = $this->lang->line('invoice_date_issued');
        $fields['invoice_note'] = $this->lang->line('invoice_note');
        $fields['tax1_description'] = $this->settings_model->get_setting('tax1_desc');
        $fields['tax1_rate'] = $this->settings_model->get_setting('tax1_rate');
        $fields['tax2_description'] = $this->settings_model->get_setting('tax1_desc');
        $fields['tax2_rate'] = $this->settings_model->get_setting('tax2_rate');
        $fields['items'] = $this->lang->line('items');
        $this->validation->set_fields($fields);

        $this->validation->set_error_delimiters('<span class="error">',
                '</span>');
    }

    // --------------------------------------------------------------------
    public function items_check($invoice)
    {
        /*foreach($invoice as $invoice_item){
            if (!($invoice_item['amount'] > 0.0)){
                $this->validation->set_message('items[0][amount]', 'Amount cannot be Zero');
                return FALSE;
            }
        }*/
        return TRUE;
    }

    function uniqueInvoice()
    {
        $this->validation->set_message('uniqueInvoice',
                $this->lang->line('invoice_not_unique'));

        return $this->invoices_model->uniqueInvoiceNumber($this->input->post('invoice_number'));
    }

    function updateAllInvoicesWithCurrency()
    {
        //try to to find all invoices of current or last year when within January
        //$year = date('Y', strtotime('-30 days', strtotime(date('Y-m-d'))));
        $this->_updateAccountingExchangeRates("1969", date('Y'));
        $data['output'] = $this->lang->line("utilities_invoices_update_message");
        $data['page_title'] = $this->lang->line('utilities_invoices_update');
        $this->load->view('utilities/invoices_update', $data);
    }

    function _updateAccountingExchangeRates($fromYear, $toYear)
    {
        $ccyDec = $this->config->item('currency_decimal');
        $ccyThou = $this->config->item('currency_thousands');
        $endDate = $toYear . '-12-31';
        $today = date("Y-m-d");
        if ($today <= $endDate) {
            $endDate = $today;
        }
        $accountingCurrency = $this->settings_model->get_setting('currency_type_accounting');
        $this->db->select('id, client_id, dateIssued, currency_symbol, currency_type, accounting_invoice_exchange_rate');
        $where = 'accounting_invoice_exchange_rate = 0 AND dateIssued >= \'' . $fromYear . '-01-01\' AND dateIssued <= \'' . $endDate . '\'';
        $this->db->where($where, NULL, FALSE);

        $invoice_set = $this->db->get('invoices');

        foreach ($invoice_set->result() as $invoice)
        {
            $invoice->currency_symbol = $this->getCurrencySymbol($invoice->client_id,
                    $invoice->currency_symbol);
            $invoice->currency_type = $this->getCurrencyType($invoice->client_id,
                    $invoice->currency_type);

            if ($invoice->accounting_invoice_exchange_rate == 0) {
                $rate = $this->calculateAccountingCurrencyExchangeRate($accountingCurrency,
                        $invoice->currency_type, $invoice->dateIssued);
                if ($rate > 0) {
                    $invoice_data = array(
                        'accounting_invoice_exchange_rate' => $rate,
                        'currency_symbol' => $invoice->currency_symbol,
                        'currency_type' => $invoice->currency_type,
                    );
                    $invoice_id = $this->invoices_model->updateInvoice($invoice->id,
                            $invoice_data);
                }
            }
        }
    }

}

?>