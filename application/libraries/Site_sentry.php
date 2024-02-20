<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * Site Sentry security library for Code Igniter applications
 * Author: James Nicol, Glossopteris Web Designs & Development, www.glossopteris.com, April 2006
 *
 * Modified very heavily for BambooINVOICE 
 */
#[\AllowDynamicProperties]
class Site_sentry
{

    public function __construct()
    {
        $this->obj = & get_instance();
    }
    function Site_sentry()
    {
        self::__construct();
    }

    function is_logged_in()
    {
        if ($this->obj->session) {

            //If user has valid session, and such is logged in
            if ($this->obj->session->userdata('logged_in') != NULL) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }

    function login_routine()
    {
        //Initialise the Encryption Library
        $this->obj->load->library('encryption');

        //Make the input username and password into variables
        $password = $this->obj->input->post('password');
        $username = $this->obj->input->post('username');

        //Use the input username and password and check against 'users' table
        $this->obj->db->where('access_level != 0');
        $this->obj->db->where('email', $username);
        
        $query = $this->obj->db->get('clientcontacts');

        $login_result = FALSE;
        foreach ($query->result() as $row)
        {
            if ($row->email == $username && $row->access_level != 0) {
                $pwd = $this->obj->encryption->decrypt($row->password);
                if ($pwd == $password) {
                    $login_result = TRUE;
                    $id = $row->id;
                    $this->obj->db->where('id', $id);
                    $this->obj->db->set('password_reset', '');
                    $this->obj->db->update('clientcontacts'); // if they have successfully logged in, we don't need that anymore
                }
            }
        }

        //If username and password match set the logged in flag in 'ci_sessions'
        if ($login_result == 1) {
            $credentials = array('user_id' => $id, 'logged_in' => $login_result);
            $this->obj->session->set_userdata($credentials);

            //On success redirect user to default page
            redirect('', 'location');
        } else {
            //On error send user back to login page, and add error message
            redirect('login/login_fail/');
        }
    }

}

?>