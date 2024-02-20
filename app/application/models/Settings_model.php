<?php

class settings_model extends CI_Model
{

    function getCompanyInfo()
    {
        //return $this->db->get('settings');
        return $this->get_row();
    }

    function getSettingsID()
    {
        //sanity check if logged in user changed
        $userID = $this->session->userdata('user_id');
        $saveuserID = $this->session->userdata('save_user_id');
        if($userID != $saveuserID){
           $this->session->set_userdata('settings_user_id', 0); 
        }
        
        $settingsUserID = $this->session->userdata('settings_user_id');
        if($settingsUserID != null && $settingsUserID != 0){
            return $settingsUserID;
        }
        
        $this->session->set_userdata('save_user_id', $userID);
        
        $this->db->where('id', $userID);
        $clientrow = $this->db->get('clientcontacts');
        if ($clientrow->num_rows() === 1) {
            $email = $clientrow->row()->email;
            $this->db->where('primary_contact_email', $email);
            $settingsrow = $this->db->get('settings');
            if ($settingsrow->num_rows() === 1) {
                $this->session->set_userdata('settings_user_id', $settingsrow->row()->id);
                
                return $settingsrow->row()->id;
            }
        }

        // return default first entry of corresponding entry is not found
        $settingsrow = $this->db->get('settings');
        if ($settingsrow->num_rows() === 1) {
            $this->session->set_userdata('settings_user_id', $settingsrow->row()->id);
            return $settingsrow->row()->id;
        }
        //if everything fails return 1
        $this->session->set_userdata('settings_user_id', 1);
        return 1;
    }

    // --------------------------------------------------------------------
    function get_row()
    {
        $id = $this->getSettingsID();
        $this->db->where('id', $id);
        $row = $this->db->get('settings');

        if ($row->num_rows() === 1) {
            return $row->row();
        } else {
            return NULL;
        }
    }
    
    function get_setting($field)
    {
        $id = $this->getSettingsID();
        $this->db->where('id', $id);
        $row = $this->db->get('settings');

        if ($row->num_rows() === 1) {
            return $row->row()->$field;
        } else {
            return FALSE;
        }
    }

    // --------------------------------------------------------------------

    function update_settings($data = array())
    {
        $id = $this->getSettingsID();
        if (count($data) == 0) {
            return TRUE; // no changes, just return a success
        }

        $this->db->where('id', $id);

        return $this->db->update('settings', $data);
    }
    // --------------------------------------------------------------------

    function getRoundFactor()
    {
        return 0.01;
    }

}

?>