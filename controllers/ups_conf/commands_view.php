<?php
use \Exception as Exception;

class commands_view extends ClearOS_Controller
{
    function index()
    {
        $this->_form('view');
    }
    function view($ups)
    {
        $this->_form('view', $ups);
    }
    function edit($ups)
    {
        $this->_form('edit', $ups);
    }
    function _form($form_type, $ups)
    {
        $this->lang->load('ups_server');
        $this->load->library('ups_server/nut');

        if ($this->input->post('submit') && ($form_ok === TRUE)) {

            try {
                //ADD
            } catch (Exception $e) {
                $this->page->view_exception($e);
                return;
            }
        }        
        
        try {
            $data['form_type'] = $form_type;
            $data['dir'] = 'ups_conf';
            $data['ups'] = $ups;
            $data['ups_commands_list'] = $this->nut->get_ups_commands_list($ups);
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }
        $this->page->view_form('ups_server/ups_conf/commands_view', $data, lang('ups_server_ups_list'));
    }
}
