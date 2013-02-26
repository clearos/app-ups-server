<?php
use \Exception as Exception;

class summary_view extends ClearOS_Controller
{
    function index()
    {
        $this->_form('view');
    }
    function view()
    {
        $this->_form('view');
    }
    function _form($form_type)
    {
        $this->lang->load('ups_server');
        $this->load->library('ups_server/nut');

        try {
            $data['form_type'] = $form_type;
            $data['dir'] = 'ups_conf';
            $data['ups_conf_list'] = $this->nut->get_ups_list();
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }
        $this->page->view_form('ups_server/ups_conf/summary_view', $data, lang('ups_server_ups_list'));
    }
}
