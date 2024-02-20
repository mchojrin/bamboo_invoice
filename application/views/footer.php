			<div id="footer">
				<p><?php echo anchor ('donate', $this->lang->line('actions_donate'), array('id'=>'donate'));?> | <?php echo anchor("http://www.bambooinvoice.net", $this->lang->line('bambooinvoice_logo'), array('title'=>'BambooInvoice'));?> &copy; <?php echo date("Y");?> (<?php echo $this->lang->line('bambooinvoice_version');?> <?php echo $this->settings_model->get_setting('bambooinvoice_version');?>)</p>
			</div>
		</div>
	</div>
</div>
</body>
</html>