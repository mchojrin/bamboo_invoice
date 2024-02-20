<?php
$this->load->view('header');
?>
<h2><a id="top"></a><?php echo $this->lang->line('bambooinvoice_logo');?> <?php echo $page_title;?></h2>

<?php if ($this->lang->line('notice_english_only') != ''):?>
	<p class="error"><?php echo $this->lang->line('notice_english_only');?></p>
<?php endif;?>

<p>Seriously, it means a lot for someone to say "thanks". Please <a href="mailto:info@bambooinvoice.net">contact me</a> and let me know who you are ok.</p>
<div>
<br/>    
<a href="https://www.paypal.me/bambooinvoice/5" target="_blank">Donate</a>
</div>

<?php
$this->load->view('footer');
?>