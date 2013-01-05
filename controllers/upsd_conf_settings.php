<?php
use \Exception as Exception;

class upsd_conf_settings extends ClearOS_Controller
{
    function index()
    {
		$this->_form('view');
    }
	function edit()
    {
    	$this->_form('edit');
    }
    function _form($form_type)
    {
    	$this->lang->load('ups_server');
    	$this->load->library('ups_server/nut');

        if ($this->input->post('submit') && ($form_ok === TRUE)) {

            try {

                redirect('/ups_server/nut_conf/summary');
            } catch (Exception $e) {
                $this->page->view_exception($e);
                return;
            }
        }		
		
		try {
			$data['form_type'] = $form_type;
			$data['upsd_conf_maxage'] = '5';
			$data['upsd_conf_statepath'] = 'statepath';
			$data['upsd_conf_maxconn'] = 'maxconnections';
			$data['upsd_conf_certfile'] = 'certfile';
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }
        $this->page->view_form('ups_server/upsd_conf/settings', $data, lang('ups_server_ups_list'));
	}
}
