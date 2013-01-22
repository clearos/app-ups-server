<?php
use \Exception as Exception;

class ups_conf_commands_view extends ClearOS_Controller
{
    function index()
    {
        $this->_form('view');
    }
    function edit($item)
    {
        $this->_form('edit', $item);
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
            // EDIT SHOULD OUTPUT AN ITEM TO RETURN BACK... ONLY RETURNING FULL LIST... NEED A FUNCTION TO RETURN UPS COMMANDS.
            $data['ups_commands_list'] = $this->nut->get_ups_commands_list();
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }
        $this->page->view_form('ups_server/ups_conf/commands_view', $data, lang('ups_server_ups_list'));
    }
}
