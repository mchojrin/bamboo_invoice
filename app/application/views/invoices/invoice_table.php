<?php
if (isset($pagination)) {
	echo $pagination;
}
?>
<table class="stripe" id="invoiceListings">
	<tbody id="invoiceRows">
	<tr>
		<th class="invNum"><?php echo $this->lang->line('invoice_invoice');?></th>
		<th class="dateIssued"><?php echo $this->lang->line('invoice_date_issued');?></th>
		<th class="clientName"><?php echo $this->lang->line('clients_name');?></th>
		<th class="amount"><?php echo $this->lang->line('invoice_amount');?></th>
		<th class="status"><?php echo $this->lang->line('invoice_status');?></th>
	</tr>
<?php
if (isset($total_rows) && $total_rows == 0):
?>
	<tr>
		<td colspan="5">
			<?php echo $this->lang->line('invoice_no_invoice_match');?>
		</td>
	</tr>
<?php
 else:
 
	$last_retrieved_month = 0;
	$display_month = TRUE; // for later use in a setting preference

	foreach($query->result() as $row): 

		$invoice_date = mysql_to_unix($row->dateIssued);
		if ($last_retrieved_month != date('F', $invoice_date) && $display_month):
?>

	<tr>
		<td colspan="5" class="monthbreak"><?php echo date('F', $invoice_date);?></td>
	</tr>

<?php 
		endif; 
		$last_retrieved_month = date('F', $invoice_date);
		// localized month
		$display_date = formatted_invoice_date($row->dateIssued);

?>
	<tr>
		<td><?php echo anchor('invoices/view/'.$row->id, $row->invoice_number);?></td>
		<td><?php echo anchor('invoices/view/'.$row->id, $display_date);?></td>
		<td class="cName"><?php echo anchor('invoices/view/'.$row->id, $row->name);?> <span class="short_description"><?php echo $short_description[$row->id]?></span></td>
		<td><?php $currencySymbol = ($row->currency_symbol == null || empty($row->currency_symbol)) ? $this->clients_model->getCurrencySymbol($row->client_id) : $row->currency_symbol;
                    
                    //round tax to next 5er
                    $roundFactor = $this->settings_model->getRoundFactor();
                    $totalAmount = round($row->subtotal / $roundFactor) * $roundFactor;
                    echo anchor('invoices/view/'.$row->id,  $currencySymbol.  " " .number_format($totalAmount, 2, $this->config->item('currency_decimal'), $this->config->item('currency_thousands')));?>
                </td>
		<td>
		<?php
                //make both numeric if one is a string comparison does not work
                $row->amount_paid = $row->amount_paid * 1;
                $row->subtotal = $row->subtotal * 1;
		if ($row->amount_paid >= ($row->subtotal + .001))
		{
                    // paid invoices
                    echo anchor('invoices/view/'.$row->id, $this->lang->line('invoice_closed'), array('title' => 'invoice status'));
		}
		elseif (mysql_to_unix($row->dateIssued) >= strtotime('-'.$row->days_payment_due. ' days')) 
		{
                    // owing less then the overdue days amount
                    echo anchor('invoices/view/'.$row->id, $this->lang->line('invoice_open'), array('title' => 'invoice status'));
		}
		else
		{ 
                    // owing more then the overdue days amount
                    // convert days due into a timestamp, and add the days payement is due in seconds
                    $due_date = mysql_to_unix($row->dateIssued) + ($row->days_payment_due * 60*60*24); 
                    $line = "<span class='error'>" . timespan($due_date, now()) . ' '.$this->lang->line('invoice_overdue').'</span>';
                    echo anchor('invoices/view/'.$row->id, $line, array('title' => 'invoice status'));
		}
		?>
		</td>
	</tr>
	<?php
	endforeach;
	endif;
	?>
	</tbody>
</table>
