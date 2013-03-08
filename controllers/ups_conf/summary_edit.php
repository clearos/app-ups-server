<?php
use \Exception as Exception;

class summary_edit extends ClearOS_Controller
{
    function index()
    {
        $this->_form('view');
    }
    function edit($ups)
    {
        $this->_form('edit', $ups);
    }
    function add()
    {
        $this->_form('add');
    }
    function delete($ups)
    {
        $confirm_uri = '/app/ups_server/ups_conf/summary_edit/destroy/' . $ups;
        $cancel_uri = '/app/ups_server/ups_conf/summary_view';
        $items = array($ups);

        $this->page->view_confirm_delete($confirm_uri, $cancel_uri, $items);
    }
    function destroy($ups)
    {
        $this->load->library('ups_server/nut');
        $command['command'] = 'delete';
        $this->nut->update_ups_commands_list($ups, $command);
        redirect('/ups_server');
    }
    function _form($form_type, $ups)
    {
        $this->lang->load('ups_server');
        $this->load->library('ups_server/nut');

        //Form Validation
        $form_ok = TRUE;
        
        if ($this->input->post('submit') && ($form_ok === TRUE)) {

            try {
                $command['name'] = $this->input->post('name');
                $command['driver'] = $this->input->post('driver');
                $command['port'] = $this->input->post('port');
                $command['sdorder'] = $this->input->post('sdorder');
                $command['desc'] = $this->input->post('desc');
                $command['nolock'] = $this->input->post('nolock');
                $command['ignorelb'] = $this->input->post('ignorelb');
                $command['maxstartdelay'] = $this->input->post('maxstartdelay');
                $command['command'] = NULL;
                $command['default'] = NULL;
                $command['override'] = NULL;
                $this->nut->update_ups_commands_list($this->input->post('name'), $command);
                if ($form_type === 'edit') {
                    $this->page->set_status_updated();
                    redirect('/ups_server/ups_conf/summary_edit/edit/'.$ups);
                } elseif ($form_type === 'add') {
                    $this->page->set_status_added();
                    
                }
                redirect('/ups_server');
            } catch (Exception $e) {
                $this->page->view_exception($e);
                return;
            }
        }
        $data['form_type'] = $form_type;
        $data['dir'] = 'ups_conf';        
        if ($form_type === 'edit')
        {
            try {
                $data['ups'] = $ups;
                $data['name'] = $this->nut->get_ups_list($ups, 'name');
                $data['driver'] = $this->nut->get_ups_list($ups, 'driver');
                $data['port'] = $this->nut->get_ups_list($ups, 'port');
                $data['sdorder'] = $this->nut->get_ups_list($ups, 'sdorder');
                $data['desc'] = $this->nut->get_ups_list($ups, 'desc');
                $data['nolock'] = $this->nut->get_ups_list($ups, 'nolock');
                $data['ignorelb'] = $this->nut->get_ups_list($ups, 'ignorelb');
                $data['maxstartdelay'] = $this->nut->get_ups_list($ups, 'maxstartdelay');
            } catch (Exception $e) {
                $this->page->view_exception($e);
                return;
            }
        }
        $this->page->view_form('ups_server/ups_conf/summary_edit', $data, lang('ups_server_ups_list'));
    }
}
