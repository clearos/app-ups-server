<?php
use \Exception as Exception;

class ups_conf_summary_view extends ClearOS_Controller
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
                //$this->page->set_status_updated();
                redirect('/ups_server/nut_conf/summary');
            } catch (Exception $e) {
                $this->page->view_exception($e);
                return;
            }
        }        
        
        try {
            $data['form_type'] = $form_type;
            $data['ups_conf_list'] = $this->nut->get_ups_list();
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }
        $this->page->view_form('ups_server/ups_conf/summary_view', $data, lang('ups_server_ups_list'));
    }
}
