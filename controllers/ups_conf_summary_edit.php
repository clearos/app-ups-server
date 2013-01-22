<?php
use \Exception as Exception;

class ups_conf_summary_edit extends ClearOS_Controller
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
        $this->_item('');
    }
    function delete($item)
    {
    }
    function update($item)
    {
    }
    function _form($form_type, $item)
    {
        $this->lang->load('ups_server');
        $this->load->library('ups_server/nut');

        if ($this->input->post('submit') && ($form_ok === TRUE)) {

            try {
                //TEST REDIRECT
                redirect('/ups_server/nut_conf/summary');
            } catch (Exception $e) {
                $this->page->view_exception($e);
                return;
            }
        }        
        
        try {
            $data['form_type'] = $form_type;
            $data['ups_conf_name'] = $this->nut->get_ups_list($item, 'name');
            $data['ups_conf_driver'] = $this->nut->get_ups_list($item, 'driver');
            $data['ups_conf_port'] = $this->nut->get_ups_list($item, 'port');
            $data['ups_conf_sdorder'] = $this->nut->get_ups_list($item, 'sorder');
            $data['ups_conf_desc'] = $this->nut->get_ups_list($item, 'desc');
            $data['ups_conf_nolock'] = $this->nut->get_ups_list($item, 'nolock');
            $data['ups_conf_ignorelb'] = $this->nut->get_ups_list($item, 'ignorelb');
            $data['ups_conf_maxstartdelay'] = $this->nut->get_ups_list($item, 'maxstartdelay');
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }
        $this->page->view_form('ups_server/ups_conf/summary_edit', $data, lang('ups_server_ups_list'));
    }
    function _item($form_type)
    {
        $this->lang->load('ups_server');
        $form_ok = $this->form_validation->run();

        if ($this->input->post('submit') && $form_ok) {
            try {
                if ($form_type === 'edit') {
                    
                    $this->page->set_status_updated();
                } else {

                    $this->page->set_status_added();
                }

                //TEST REDIRECT
                redirect('/ups_server/nut_conf/summary');
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

        $this->page->view_form('ups_server/ups_conf/summary_edit', $data, lang('ups_server_settings'));
    }
}
