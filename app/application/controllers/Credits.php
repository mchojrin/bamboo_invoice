<?php

class Credits extends MY_Controller {

        function __construct() {
            parent::__construct();
	}

	function index()
	{
		$data['page_title'] = $this->lang->line('menu_credits');
		$this->load->view('credits/index', $data);
	}

}
?>