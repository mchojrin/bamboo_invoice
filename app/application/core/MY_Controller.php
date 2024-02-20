<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	var $currency_decimal;
	var $currency_thousands;

        
        function __construct()
        {
            parent::__construct();

            // The cents separater is a hidden config variable.  If it isn't available default to '.'
            if ($this->config->item('currency_decimal') == '')
            {
                    $this->config->set_item('currency_decimal', '.');
            }
            // The cents separater is a hidden config variable.  If it isn't available default to '.'
            if ($this->config->item('currency_thousands') == '')
            {
                    $this->config->set_item('currency_thousands', '\'');
            }
            // a list of unlocked (ie: not password protected) controllers.  We assume
            // controllers are locked if they aren't explicitly on this list
            $unlocked = array('changelog', 'credits', 'donate', 'front', 'help', 'login');

            if ( ! $this->site_sentry->is_logged_in() AND ! in_array(strtolower(get_class($this)), $unlocked))
            {
                    redirect('login/');
            }

            $this->output->enable_profiler($this->config->item('show_profiler'));
	}

}