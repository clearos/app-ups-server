<?php
use \Exception as Exception;

class settings extends ClearOS_Controller
{
    /**
     * UPS server settings view.
     *
     * @return view
     */
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
        
        //$this->form_validation->set_policy('chroot', 'ups_server/nut', 'validate_param', TRUE);
        //$form_ok = $this->form_validation->run();
        $form_ok = TRUE;
        
        if ($this->input->post('submit') && ($form_ok === TRUE)) {

            try {
                $this->nut->set_ups_conf($this->input->post('chroot'), 'chroot', FALSE);
                $this->nut->set_ups_conf($this->input->post('driverpath'), 'driverpath', FALSE);
                $this->nut->set_ups_conf($this->input->post('maxstartdelay'), 'maxstartdelay', FALSE);
                $this->nut->set_ups_conf($this->input->post('pollinterval'), 'pollinterval', FALSE);
                $this->nut->set_ups_conf($this->input->post('user'), 'user', FALSE);
                $this->page->set_status_updated();
            } catch (Exception $e) {
                $this->page->view_exception($e);
                return;
            }
        }        
        
        try {
            $data['form_type'] = $form_type;
            $data['dir'] = 'ups_conf';
            $data['chroot'] = $this->nut->get_ups_conf('chroot', FALSE);
            $data['driverpath'] = $this->nut->get_ups_conf('driverpath', FALSE);
            $data['maxstartdelay'] = $this->nut->get_ups_conf('maxstartdelay', FALSE);
            $data['pollinterval'] = $this->nut->get_ups_conf('pollinterval', FALSE);
            $data['user'] = $this->nut->get_ups_conf('user', FALSE);
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }
        $this->page->view_form('ups_server/ups_conf/settings', $data, lang('ups_server_ups_list'));
    }
}
