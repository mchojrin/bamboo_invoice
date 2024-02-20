<?php

class reports_model extends CI_Model
{

    function getDetailedData($start_date, $end_date)
    {
        $this->db->distinct();
        
        $escape = TRUE;
        $roundFactor = $this->settings_model->getRoundFactor();
        $itemsPfx = $this->db->dbprefix('invoice_items');
        $invPfx = $this->db->dbprefix('invoices');
        $clientsPfx = $this->db->dbprefix('clients');
        $this->db->select($clientsPfx.'.name, ' .  $invPfx.'.currency_type, '.  $invPfx.'.currency_symbol');
        $this->db->select('SUM('.$itemsPfx. '.amount * '.$itemsPfx.'.quantity'.') AS amount', $escape);
    
        
        //5er round
        $this->db->select('SUM( ' . $itemsPfx . '.taxable * ROUND((' . $itemsPfx . '.amount*' . $invPfx . '.tax1_rate/100 * ' . $itemsPfx . '.quantity) / ' . $roundFactor . ' ,0) * ' . $roundFactor . ') as tax1_collected', FALSE);
        //5er round
        $this->db->select('SUM( ' . $itemsPfx . '.taxable * ROUND((' . $itemsPfx . '.amount*' . $invPfx . '.tax2_rate/100 * ' . $itemsPfx . '.quantity) / ' . $roundFactor . ' ,0) * ' . $roundFactor . ') as tax2_collected', FALSE);
        //5er round
        // had to remove round ,0 decimals select did put quotes around it 0 is default anyway
        $this->db->select('SUM( ROUND((' . $itemsPfx . '.amount * ' .$invPfx . '.accounting_invoice_exchange_rate * ' . $itemsPfx . '.quantity + ((' . $itemsPfx . '.amount * ' . $invPfx . '.accounting_invoice_exchange_rate) *' . $itemsPfx . '.quantity * (' . $invPfx . '.tax1_rate/100 + ' . $invPfx . '.tax2_rate/100) * ' . $itemsPfx . '.taxable)) / ' . $roundFactor . ',0) * ' . $roundFactor . ' ) AS accounting_total_with_tax', FALSE);
        //$this->db->select('SUM( ROUND((' . $itemsPfx . '.amount * ' .$invPfx . '.accounting_invoice_exchange_rate * ' . $itemsPfx . '.quantity + ((' . $itemsPfx . '.amount * ' . $invPfx . '.accounting_invoice_exchange_rate) *' . $itemsPfx . '.quantity * (' . $invPfx . '.tax1_rate/100 + ' . $invPfx . '.tax2_rate/100) * ' . $itemsPfx . '.taxable)) / 0.05) * 0.05 ) AS accounting_total_with_tax', FALSE);
        
        $this->db->join('invoices', $invPfx.'.client_id = clients.id');
        $this->db->join('invoice_items', 'invoices.id = invoice_items.invoice_id');
        $this->db->where('dateIssued >= "' . $start_date . '" and dateIssued <= "' . $end_date . '"');
        $this->db->order_by($invPfx.'.currency_type,' . $clientsPfx.'.name');
        $this->db->group_by($clientsPfx.'.name, ' . $invPfx.'.currency_type, '. $invPfx.'.currency_symbol');

        return $this->db->get('clients');
    }

    // --------------------------------------------------------------------

    function getSummaryData($start_date, $end_date)
    {
        $roundFactor = $this->settings_model->getRoundFactor();
        $escape = TRUE;
        
        $itemsPfx = $this->db->dbprefix('invoice_items');
        $invPfx = $this->db->dbprefix('invoices');
        $this->db->select('SUM( ROUND((' .$itemsPfx.'.quantity * '. $itemsPfx.'.amount) * ' .$invPfx . '.accounting_invoice_exchange_rate,2)) AS accounting_total_without_tax', $escape);
        //5er round
        $this->db->select('SUM( ROUND((' . $itemsPfx . '.amount*' . $this->db->dbprefix('invoices') . '.tax1_rate/100 * ' . $itemsPfx . '.quantity) / ' . $roundFactor . ' , 0) * ' . $roundFactor . ') as tax1_collected', $escape);
        //5er round
        $this->db->select('SUM( ROUND((' . $itemsPfx . '.amount*' . $this->db->dbprefix('invoices') . '.tax2_rate/100 * ' . $itemsPfx . '.quantity) / ' . $roundFactor . ' ,0) * ' . $roundFactor . ') as tax2_collected', $escape);
        //5er round
        $this->db->select('SUM(ROUND(((' . $itemsPfx . '.amount*' . $itemsPfx . '.quantity)*' . $this->db->dbprefix('invoices') . '.tax1_rate/100 * ' .$invPfx . '.accounting_invoice_exchange_rate) / ' . $roundFactor . ' ,0) * ' . $roundFactor . ') AS accounting_tax1_collected', $escape);
        //5er round
        $this->db->select('SUM(ROUND(((' . $itemsPfx . '.amount*' . $itemsPfx . '.quantity)*' . $this->db->dbprefix('invoices') . '.tax2_rate/100 * ' .$invPfx . '.accounting_invoice_exchange_rate) / ' . $roundFactor . ' ,0) * ' . $roundFactor . ') AS accounting_tax2_collected', $escape);        
        //5er round
        $this->db->select('SUM(ROUND((' . $itemsPfx . '.amount * ' .$invPfx . '.accounting_invoice_exchange_rate * ' . $itemsPfx . '.quantity + ((' . $itemsPfx . '.amount * ' . $this->db->dbprefix('invoices') . '.accounting_invoice_exchange_rate) *' . $itemsPfx . '.quantity * (' . $this->db->dbprefix('invoices') . '.tax1_rate/100 + ' . $this->db->dbprefix('invoices') . '.tax2_rate/100) * ' . $itemsPfx . '.taxable)) / ' . $roundFactor . ' ,0 ) * ' . $roundFactor . ' ) AS accounting_total_with_tax', $escape);
        $this->db->select('SUM('.$itemsPfx. '.amount * '.$itemsPfx.'.quantity'.') AS amount', $escape);
        $this->db->join('invoices', 'invoices.client_id = clients.id', $escape);
        $this->db->join('invoice_items', 'invoices.id = invoice_items.invoice_id', $escape);
        $this->db->where('dateIssued >= ', $start_date, $escape);
        $this->db->where('dateIssued <= ', $end_date, $escape);

        return $this->db->get('clients')->row();
    }

    // --------------------------------------------------------------------

    function getInvoiceDateRange($start_date, $end_date)
    {
        $this->db->distinct();
        $this->db->select('invoices.id, invoices.dateIssued, invoices.invoice_number');
        $this->db->join('clients', 'invoices.client_id = clients.id');
        $this->db->join('invoice_items', 'invoices.id = invoice_items.invoice_id');
        $this->db->where("dateIssued >= '$start_date'");
        $this->db->where("dateIssued <= '$end_date'");
        $this->db->order_by('dateIssued desc, invoice_number desc');

        return $this->db->get('invoices');
    }

}

?>