<?php
use \Exception as Exception;

class nut_conf extends ClearOS_Controller
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
                $this->nut->set_server_mode($this->input->post('server_mode'));
				$this->nut->set_server_upsd($this->input->post('server_upsd'));
				$this->nut->set_server_upsmon($this->input->post('server_upsmon'));
				$this->nut->set_server_poweroff_wait($this->input->post('server_poweroff_wait'));
                $this->page->set_status_updated();
                redirect('/ups_server/nut_conf/summary');
            } catch (Exception $e) {
                $this->page->view_exception($e);
                return;
            }
        }		
		
		try {
            $data['form_type'] = $form_type;
            $data['server_mode'] = $this->nut->get_server_mode();
			$data['show_options'] = TRUE;
			$data['server_upsd'] = $this->nut->get_server_upsd();
			$data['server_upsmon'] = $this->nut->get_server_upsmon();
			$data['server_poweroff_wait'] = $this->nut->get_server_poweroff_wait();
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }
    	$this->page->view_form('ups_server/nut_conf/summary', $data, lang('base_settings'));
    }
}
