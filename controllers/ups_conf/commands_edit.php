p<?php
use \Exception as Exception;

class commands_edit extends ClearOS_Controller
{
    function index()
    {
        $this->_form('view', NULL, NULL);
    }
    function edit($item, $ups)
    {
        $this->_form('edit', $item, $ups);
    }
    function add($ups)
    {
        $this->_form('add', '', $ups);
    }
    function add_custom($ups)
    {
        $this->_form('add_custom', NULL, $ups);
    }
    function delete($command = NULL, $ups)
    {
        $confirm_uri = '/app/ups_server/ups_conf/commands_edit/destroy/'.$command.'/'.$ups;
        $cancel_uri = '/app/ups_server/ups_conf/commands_view/view/'.$ups;
        $items = array($command);

        $this->page->view_confirm_delete($confirm_uri, $cancel_uri, $items);
    }
    function destroy($command = NULL, $ups)
    {
        $this->load->library('ups_server/nut');

        try {
            $this->nut->set_custom_commands_list('delete', $command);

            $this->page->set_status_deleted();
            redirect('/ups_server/ups_conf/commands_view/view/'.$ups);
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }
    }
    function _form($form_type, $item, $ups)
    {
        $this->lang->load('ups_server');
        $this->load->library('ups_server/nut');
        
        //Form Validation
        $form_ok = TRUE;
        
        if ($this->input->post('submit') && ($form_ok === TRUE)) {

            try {
                if ($form_type === 'edit')
                {
                    $ups_name = $this->nut->get_ups_list($ups, 'name');
                    $this->nut->update_ups_commands_list($ups_name, $this->input->post('command'), $this->input->post('default'), $this->input->post('override'));
                    $this->page->set_status_updated();
                    redirect('/ups_server/ups_conf/commands_view/view/'.$ups);
                } elseif ($form_type === 'add_custom') {
                    $this->nut->set_custom_commands_list('add', $this->input->post('command'));
                    $this->page->set_status_added();
                }
                //redirect('/ups_server/ups_conf_commands_view/'.$ups);
            } catch (Exception $e) {
                $this->page->view_exception($e);
                return;
            }
        }
        $data['form_type'] = $form_type;
        $data['dir'] = 'ups_conf';
        $data['ups'] = $ups;        
        if ($form_type === 'edit')
        {
            try {
                $data['command'] = $this->nut->get_ups_commands_list($ups, $item, 'command');
                $data['default'] = $this->nut->get_ups_commands_list($ups, $item, 'default');
                $data['override'] = $this->nut->get_ups_commands_list($ups, $item, 'override');
            } catch (Exception $e) {
                $this->page->view_exception($e);
                return;
            }
        }
        $this->page->view_form('ups_server/ups_conf/commands_edit', $data, 'ups_server_ups_list');
    }
}
