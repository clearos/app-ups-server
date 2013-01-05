<?php
use \Exception as Exception;

class ups_conf_commands_edit extends ClearOS_Controller
{
    function index()
    {
		$this->_form('view');
    }
	function edit($item)
    {
    	$this->_form('edit', $item);
    }
	function add()
	{
		$this->_item('');
	}
	function delete()
	{
	}
    function _form($form_type, $item)
    {
    	$this->lang->load('ups_server');
    	$this->load->library('ups_server/nut');

        if ($this->input->post('submit') && ($form_ok === TRUE)) {

            try {
                //TEST REDIRECT
                redirect('/ups_server/nut_conf/summary');
            } catch (Exception $e) {
                $this->page->view_exception($e);
                return;
            }
        }		
		
		try {
			$data['form_type'] = $form_type;
			$data['ups_commands_command'] = $this->nut->get_ups_commands_list($item, 'command');
			$data['ups_commands_default'] = $this->nut->get_ups_commands_list($item, 'default');
			$data['ups_commands_override'] = $this->nut->get_ups_commands_list($item, 'override');
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }
        $this->page->view_form('ups_server/ups_conf/commands_edit', $data, 'ups_server_ups_list');
	}
	function _item($form_type)
    {
		$this->lang->load('ups_server');
        $form_ok = $this->form_validation->run();

        if ($this->input->post('submit') && $form_ok) {
            try {
				if ($form_type === 'edit') {
                    
                    $this->page->set_status_updated();
                } else {

                    $this->page->set_status_added();
                }

                //TEST REDIRECT
                redirect('/ups_server/nut_conf/summary');
            } catch (Exception $e) {
                $this->page->view_exception($e);
                return;
            }
        }

        try {
			$data['form_type'] = $form_type;
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }

        $this->page->view_form('ups_server/ups_conf/commands_edit', $data, lang('ups_server_server_settings'));
    }
}
