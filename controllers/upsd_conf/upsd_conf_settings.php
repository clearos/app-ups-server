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
        $form_ok = TRUE;
        if ($this->input->post('submit') && ($form_ok === TRUE)) {

            try {
                $this->nut->set_upsd_conf($this->input->post('maxage'), 'maxage', FALSE);
                $this->nut->set_upsd_conf($this->input->post('statepath'), 'statepath', TRUE);
                $this->nut->set_upsd_conf($this->input->post('maxconn'), 'maxconn', FALSE);
                $this->nut->set_upsd_conf($this->input->post('certfile'), 'certfile', FALSE);
                $this->page->set_status_updated();
                redirect('/ups_server/upsd_conf/upsd_conf_settings');
            } catch (Exception $e) {
                $this->page->view_exception($e);
                return;
            }
        }        
        
        try {
            $data['form_type'] = $form_type;
            $data['dir'] = 'upsd_conf';
            $data['maxage'] = $this->nut->get_upsd_conf('maxage', TRUE);
            $data['statepath'] = $this->nut->get_upsd_conf('statepath', TRUE);
            $data['maxconn'] = $this->nut->get_upsd_conf('maxconn', TRUE);
            $data['certfile'] = $this->nut->get_upsd_conf('certfile', TRUE);
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }
        $this->page->view_form('ups_server/upsd_conf/settings', $data, lang('ups_server_ups_list'));
    }
}
