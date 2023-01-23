<?php
namespace MdnWebp\Controller;

use Configuration;
use Link;
use PrestaShop\PrestaShop\Adapter\Entity\Db;
use PrestaShop\PrestaShop\Core\Domain\Product\Image\QueryResult\ProductImage;
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
                'name' => 'Categories Images',
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
        if(\Tools::isSubmit("quality")) {
            Configuration::updateValue("MDN_WEBP_QUALITY", \Tools::getValue('quality'));
            $this->addFlash("success", "Quality setting updated");
        }

        if(\Tools::isSubmit("generate_product")) {
            $product =  new \Product(\Tools::getValue('product'));
            $images =  $product->getImages(1);
            foreach ($images as $image) {
                $productImage = new \Image($image['id_image']);
                \ConvertToWebp::doForProductImage($productImage->getImgPath());
            }
            $this->addFlash("success", "Product Images Generated");
        }

        if(\Tools::isSubmit("generate_category")) {
            \ConvertToWebp::doForCategory( \Tools::getValue('category'));
            $this->addFlash("success", "Category Images Generated");
        }

        if(\Tools::isSubmit("generate")) {
            $supports = $this->getSupports();
            if(!empty($supports[\Tools::getValue("type")])) {
                $support = $supports[\Tools::getValue("type")];

                if(!is_array($support['path']))
                    $support['path'] = [$support['path']];

                $images = [];
                foreach ($support['path'] as $path) {
                    $destination = \ConvertToWebp::doForPath($path);
                    foreach ($destination as $item) {
                        $images[] = $item;
                    }
                }

                $this->addFlash("success", "Generated for ".$support['name']." : <ul>
                ".implode("", array_map(function ($v) { return "<li>".$v."</li>";}, $images))."
                 </ul>");
            }
            else
                $this->addFlash("error", "This support isn't compatible");
        }

        $categories = [];
        foreach (\Category::getCategories() as $sub_categories)
            $categories = array_merge($categories, $sub_categories);

        $products = (Db::getInstance()->executeS("SELECT pl.id_product, name FROM `" . _DB_PREFIX_ . "product` p JOIN `" . _DB_PREFIX_ . "product_lang` pl ON p.id_product = pl.id_product WHERE pl.id_lang = '1' ORDER BY id_product ASC"));

        $product_generate_url = Link::getUrlSmarty(array('entity' => 'sf', 'route' => 'webp_product',
            'sf-params' => array(
                'product' => $products[0]['id_product'],
            )));

        return $this->render('@Modules/mdn_webp/views/templates/admin/webp.html.twig', [
            'supports' => $this->getSupports(),
            'quality' => Configuration::get("MDN_WEBP_QUALITY", null, null, null, 80),
            'categories' => $categories,
            'products' => $products,
            'product_generate_url' => $product_generate_url
        ]);
    }

    public function generateProductImage($product) {
        $product_id = $product;
        $product =  new \Product($product, false, 1);
        $images =  $product->getImages(1);
        foreach ($images as $image) {
            $productImage = new \Image($image['id_image']);
            \ConvertToWebp::doForProductImage($productImage->getImgPath());
        }

        // next
        $next = (Db::getInstance()->getValue("SELECT p.id_product FROM `" . _DB_PREFIX_ . "product` p WHERE id_product > '".$product_id."' ORDER BY id_product ASC"));
        $next_url = null;
        if($next) {
            $next_url =  Link::getUrlSmarty(array('entity' => 'sf', 'route' => 'webp_product',
                'sf-params' => array(
                    'product' => $next,
                )));
        }

        return $this->json(["next" => $next_url, "name" => $product->name]);
    }
}