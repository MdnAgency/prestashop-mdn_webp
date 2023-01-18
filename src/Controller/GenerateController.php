<?php
namespace MdnWebp\Controller;

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\HttpFoundation\File\UploadedFile;

require_once _PS_MODULE_DIR_ . '/mdn_webp/utils/ConvertToWebp.php';
require_once _PS_MODULE_DIR_ . '/mdn_webp/utils/SmartyExtension.php';
require_once _PS_MODULE_DIR_ . '/mdn_webp/utils/InstallFixs.php';

class GenerateController extends FrameworkBundleAdminController
{
    // you can use symfony DI to inject services
    public function __construct()
    {
    }

    private function getSupports() {
        $supports = [
            'category' => [
                'name' => 'Catégorie',
                'path' => [_PS_IMG_DIR_."c".DIRECTORY_SEPARATOR."*.{jpg,JPG,jpeg,JPEG,png,PNG}"]
            ],
            'image_slider' => [
                'name' => 'Image Slider',
                'module' => 'ps_imageslider',
                'path' => _PS_MODULE_DIR_."ps_imageslider".DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR."*.{jpg,JPG,jpeg,JPEG,png,PNG}"
            ]
        ];

        $supports = array_filter($supports, function ($v) {
            if(empty($v['module']))
                return true;
            else {
                return \Module::isEnabled($v['module']);
            }
        });

        return $supports;
    }

    public function showConfigPage()
    {
        return $this->render('@Modules/mdn_webp/views/templates/admin/webp.html.twig', [
            'supports' => $this->getSupports()
        ]);
    }

    public function generateCategoryImage($id) {
        $destination = \ConvertToWebp::webpImage(_PS_IMG_DIR_."c".DIRECTORY_SEPARATOR."$id",  80, false, true);
        return $this->json($destination);
    }


    public function generateAllCategoriesImages() {
        $destinations = [];
        $folder = glob(_PS_IMG_DIR_."c".DIRECTORY_SEPARATOR."*.{jpg,JPG,jpeg,JPEG,png,PNG}", GLOB_BRACE);
        foreach ($folder as $value) {
            $file = basename($value);
            $destination = \ConvertToWebp::webpImage(_PS_IMG_DIR_."c".DIRECTORY_SEPARATOR."$file",  80, false, true);
            $destinations[] = $destination;
        }
        return $this->json($destinations);
    }

}