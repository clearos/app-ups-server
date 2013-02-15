<?php
use \Exception as Exception;

class ups_conf_settings extends ClearOS_Controller
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
        
        $this->form_validation->set_policy('ups_conf_chroot', 'ups_server/nut', 'validate_param', TRUE);
        $form_ok = $this->form_validation->run();
        
        if ($this->input->post('submit') && ($form_ok === TRUE)) {

            try {
                $this->nut->set_ups_conf($this->input->post('ups_conf_chroot'), 'chroot', FALSE);
                $this->nut->set_ups_conf($this->input->post('ups_conf_driverpath'), 'driverpath', FALSE);
                $this->nut->set_ups_conf($this->input->post('ups_conf_maxstartdelay'), 'maxstartdelay', FALSE);
                $this->nut->set_ups_conf($this->input->post('ups_conf_pollinterval'), 'pollinterval', FALSE);
                $this->nut->set_ups_conf($this->input->post('ups_conf_user'), 'user', FALSE);
                $this->page->set_status_updated();
            } catch (Exception $e) {
                $this->page->view_exception($e);
                return;
            }
        }        
        
        try {
            $data['form_type'] = $form_type;
            $data['ups_conf_chroot'] = $this->nut->get_ups_conf('chroot', FALSE);
            $data['ups_conf_driverpath'] = $this->nut->get_ups_conf('driverpath', FALSE);
            $data['ups_conf_maxstartdelay'] = $this->nut->get_ups_conf('maxstartdelay', FALSE);
            $data['ups_conf_pollinterval'] = $this->nut->get_ups_conf('pollinterval', FALSE);
            $data['ups_conf_user'] = $this->nut->get_ups_conf('user', FALSE);
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }
        $this->page->view_form('ups_server/ups_conf/settings', $data, lang('ups_server_ups_list'));
    }
}
