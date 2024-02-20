<?php

class invoices_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        //$this->obj = & get_instance();
    }

    // --------------------------------------------------------------------

    function addInvoice($invoice_data)
    {
        if ($this->db->insert('invoices', $invoice_data)) {
            return $this->db->insert_id();
        } else {
            return FALSE;
        }
    }

    function addInvoiceItem($invoice_items)
    {
        if ($this->db->insert('invoice_items', $invoice_items)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // --------------------------------------------------------------------

    function updateInvoice($invoice_id, $invoice_data)
    {
        $this->db->where('id', $invoice_id);

        if ($this->db->update('invoices', $invoice_data)) {
            return $invoice_id;
        } else {
            return FALSE;
        }
    }

    // --------------------------------------------------------------------

    function payment($invoice_data)
    {
        if ($this->db->insert('invoice_payments', $invoice_data)) {
            return $invoice_data['invoice_id'];
        } else {
            return FALSE;
        }
    }

    // --------------------------------------------------------------------

    function delete_invoice($invoice_id)
    {
        $this->db->where('invoice_id', $invoice_id);
        $this->db->delete('invoice_payments'); // remove invoice payments

        $this->db->where('invoice_id', $invoice_id);
        $this->db->delete('invoice_histories'); // remove invoice_histories info

        $this->db->where('id', $invoice_id);
        $this->db->delete('invoices'); // remove invoice info

        $this->delete_invoice_items($invoice_id); // remove invoice items
    }

    // --------------------------------------------------------------------

    function delete_invoice_items($invoice_id)
    {
        $this->db->where('invoice_id', $invoice_id);
        $this->db->delete('invoice_items');
    }

    // --------------------------------------------------------------------

    function getSingleInvoice($invoice_id)
    {
        $this->db->distinct();
        
        $escape = TRUE;
        $roundFactor = $this->settings_model->getRoundFactor();
        $itemsPfx = $this->db->dbprefix('invoice_items');
        $invPfx = $this->db->dbprefix('invoices');
        $invPay = $this->db->dbprefix('invoice_payments');

        $this->db->select('invoices.*, clients.name, clients.address1, clients.address2, clients.city, clients.country, clients.province, clients.website, clients.postal_code, clients.tax_code', $escape);
        $this->db->select('(SELECT SUM(' . $this->db->dbprefix('invoice_payments') . '.amount_paid) FROM ' . $invPay . ' WHERE ' . $this->db->dbprefix('invoice_payments') . '.invoice_id=' . $invoice_id . ') AS amount_paid', $escape);
        $this->db->select('TO_DAYS(' . $invPfx . '.dateIssued) - TO_DAYS(curdate() + CAST(' . $invPfx . '.days_payment_due AS SIGNED)) AS daysOverdue', $escape);
        $this->db->select('(SELECT SUM(' . $itemsPfx . '.amount * ' . $itemsPfx . '.quantity) FROM ' . $itemsPfx . ' WHERE ' . $itemsPfx . '.invoice_id=' . $invoice_id . ') AS total_notax', $escape);
        //$this->db->select('(SELECT SUM((' . $itemsPfx . '.amount * ' . $invPfx . '.accounting_invoice_exchange_rate) * ' . $itemsPfx . '.quantity) FROM ' . $itemsPfx . ' WHERE ' . $itemsPfx . '.invoice_id=' . $invoice_id . ') AS accounting_total_notax', $escape);
        $this->db->select('(SELECT SUM(' . $itemsPfx . '.amount * ' . $itemsPfx . '.quantity * (' . $invPfx . '.tax1_rate/100 * ' . $itemsPfx . '.taxable)) FROM ' . $itemsPfx . ' WHERE ' . $itemsPfx . '.invoice_id=' . $invoice_id . ') AS total_tax1', $escape);
        $this->db->select('(SELECT SUM(' . $itemsPfx . '.amount * ' . $itemsPfx . '.quantity * (' . $invPfx . '.tax2_rate/100 * ' . $itemsPfx . '.taxable)) FROM ' . $itemsPfx . ' WHERE ' . $itemsPfx . '.invoice_id=' . $invoice_id . ') AS total_tax2', $escape);
        //$this->db->select('(SELECT SUM(' . $itemsPfx . '.amount * ' . $itemsPfx . '.quantity + ROUND((' . $itemsPfx . '.amount * ' . $itemsPfx . '.quantity * (' . $invPfx . '.tax1_rate/100 + ' . $invPfx . '.tax2_rate/100) * ' . $itemsPfx . '.taxable); 2)) FROM ' . $itemsPfx . ' WHERE ' . $itemsPfx . '.invoice_id=' . $invoice_id . ') AS total_with_tax', $escape);
        //$this->db->select('(SELECT SUM(' . $itemsPfx . '.amount * ' . $itemsPfx . '.quantity + ROUND(((' . $itemsPfx . '.amount * ' . $invPfx . '.accounting_invoice_exchange_rate) *' . $itemsPfx . '.quantity * (' . $invPfx . '.tax1_rate/100 + ' . $invPfx . '.tax2_rate/100) * ' . $itemsPfx . '.taxable); 2)) FROM ' . $itemsPfx . ' WHERE ' . $itemsPfx . '.invoice_id=' . $invoice_id . ') AS accounting_total_with_tax', $escape);

        $this->db->join('clients', 'invoices.client_id = clients.id', $escape);
        $this->db->join('invoice_items',
                'invoices.id = invoice_items.invoice_id', 'left', $escape);
        $this->db->join('invoice_payments',
                'invoices.id = invoice_payments.invoice_id', 'left', $escape);
        $this->db->group_by('invoices.id, invoices.client_id, invoices.invoice_number, invoices.dateIssued, invoices.payment_term, invoices.tax1_desc, , invoices.tax1_rate, invoices.tax2_desc, invoices.tax2_rate, invoices.invoice_note, invoices.days_payment_due, invoices.currency_type, invoices.currency_symbol, invoices.accounting_invoice_exchange_rate, clients.name, clients.address1, clients.address2, clients.city, clients.country, clients.province, clients.website, clients.postal_code, clients.tax_code', $escape);
        $this->db->where('invoices.id', $invoice_id, $escape);

        $data = $this->db->get('invoices');
        //do some rounding here
        $invoice = $data->row();
        //round tax to next 5er
        $invoice->total_tax1 = round($invoice->total_tax1 / $roundFactor) * $roundFactor;
        //round tax to next 5er
        $invoice->total_tax2 = round($invoice->total_tax2 / $roundFactor) * $roundFactor;
        //round tax to next 5er
        $invoice->total_with_tax = round(($invoice->total_notax+$invoice->total_tax1+$invoice->total_tax2) / $roundFactor) * $roundFactor;
        
        $invoice->accounting_total_with_tax = round(($invoice->total_notax+$invoice->total_tax1+$invoice->total_tax2) * $invoice->accounting_invoice_exchange_rate / $roundFactor) * $roundFactor;
        
        
        //round tax to next 5er
        $invoice->amount_paid = round($invoice->amount_paid / $roundFactor) * $roundFactor;
        return $data;
    }

    // --------------------------------------------------------------------

    function build_short_descriptions()
    {
        $this->db->distinct();
        
        $limit = ($this->config->item('short_description_characters') != '') ? $this->config->item('short_description_characters') : 50;

        $short_descriptions = array();

        $this->db->select('invoice_id, work_description', FALSE);
        $this->db->group_by('invoice_id, invoice_items.work_description');

        foreach ($this->db->get('invoice_items')->result() as $short_desc)
        {
            $short_descriptions[$short_desc->invoice_id] = ($limit == 0) ? '' : '[' . character_limiter($short_desc->work_description,
                            $limit) . ']';
        }

        return $short_descriptions;
    }

    // --------------------------------------------------------------------


    function build_short_descriptions_with_id($invoice_id)
    {
        $this->db->distinct();
        
        $limit = ($this->config->item('short_description_characters') != '') ? $this->config->item('short_description_characters') : 50;

        $short_descriptions = array();

        $this->db->select('invoice_id, work_description', FALSE);
        $this->db->group_by('invoice_id, work_description');
        $this->db->where('invoice_id', $invoice_id);

        foreach ($this->db->get('invoice_items')->result() as $short_desc)
        {
            $short_descriptions[$short_desc->invoice_id] = ($limit == 0) ? '' : '[' . character_limiter($short_desc->work_description,
                            $limit) . ']';
            //only interested in the first entry
            break;
        }

        return $short_descriptions;
    }

    // --------------------------------------------------------------------

    function getInvoiceItems($invoice_id)
    {

        $this->db->where('invoice_id', $invoice_id);
        $this->db->order_by('id', 'ASC');

        $items = $this->db->get('invoice_items');

        if ($items->num_rows() > 0) {
            return $items;
        } else {
            return FALSE;
        }
    }

    // --------------------------------------------------------------------

    function getInvoiceHistory($invoice_id)
    {
        $this->db->where('invoice_histories.invoice_id', $invoice_id);
        $this->db->order_by('date_sent');

        return $this->db->get('invoice_histories');
    }

    // --------------------------------------------------------------------

    function getInvoicePaymentHistory($invoice_id)
    {
        $this->db->where('invoice_id', $invoice_id);
        $this->db->order_by('date_paid');

        return $this->db->get('invoice_payments');
    }

    // --------------------------------------------------------------------

    function getInvoices($status, $offset = 0, $limit = 10000000)
    {
        return $this->_getInvoices(FALSE, FALSE, $status, $offset, $limit);
    }

    // --------------------------------------------------------------------

    function getInvoicesAJAX($status, $client_id)
    {
        return $this->_getInvoices(FALSE, $client_id, $status);
    }

    // --------------------------------------------------------------------

    function _getInvoices($invoice_id, $client_id, $status, $offset = 0,
            $limit = 10000000)
    {
        $this->db->distinct();
        
        $roundFactor = $this->settings_model->getRoundFactor();
        $itemsPfx = $this->db->dbprefix('invoice_items');
        $invPfx = $this->db->dbprefix('invoices');
        // check for any invoices first
        if ($this->db->count_all_results('invoices') < 1) {
            return FALSE;
        }

        if (is_numeric($invoice_id)) {
            $this->db->where('invoices.id', $invoice_id);
        }

        if (is_numeric($client_id)) {
            $this->db->where('client_id', $client_id);
        } else {
            $this->db->where('client_id IS NOT NULL');
        }

        if ($status == 'overdue') {
            //round 5er
            $this->db->having("daysOverdue <= -days_payment_due AND ((ROUND(amount_paid / " . $roundFactor . ", 0)*" . $roundFactor . ") < subtotal OR amount_paid is null)",
                    '', FALSE);
        } elseif ($status == 'open') {
            //round 5er
            $this->db->having("( (ROUND(amount_paid / " . $roundFactor . ", 0)*" . $roundFactor . ") < subtotal or amount_paid is null)",
                    '', FALSE);
        } elseif ($status == 'closed') {
            //round 5er
            $this->db->having('(ROUND(amount_paid / ' . $roundFactor . ' , 0)* '. $roundFactor . ') >= subtotal', '',
                    FALSE);
        }

        $this->db->select('invoices.*, clients.name');
        $this->db->select('(SELECT SUM(amount_paid) FROM ' . $this->db->dbprefix('invoice_payments') . ' WHERE invoice_id=' . $invPfx . '.id) AS amount_paid',
                FALSE);
        $this->db->select('TO_DAYS(' . $invPfx . '.dateIssued) - TO_DAYS(curdate() + CAST(' . $invPfx . '.days_payment_due AS SIGNED)) AS daysOverdue',
                FALSE);

        $this->db->select('ROUND((SELECT SUM( (((' . $itemsPfx . '.amount * ' . $itemsPfx . '.quantity + (' . $itemsPfx . '.amount * ' . $itemsPfx . '.quantity * ( ' . $itemsPfx . '.taxable * ' . $invPfx . '.tax1_rate/100 + ' . $itemsPfx . '.taxable * ' . $invPfx . '.tax2_rate/100))))) ) FROM ' . $itemsPfx . ' WHERE ' . $itemsPfx . '.invoice_id=' . $invPfx . '.id),2) AS subtotal',
                FALSE);
        $this->db->select('ROUND((SELECT SUM( (((' . $itemsPfx . '.amount * ' . $itemsPfx . '.quantity + (' . $itemsPfx . '.amount * ' . $itemsPfx . '.quantity * ( ' . $itemsPfx . '.taxable * ' . $invPfx . '.tax1_rate/100 + ' . $itemsPfx . '.taxable * ' . $invPfx . '.tax2_rate/100)) * ' . $invPfx . '.accounting_invoice_exchange_rate)))) FROM ' . $itemsPfx . ' WHERE ' . $itemsPfx . '.invoice_id=' . $invPfx . '.id),2) AS accounting_subtotal',
                FALSE);

        $this->db->join('clients', 'invoices.client_id = clients.id');
        $this->db->join('invoice_items',
                'invoices.id = invoice_items.invoice_id', 'left');
        $this->db->join('invoice_payments',
                'invoices.id = invoice_payments.invoice_id', 'left');

        $this->db->order_by('dateIssued desc, invoice_number desc');
        $this->db->group_by('invoices.id, invoices.client_id, invoices.invoice_number, invoices.dateIssued, invoices.payment_term, invoices.tax1_desc, invoices.tax1_rate, invoices.tax2_desc, invoices.tax2_rate, invoices.invoice_note, invoices.days_payment_due, invoices.currency_type, invoices.currency_symbol, invoices.accounting_invoice_exchange_rate, clients.name');       
        //$this->db->offset($offset);
        //$this->db->limit($limit);

        $query = $this->db->get('invoices');
        if ($query->num_rows() == 0) {
            
        } else {
            //because this select does not work we have to round after the select
            /**
             *  $this->db->select('(CEILING((ROUND(SELECT SUM( ' . $itemsPfx . '.amount * ' . $itemsPfx . '.quantity + (' . $itemsPfx . '.amount * ' . $itemsPfx . '.quantity * (' . $invPfx . '.tax1_rate/100 + ' . $invPfx . '.tax2_rate/100) * ' . $itemsPfx . '.taxable) ) FROM ' . $itemsPfx . ' WHERE ' . $itemsPfx . '.invoice_id=' . $invPfx . '.id); 2) / 0.05) * 0.05) AS cedi',
                FALSE);
            */
            /*commented out because query builder support this now
             * foreach ($query->result() as $row)
            {
                //round 5er
                $row->subtotal = round($row->subtotal / $roundFactor ) * $roundFactor;
                $row->accounting_subtotal = round($row->accounting_subtotal / $roundFactor) * $roundFactor;
            }*/
        }
        return $query;
    }

    // --------------------------------------------------------------------

    function lastInvoiceNumber($client_id)
    {
        if ($this->config->item('unique_invoice_per_client') === TRUE) {
            $this->db->where('client_id', $client_id);
        }

        $this->db->where('invoice_number != ""');
        $this->db->order_by("id", "desc");
        $this->db->limit(1);

        $query = $this->db->get('invoices');

        if ($query->num_rows() > 0) {
            return $query->row()->invoice_number;
        } else {
            return '0';
        }
    }

    // --------------------------------------------------------------------

    function uniqueInvoiceNumber($invoice_number)
    {
        $this->db->where('invoice_number', $invoice_number);

        $query = $this->db->get('invoices');

        $num_rows = $query->num_rows();

        if ($num_rows == 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // --------------------------------------------------------------------

    function uniqueInvoiceNumberEdit($invoice_number, $invoice_id)
    {
        $this->db->where('invoice_number', $invoice_number);
        $this->db->where('id != ', $invoice_id);
        $query = $this->db->get('invoices');

        $num_rows = $query->num_rows();

        if ($num_rows == 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}

?>