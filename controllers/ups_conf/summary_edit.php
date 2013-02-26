<?php
use \Exception as Exception;

class summary_edit extends ClearOS_Controller
{
    function index()
    {
        $this->_form('view');
    }
    function edit($item)
    {
        $this->_form('edit', $item);
    }
    function add()
    {
        $this->_form('add');
    }
    function delete()
    {
    }
    function _form($form_type, $item)
    {
        $this->lang->load('ups_server');
        $this->load->library('ups_server/nut');

        //Form Validation
        $form_ok = TRUE;
        
        if ($this->input->post('submit') && ($form_ok === TRUE)) {

            try {
                //ADD
                $this->nut->set_ups_commands_list($this->input->post('command'));
            } catch (Exception $e) {
                $this->page->view_exception($e);
                return;
            }
        }        
        if ($form_type === 'edit')
        {
            try {
                $data['form_type'] = $form_type;
                $data['dir'] = 'ups_conf';
                $data['name'] = $this->nut->get_ups_list($item, 'name');
                $data['driver'] = $this->nut->get_ups_list($item, 'driver');
                $data['port'] = $this->nut->get_ups_list($item, 'port');
                $data['sdorder'] = $this->nut->get_ups_list($item, 'sorder');
                $data['desc'] = $this->nut->get_ups_list($item, 'desc');
                $data['nolock'] = $this->nut->get_ups_list($item, 'nolock');
                $data['ignorelb'] = $this->nut->get_ups_list($item, 'ignorelb');
                $data['maxstartdelay'] = $this->nut->get_ups_list($item, 'maxstartdelay');
            } catch (Exception $e) {
                $this->page->view_exception($e);
                return;
            }
        }
        $this->page->view_form('ups_server/ups_conf/summary_edit', $data, lang('ups_server_ups_list'));
    }
}
