<?php
require 'vendor/autoload.php';

if (!defined('_PS_VERSION_')) {
    exit;
}
require_once _PS_MODULE_DIR_ . '/mdn_webp/utils/SmartyExtension.php';
require_once _PS_MODULE_DIR_ . '/mdn_webp/utils/ConvertToWebp.php';
require_once _PS_MODULE_DIR_ . '/mdn_webp/utils/InstallFixs.php';
class Mdn_webp extends Module  {
    public $tabs = [
        [
            'name' => [
                'en' => 'WebP'
            ],
            'class_name' => 'WebP',
            'parent_class_name' => 'IMPROVE',
            'route_name' => 'webp_home'
        ],
    ];

    public function __construct()
    {
        $this->displayName = "MDN - WebP Support";
        $this->name = 'mdn_webp';
        $this->author = 'Maison du Net - Loris';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->bootstrap = true;
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7.7.0', 'max' => _PS_VERSION_);
        parent::__construct();
    }

    public function install()
    {
        return parent::install()
            && $this->registerHook("actionDispatcher")
            && $this->registerHook("actionWatermark")
            && $this->registerHook("actionAfterCreateCategoryFormHandler")
            && $this->registerHook("actionAfterUpdateCategoryFormHandler")
            && InstallFixs::installHtaccess()
            && InstallFixs::addWebPSupportImg();
    }


    public function hookActionWatermark($params) {
        $image = new Image($params['id_image']);
        $url = ConvertToWebp::doForProductImage($image->getImgPath());
    }

    public function hookActionAfterUpdateCategoryFormHandler($params) {
        $category_id = $params['id'];
        ConvertToWebp::doForCategory($category_id);
    }

    public function hookActionAfterCreateCategoryFormHandler($params) {
        $category_id = $params['id'];
        ConvertToWebp::doForCategory($category_id);
    }

    public function uninstall()
    {
        return parent::uninstall()
            && InstallFixs::uninstallHtaccess();
    }

    public function hookActionDispatcher()
    {
        $this->context->smarty->registerPlugin('modifier', 'webp', array('SmartyExtension', 'webp'));
    }
}
