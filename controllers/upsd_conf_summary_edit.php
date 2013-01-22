<?php
use \Exception as Exception;

class upsd_conf_summary_edit extends ClearOS_Controller
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
            $data['upsd_conf_ip_validate'] = $this->nut->get_upsd_interfaces($item, 'validate');
            $data['upsd_conf_ip'] = $this->nut->get_upsd_interfaces($item, 'ip');
            $data['upsd_conf_port'] = $this->nut->get_upsd_interfaces($item, 'port');
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }
        $this->page->view_form('ups_server/upsd_conf/summary_edit', $data, lang('ups_server_ups_list'));
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

        $this->page->view_form('ups_server/upsd_conf/summary_edit', $data, lang('ups_server_server_interfaces'));
    }
}
