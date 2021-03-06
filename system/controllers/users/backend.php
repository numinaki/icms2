<?php

class backendUsers extends cmsBackend {

    public $useSeoOptions = true;
    public $useItemSeoOptions = true;
    protected $useOptions = true;

    public $useDefaultOptionsAction = true;
    public $useDefaultPermissionsAction = true;

    public function actionIndex(){
        $this->redirectToAction('fields');
    }

    public function getBackendMenu(){
        return array(
            array(
                'title' => LANG_USERS_CFG_FIELDS,
                'url' => href_to($this->root_url, 'fields')
            ),
            array(
                'title' => LANG_USERS_CFG_TABS,
                'url' => href_to($this->root_url, 'tabs')
            ),
            array(
                'title' => LANG_OPTIONS,
                'url' => href_to($this->root_url, 'options')
            ),
            array(
                'title' => LANG_PERMISSIONS,
                'url' => href_to($this->root_url, 'perms', 'users')
            ),
            array(
                'title' => LANG_USERS_CFG_MIGRATION,
                'url' => href_to($this->root_url, 'migrations')
            ),
            array(
                'title' => LANG_USERS,
                'url' => href_to('admin', 'users')
            )
        );
    }

    public function validate_unique_field($value){
        return !$this->cms_core->db->isFieldExists('{users}', $value);
    }

}
