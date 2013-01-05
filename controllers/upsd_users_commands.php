<?php
use \Exception as Exception;

class upsd_users_commands extends ClearOS_Controller
{
    function index()
    {
		$this->_form('view');
    }
	function edit()
    {
    	$this->_form('edit');
    }
    function _form($form_type, $item)
    {
    	$this->lang->load('ups_server');
    	$this->load->library('ups_server/nut');

        if ($this->input->post('submit') && ($form_ok === TRUE)) {

            try {
                //$this->page->set_status_updated();
                redirect('/ups_server/nut_conf/commands_view');
            } catch (Exception $e) {
                $this->page->view_exception($e);
                return;
            }
        }		
		
		try {
			$data['form_type'] = $form_type;
			$data['upsd_users_command_list'] = $this->nut->get_user_commands_list();
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }
        $this->page->view_form('ups_server/upsd_users/commands_view', $data, lang('ups_server_ups_list'));
	}
}
