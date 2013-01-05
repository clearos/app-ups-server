<?php
use \Exception as Exception;

class upsd_conf_summary_view extends ClearOS_Controller
{
    function index()
    {
		$this->_form('view');
    }
	function edit($item)
    {
		$this->_item('edit', $item);
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
			$data['upsd_conf_interfaces'] = $this->nut->get_upsd_interfaces();
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }
        $this->page->view_form('ups_server/upsd_conf/summary_view', $data, lang('ups_server_ups_list'));
	}
}
