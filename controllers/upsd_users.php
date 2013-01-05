<?php
use \Exception as Exception;

class upsd_users extends ClearOS_Controller
{
    function index()
    {
		$this->_form('view');
    }
	function edit($item)
    {
    	$this->_item('edit', $item);
    }
	function add()
    {
		$data['form_type'] = '';
    	$this->page->view_form('ups_server/upsd_users/users', $data, 'FIX ME SOON');
		//FIX HARD CODED VIEW.
		//$this->_item('');
    }
	function delete($item)
	{
	}
	function update($item)
	{
	}
    function _form($form_type, $item)
    {
    	$this->lang->load('ups_server');
    	$this->load->library('ups_server/nut');

        if ($this->input->post('submit') && ($form_ok === TRUE)) {

            try {
                //$this->page->set_status_updated();
                redirect('/ups_server/nut_conf/summary');
            } catch (Exception $e) {
                $this->page->view_exception($e);
                return;
            }
        }		
		
		try {
			$data['form_type'] = $form_type;
			$data['upsd_user_list'] = $this->nut->get_users_list();
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }
        $this->page->view_form('ups_server/upsd_users/summary', $data, 'app_nut_user_list');
	}
	function _item($form_type, $item)
    {
		$this->lang->load('nut');
		$this->load->library('ups_server/nut');
		
        $form_ok = $this->form_validation->run();

        if ($this->input->post('submit') && $form_ok) {
            try {
				if ($form_type === 'edit') {
                    
                    $this->page->set_status_updated();
                } else {

                    $this->page->set_status_added();
                }

                redirect('/ups_server/upsd_users/summary');
            } catch (Exception $e) {
                $this->page->view_exception($e);
                return;
            }
        }

        try {
			$data['form_type'] = $form_type;
			$data['upsd_user_name'] = $this->nut->get_users_list($item, 'name');
			$data['upsd_user_pwd'] = $this->nut->get_users_list($item, 'pwd');
			$data['upsd_user_actions_set'] = $this->nut->get_users_list($item, 'actions_set');
			$data['upsd_user_actions_fsd'] = $this->nut->get_users_list($item, 'actions_fsd');
			$data['upsd_user_upsmon'] = $this->nut->get_users_list($item, 'upsmon');
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }

        $this->page->view_form('ups_server/upsd_users/users', $data, lang('blah blah'));
    }
}
