<?php

/*
  Copyright (C) <2011>  Vasyl Martyniuk <martyniuk.vasyl@gmail.com>

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Extended Model Class
 *
 * @package AAM
 * @subpackage Models
 * @author Vasyl Martyniuk <martyniuk.vasyl@gmail.com>
 * @version 1.2
 * @copyright Copyright © 2011-2012 Vasyl Martyniuk
 * @license GNU General Public License {@link http://www.gnu.org/licenses/}
 */
class mvb_Model_Pro {

    /**
     * Constructor
     *
     * @access public
     */
    public function __construct() {

        define('AAM_PREMIUM', 1);

        if (is_admin()) {
            add_filter(WPACCESS_PREFIX . 'restrict_limit', array($this, 'unblockLimit'));
            add_filter(WPACCESS_PREFIX . 'msar_restrict_limit', array($this, 'unblockLimit'));
        }
        add_filter(WPACCESS_PREFIX . 'default_action', array($this, 'defaultAction'), 10, 3);

        $this->registerPageCategory();
    }

    public function defaultAction($default, $action, $type) {

        if (is_admin()) {
            $config = mvb_Model_ConfigPress::getOption("backend.access.{$type}.default.{$action}");
        } else {
            $config = mvb_Model_ConfigPress::getOption("frontend.access.{$type}.default.{$action}");
        }

        return (is_null($config) ? $default : ($config == 'allow' ? TRUE : FALSE));
    }

    public function unblockLimit($limit) {

        return -1;
    }

    public function registerPageCategory() {

        if (mvb_Model_ConfigPress::getOption('aam.page_category', 'false') == 'true') {
            register_taxonomy('page_category', 'page', array(
                'hierarchical' => TRUE,
                'rewrite' => TRUE,
                'public' => TRUE,
                'show_ui' => TRUE,
                'show_in_nav_menus' => TRUE,
            ));
        }
    }

    public static function populateRestriction($type, $object, $id) {
        switch($object){
            case 'user':
                $result = self::populateUser($type, $id);
                break;

            case 'role':
                $result = self::populateRole($type, $id);
                break;

            default:
                break;
        }

        if (empty($result)){
            $result = self::populateDefault($type);
        }

        return $result;
    }

    protected static function populateUser($type, $id){
        $result = self::getAccessConfig("user.{$id}.", $type);
        if (empty($result)){
            $result = self::getAccessConfig("user.all.", $type);
        }

        return $result;
    }

     protected static function populateRole($type, $id){
        $result = self::getAccessConfig("role.{$id}.", $type);
        if (empty($result)){
            $result = self::getAccessConfig("role.all.", $type);
        }

        return $result;
    }

    protected static function populateDefault($type){
        return self::getAccessConfig('', $type);
    }

    protected static function getAccessConfig($prefix, $type){
        $result = array();
        if (mvb_Model_ConfigPress::getOption("frontend.{$prefix}access.post.default.list") == 'deny') {
            $result['frontend_post_list'] = 1;
        }
        if (mvb_Model_ConfigPress::getOption("backend.{$prefix}access.post.default.list") == 'deny') {
            $result['backend_post_list'] = 1;
        }
        if (mvb_Model_ConfigPress::getOption("frontend.{$prefix}access.post.default.read") == 'deny') {
            $result['frontend_post_read'] = 1;
        }
        if (mvb_Model_ConfigPress::getOption("backend.{$prefix}access.post.default.read") == 'deny') {
            $result['backend_post_read'] = 1;
        }
        if (mvb_Model_ConfigPress::getOption("frontend.{$prefix}access.post.default.delete") == 'deny') {
            $result['frontend_post_delete'] = 1;
        }
        if (mvb_Model_ConfigPress::getOption("backend.{$prefix}access.post.default.trash") == 'deny') {
            $result['backend_post_trash'] = 1;
        }
        if (mvb_Model_ConfigPress::getOption("backend.{$prefix}access.post.default.delete") == 'deny') {
            $result['backend_post_delete'] = 1;
        }
        if (mvb_Model_ConfigPress::getOption("frontend.{$prefix}access.post.default.edit") == 'deny') {
            $result['frontend_post_edit'] = 1;
        }
        if (mvb_Model_ConfigPress::getOption("backend.{$prefix}access.post.default.edit") == 'deny') {
            $result['backend_post_edit'] = 1;
        }
        if (mvb_Model_ConfigPress::getOption("frontend.{$prefix}access.post.default.publish") == 'deny') {
            $result['frontend_post_publish'] = 1;
        }
        if (mvb_Model_ConfigPress::getOption("backend.{$prefix}access.post.default.publish") == 'deny') {
            $result['backend_post_publish'] = 1;
        }
        if (mvb_Model_ConfigPress::getOption("frontend.{$prefix}access.post.default.comment") == 'deny') {
            $result['frontend_post_comment'] = 1;
        }
        if (mvb_Model_ConfigPress::getOption("backend.{$prefix}access.post.default.comment") == 'deny') {
            $result['backend_post_comment'] = 1;
        }
        if (mvb_Model_ConfigPress::getOption("frontend.{$prefix}access.post.default.exclude") == 'deny') {
            $result['frontend_post_exclude'] = 1;
        }

        switch ($type) {
            case 'taxonomy':
                if (mvb_Model_ConfigPress::getOption("frontend.{$prefix}access.taxonomy.default.list") == 'deny') {
                    $result['frontend_list'] = 1;
                }
                if (mvb_Model_ConfigPress::getOption("backend.{$prefix}access.taxonomy.default.list") == 'deny') {
                    $result['backend_list'] = 1;
                }
                if (mvb_Model_ConfigPress::getOption("frontend.{$prefix}access.taxonomy.default.browse") == 'deny') {
                    $result['frontend_browse'] = 1;
                }
                if (mvb_Model_ConfigPress::getOption("backend.{$prefix}access.taxonomy.default.browse") == 'deny') {
                    $result['backend_browse'] = 1;
                }
                if (mvb_Model_ConfigPress::getOption("backend.{$prefix}access.taxonomy.default.edit") == 'deny') {
                    $result['backend_edit'] = 1;
                }
                break;

            default:
                break;
        }

        return $result;
    }

}

?>