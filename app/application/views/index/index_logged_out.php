<?php
$this->load->view('header');
?>

<h2>Welcome to <?php echo $this->lang->line('bambooinvoice_logo');?></h2>

<div id="loginform">

	<h3>Demo <?php echo $this->lang->line('bambooinvoice_logo');?> </h3>

	<p>If you are interested in seeing <?php echo $this->lang->line('bambooinvoice_logo');?> in action, please <?php echo anchor('login', 'login');?> with the username "admin@admin.com" and the password "password"... </p>

</div>

<p><?php echo anchor('http://bambooinvoice.net', $this->lang->line('bambooinvoice_logo'), array('title'=>'BambooInvoice'));?> is free Open Source invoicing software intended for small businesses and independent contractors.  Our number one priorities are ease of use, user-interface, and beautiful code.</p>

<p><?php echo $this->lang->line('bambooinvoice_logo');?> was originally built by designer and programmer Derek Allard and further developed by Cedric Perrot, who uses it everyday, not by a large firm who can't remember the names of its customers.  It is meant to be sexy, both on top of, and under the hood.  Go ahead, kick the tires.  View source shows semantic, structured, meaningful <acronym title="Extensible Hypertext Markup Language">XHTML</acronym>.  We use <acronym title="Asynchronous Javascript And XML">AJAX</acronym> to keep things peppy and javascript to keep things functional.  The entire application degrades gracefully for users without javascript enabled, and complies with <acronym title="Web Accessibility Initiative">WAI</acronym> priority two.</p>

<div class="work_description download">
	<p><a id="bamboodownload" href="https://sourceforge.net/projects/bambooinvoice/">Download Bamboo</a> (view <?php echo anchor ('changelog', 'changelog') . ' and ' . anchor('credits', 'credits');?>) </p>
</div>

<div class="work_description">
	<p><?php echo $this->lang->line('bambooinvoice_logo');?> now has <a href="http://www.bambooinvoice.net"> Website</a>.  Need help, want to chat?  Come visit us.</p>
</div>

<p><strong>Note:</strong> <?php echo $this->lang->line('bambooinvoice_logo');?> is now a stable version.  There are a few bugs and uncompleted features.  It is stable for everyday use, and is growing all the time.</p>

<h3 style="clear:left;">Features</h3>

<ul>
	<li><em>You are in control</em>. <?php echo $this->lang->line('bambooinvoice_logo');?> sits on <em>your</em> server.  Its <em>your</em> data. You never need to trust your invoicing data to anyone else, and you can get it out of the system easily.</li>
	<li><?php echo $this->lang->line('bambooinvoice_logo');?> is easy to use, and easy on the eyes.</li>
	<li><?php echo $this->lang->line('bambooinvoice_logo');?> is actively used and  developed. </li>
	<li><?php echo $this->lang->line('bambooinvoice_logo');?> is built atop modern coding standards.  Its a <acronym title="Extensible Hypertext Markup Language">XHTML</acronym>-strict, <acronym title="Web Accessibility Initiative">WAI</acronym> conforming, <acronym title="Cascading Style Sheets">CSS</acronym> using treat!</li>
	<li><?php echo $this->lang->line('bambooinvoice_logo');?> is internationalized. Currently it is available in English, French, German, Dutch, Romanian, Spanish, Portuguese, Bulgarian, Swedish, Italian, and Estonian with new languages being added all the time.</li>
	<li>Built on the excellent <a href="http://codeigniter.com/">CodeIgniter</a> project.</li>
	<li>You'll be the coolest kid on your block if you use  <?php echo $this->lang->line('bambooinvoice_logo');?></li>
</ul>

<h3>Requirements</h3>

<p><?php echo $this->lang->line('bambooinvoice_logo');?> is built using <acronym title="Hypertext Preprocessor">PHP</acronym> 5 and 7.x and needs a database (MySQL and MySQLi 5+ are known to work, and drivers are included for MSSQL, Postgre, OCI8, SQLite, and ODBC). In order to generate PDFs, you'll need the DOM extension enabled.</p>

<p>It is known to work with modern, standards compliant browsers.</p>

<?php
$this->load->view('footer');
?>