<?php
$this->load->view('header');
?>
<h2><?php echo $page_title; ?></h2>

<?php echo form_open('clients/edit', array('id' => 'newClientForm', 'onsubmit' => 'return requiredFields();'), array('id'=>$row->id));?>

	<p><label><span><?php echo $this->lang->line('clients_name');?>:</span> <input class="requiredfield" type="text" id="clientName" name="clientName" size="50" maxlength="50" value="<?php echo ($this->validation->clientName) ? ($this->validation->clientName) : ($row->name);?>" /></label> <?php echo $this->validation->clientName_error; ?></p>
	<p><label><span><?php echo $this->lang->line('clients_website');?>:</span> <input type="text" name="website" id="website" size="50" maxlength="50" value="<?php echo ($this->validation->website) ? ($this->validation->website) : ($row->website);?>" /></label> <?php echo $this->validation->website_error; ?></p>
	<div class="address">
	<p><label><span><?php echo $this->lang->line('clients_address1');?>:</span> <input type="text" name="address1" id="address1" size="50" maxlength="50" value="<?php echo ($this->validation->address1) ? ($this->validation->address1) : ($row->address1);?>" /></label> <?php echo $this->validation->address1_error; ?></p>
	<p><label><span><?php echo $this->lang->line('clients_address2');?>:</span> <input type="text" name="address2" id="address2" size="50" maxlength="50" value="<?php echo ($this->validation->address2) ? ($this->validation->address2) : ($row->address2);?>" /></label> <?php echo $this->validation->address2_error; ?></p>
	<p><label><span><?php echo $this->lang->line('clients_city');?>:</span> <input type="text" name="city" id="city" size="50" maxlength="50" value="<?php echo ($this->validation->city) ? ($this->validation->city) : ($row->city);?>" /></label> <?php echo $this->validation->city_error; ?></p>
	<p><label><span><?php echo $this->lang->line('clients_province');?>:</span> <input type="text" name="province" id="province" size="25" maxlength="25" value="<?php echo ($this->validation->province) ? ($this->validation->province) : ($row->province);?>" /></label> <?php echo $this->validation->province_error; ?></p>
	<p><label><span><?php echo $this->lang->line('clients_country');?>:</span> <input type="text" name="country" id="country" size="25" maxlength="25" value="<?php echo ($this->validation->country) ? ($this->validation->country) : ($row->country);?>" /></label><?php echo $this->validation->country_error; ?></p>
	<p><label><span><?php echo $this->lang->line('clients_postal');?>:</span> <input type="text" name="postal_code" id="postal_code" size="10" maxlength="10" value="<?php echo ($this->validation->postal_code) ? ($this->validation->postal_code) : ($row->postal_code);?>" /></label> <?php echo $this->validation->postal_code_error; ?></p>
	</div>

	<p><label><span><?php echo $this->lang->line('settings_tax_code');?>:</span> <input type="text" name="tax_code" id="tax_code" size="50" maxlength="75" value="<?php echo ($this->validation->tax_code) ? ($this->validation->tax_code) : ($row->tax_code);?>" /></label> <?php echo $this->validation->tax_code_error; ?></p>
	<p><label><span><?php echo $this->lang->line('settings_currency_type');?>:</span> <input type="text" name="currency_type" id="currency_type" size="20" maxlength="20" value="<?php echo ($this->validation->currency_type) ? ($this->validation->currency_type) : ($row->currency_type);?>" /></label> <?php echo $this->validation->currency_type_error; ?></p>
	<p><label><span><?php echo $this->lang->line('settings_currency_symbol');?>:</span> <input type="text" name="currency_symbol" id="currency_symbol" size="9" maxlength="9" value="<?php echo ($this->validation->currency_symbol) ? ($this->validation->currency_symbol) : ($row->currency_symbol);?>" /></label> <?php echo $this->validation->currency_symbol_error; ?></p>
	<p><label><span><?php echo $this->lang->line('settings_days_payment_due');?>:</span> <input type="text" name="days_payment_due" id="days_payment_due" size="5" maxlength="5" value="<?php echo ($this->validation->days_payment_due) ? ($this->validation->days_payment_due) : ($row->days_payment_due);?>" /></label> <?php echo $this->validation->days_payment_due_error; ?></p>
        <p><label><span><?php echo $this->lang->line('settings_default_note')."<br/>".$this->lang->line('invoice_note_max_chars');?>:</span> <textarea name="invoice_note_default" id="invoice_note_default" cols="50" rows="5" maxlength="255"><?php echo ($this->validation->invoice_note_default) ? ($this->validation->invoice_note_default) : ($row->invoice_note_default);?></textarea></label> <?php echo $this->validation->invoice_note_default_error; ?></p>

	<fieldset style="clear:left;"><legend><?php echo $this->lang->line('invoice_tax_status');?>:</legend>
	<?php if ($row->tax_status): ?>
	<label for="taxable"><input type="radio" name="tax_status" id="taxable" value="1" checked="checked" class="noborder" /><?php echo $this->lang->line('invoice_taxable');?></label><br />
	<label for="notax"><input type="radio" name="tax_status" id="notax" value="0" class="noborder" /><?php echo $this->lang->line('invoice_not_taxable');?></label>
	<?php else:?>
	<label for="taxable"><input type="radio" name="tax_status" id="taxable" value="1" class="noborder" /><?php echo $this->lang->line('invoice_taxable');?></label><br />
	<label for="notax"><input type="radio" name="tax_status" id="notax" value="0" checked="checked" class="noborder" /><?php echo $this->lang->line('invoice_not_taxable');?></label>
	<?php endif;?>
	</fieldset>

	<p><?php echo form_submit('updateClient', $this->lang->line('clients_update_client'), 'id="updateClient"');?></p>

<?php echo form_close();?>

<?php
$this->load->view('footer');
?>