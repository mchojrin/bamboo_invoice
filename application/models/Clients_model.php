<?php
class clients_model extends CI_Model {

	function countAllClients()
	{
		return $this->db->count_all('clients');
	}

	// --------------------------------------------------------------------

	function countClientInvoices($client_id)
	{
		$this->db->where('client_id', $client_id);

		return $this->db->count_all_results('invoices');
	}

	// --------------------------------------------------------------------

	function getAllClients()
	{
		// we need an array of company names to associate each contact with its company
//		$companies = array();
//		foreach($this->clients_model->getAllClients()->result() as $company)
//		{
//			$companies[$company->id] = $company->name;
//		}

		$this->db->order_by('name', 'asc');

		return $this->db->get('clients');
	}
	// --------------------------------------------------------------------
        
        function getInvoiceNote($clientId)
        {
            $client = $this->clients_model->get_client_info($clientId);
            if($client->invoice_note_default != null && !empty($client->invoice_note_default)){
                return $client->invoice_note_default;
            }
            return $this->settings_model->get_setting('invoice_note_default');
        }
        
	// --------------------------------------------------------------------
        
        function getCurrencyType($clientId)
        {
            $client = $this->clients_model->get_client_info($clientId);
            if($client->currency_type != null && !empty($client->currency_type)){
                return $client->currency_type;
            }
            return $this->settings_model->get_setting('currency_type');
        }
        
	// --------------------------------------------------------------------

        function getCurrencySymbol($clientId)
        {
            $client = $this->clients_model->get_client_info($clientId);
            if($client->currency_symbol != null && !empty($client->currency_symbol)){
                return $client->currency_symbol;
            }
            return $this->settings_model->get_setting('currency_symbol');
        }
        
 	// --------------------------------------------------------------------
        
        function getDaysPaymentDue($clientId)
        {
            $client = $this->clients_model->get_client_info($clientId);
            if($client->days_payment_due > 0){
                return $client->days_payment_due;
            }
            else if($this->settings_model->get_setting('days_payment_due') > 0){
                return $this->settings_model->get_setting('days_payment_due');
            }
            //if no one has set anything we return default 30 days
            return 30;
        }

        // --------------------------------------------------------------------
        
	function get_client_info($id, $fields = '*')
	{
		$this->db->select($fields);
		$this->db->where('id', $id);

		return $this->db->get('clients')->row();
	}

	// --------------------------------------------------------------------

	function getClientContacts($id)
	{
		$this->db->where('client_id', $id);

		return $this->db->get('clientcontacts');
	}

	// --------------------------------------------------------------------

	function addClient($clientInfo)
	{
		$this->db->insert('clients', $clientInfo);

		return TRUE;
	}

	// --------------------------------------------------------------------

	function updateClient($client_id, $clientInfo)
	{
		$this->db->where('id', $client_id);
		$this->db->update('clients', $clientInfo);

		return TRUE;
	}

	// --------------------------------------------------------------------

	function deleteClient($client_id)
	{
		// Don't allow admins to be deleted this way
		if ($client_id === 0)
		{
			return FALSE;
		}
		else
		{
			// get all invoices related to this client
			$this->db->select('id');
			$this->db->where('client_id', $client_id);
			$result = $this->db->get('invoices');

			$invoice_id_array = array(0);

			foreach ($result->result() as $invoice_id)
			{
				$invoice_id_array[] = $invoice_id->id;
			}

			// There are 5 tables of data to delete from in order to completely
			// clear out record of this client.

			$this->db->where_in('invoice_id', $invoice_id_array);
			$this->db->delete('invoice_histories');

			$this->db->where_in('invoice_id', $invoice_id_array);
			$this->db->delete('invoice_payments');

			$this->db->where('client_id', $client_id);
			$this->db->delete('clientcontacts'); 

			$this->db->where('id', $client_id);
			$this->db->delete('clients');

			$this->db->where('client_id', $client_id);
			$this->db->delete('invoices'); 

			return TRUE;
		}
	}

}
?>