<?php

class Help extends MY_Controller {

        function __construct() {
            parent::__construct();
	}

	function index()
	{
		$this->output->cache(5);
		$data['page_title'] = $this->lang->line('menu_help');
		$this->load->view('help/index', $data);
	}

}
?>