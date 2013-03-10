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
    function enable($item)
    {
        $this->load->library('ups_server/nut');
        $interface = $this->nut->get_upsd_interfaces($item);
        $interface['enabled'] = ($interface['enabled']) ? TRUE : FALSE;
        $this->nut->set_upsd_interfaces($interface);
        $this->page->set_status_updated();
        redirect('/ups_server/');
    }
    function add()
    {
        $this->_form('add');
    }
    function delete($item)
    {
        $this->load->library('ups_server/nut');
        $confirm_uri = '/app/ups_server/upsd_conf/upsd_conf_summary_edit/destroy/'.$item;
        $cancel_uri = '/app/ups_server/';
        $items = array($this->nut->get_upsd_interfaces($item, 'ip'));
        $this->page->view_confirm_delete($confirm_uri, $cancel_uri, $items);
    }
    function destroy($item)
    {
        $this->load->library('ups_server/nut');
        $this->nut->delete_upsd_interfaces($this->nut->get_upsd_interfaces($item, 'ip'));
        redirect('/ups_server/');
    }
    function _form($form_type, $item)
    {
        $this->lang->load('ups_server');
        $this->load->library('ups_server/nut');
        $form_ok = TRUE;
        if ($this->input->post('submit') && ($form_ok === TRUE)) {

            try {
                $interface['ip_old'] = $this->input->post('ip_old');
                //FIX: Add IP validation
                $interface['ip'] = $this->input->post('ip');
                //FIX: Add PORT validation, This is removing 'Optional: Default 3493' text.
                $interface['port'] = ($this->input->post('port') != 'Optional: Default 3493' ) ? $this->input->post('port') : '';
                $this->nut->set_upsd_interfaces($interface);
                if ($form_type === 'edit')
                {
                    $this->page->set_status_updated();
                    redirect('/ups_server/upsd_conf/upsd_conf_summary_edit/edit/'.$item);
                } elseif ($form_type === 'add') {
                    $this->page->set_status_added();
                    redirect('/ups_server/');
                }
            } catch (Exception $e) {
                $this->page->view_exception($e);
                return;
            }
        }        
        
        try {
            $data['form_type'] = $form_type;
            $data['dir'] = 'upsd_conf';
            $data['item'] = $item;
            
            //Set this as $data then set above values? hmmmm, removes 3 lines of code.
            $interface = $this->nut->get_upsd_interfaces($item);
            
            $data['ip_validate'] = $interface['ip_validate'];
            $data['ip'] = $interface['ip'];
            $data['port'] = $interface['port'];
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }
        $this->page->view_form('ups_server/upsd_conf/summary_edit', $data, lang('ups_server_ups_list'));
    }
}