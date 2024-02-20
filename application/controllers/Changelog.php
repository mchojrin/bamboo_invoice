<?php

class Changelog extends MY_Controller {

        function __construct() {
            parent::__construct();
	}

	function index()
	{
		$data['page_title'] = $this->lang->line('menu_changelog');
		$this->load->view('changelog/index', $data);
	}

}
?>