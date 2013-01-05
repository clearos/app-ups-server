<?php
use \Exception as Exception;

class upsmon_conf extends ClearOS_Controller
{
    function index()
    {
        $this->_form('view');
    }
    function edit()
    {
    	$this->_form('edit');
    }
    function view()
    {
    	$this->_form('view');
    }
    function _form($form_type)
    {
    	$this->lang->load('ups_server');
    	$this->load->library('ups_server/nut');
		
		$this->form_validation->set_policy('server_mode', 'ups_server/nut', 'validate_server_mode', TRUE);
        $form_ok = $this->form_validation->run();
		
        if ($this->input->post('submit') && ($form_ok === TRUE)) {

            try {
                $this->page->set_status_updated();
                redirect('/ups_server/upsmon_conf/summary');
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
    	$this->page->view_form('ups_server/upsmon_conf/summary', $data, lang('base_settings'));
    }
}
