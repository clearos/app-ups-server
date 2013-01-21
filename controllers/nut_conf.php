<?php
/**
 * UPS Server controller.
 *
 * @category   Apps
 * @package    Accounts
 * @subpackage Controllers
 * @author     UWS <UWS@Shaw.ca>
 * @copyright  2011 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/ups_server/
 */
 
///////////////////////////////////////////////////////////////////////////////
//
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
//
///////////////////////////////////////////////////////////////////////////////

/**
 * UPS Server controller.
 *
 * @category   Apps
 * @package    UPS_Server
 * @subpackage Controllers
 * @author     UWS <UWS@Shaw.ca>
 * @copyright  2011 ClearFoundation
 * @license    http://www.gnu.org/copyleft/gpl.html GNU General Public License version 3 or later
 * @link       http://www.clearfoundation.com/docs/developer/apps/ups_server/
 */
class Nut_Conf extends ClearOS_Controller
{
    /**
     * UPS server summary view.
     *
     * @return view
     */
    function index()
    {
        $this->_form('view');
    }
    
    /**
     * Edit NUT Conf entry view.
     *
     * @return view
     */
    function edit()
    {
        $this->_form('edit');
    }
     /**
     * UPS server summary view.
     *
     * @return view
     */
    function view()
    {
        $this->_form('view');
    }
    /**
     * UPS server entry common add/edit form handler.
     *
     * @param string $form_type form type
     *
     * @return view
     */
    function _form($form_type)
    {
        $this->lang->load('ups_server');
        $this->load->library('ups_server/nut');
        
        $this->form_validation->set_policy('server_mode', 'ups_server/nut', 'validate_server_mode', TRUE);
        $form_ok = $this->form_validation->run();
        
        if ($this->input->post('submit') && ($form_ok === TRUE)) {

            try {
                $this->nut->set_server_mode($this->input->post('server_mode'));
                $this->nut->set_server_upsd($this->input->post('server_upsd'));
                $this->nut->set_server_upsmon($this->input->post('server_upsmon'));
                $this->nut->set_server_poweroff_wait($this->input->post('server_poweroff_wait'));
                $this->page->set_status_updated();
                redirect('/ups_server/nut_conf/summary');
            } catch (Exception $e) {
                $this->page->view_exception($e);
                return;
            }
        }       
        
        try {
            $data['form_type'] = $form_type;
            $data['server_mode'] = $this->nut->get_server_mode();
            $data['show_options'] = TRUE;
            $data['server_upsd'] = $this->nut->get_server_upsd();
            $data['server_upsmon'] = $this->nut->get_server_upsmon();
            $data['server_poweroff_wait'] = $this->nut->get_server_poweroff_wait();
        } catch (Exception $e) {
            $this->page->view_exception($e);
            return;
        }
        $this->page->view_form('ups_server/nut_conf/summary', $data, lang('base_settings'));
    }
}
