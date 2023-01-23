<?php
class ConvertToWebp {
    // source : https://www.php.net/manual/en/function.imagewebp.php#126837
    public static function webpImage($source, $quality = 100, $removeOld = false, $webp_folder = false)
    {
        // infos
        $dir = pathinfo($source, PATHINFO_DIRNAME);
        $name = pathinfo($source, PATHINFO_FILENAME);

        //dossier
        if($webp_folder) {
            mkdir($dir . DIRECTORY_SEPARATOR . ($webp_folder ? "webp" . DIRECTORY_SEPARATOR : ""), 0777);
        }

        // image
        $destination = $dir . DIRECTORY_SEPARATOR . ($webp_folder ? "webp" . DIRECTORY_SEPARATOR : "") . $name .  '.webp';
        $info = getimagesize($source);
        $isAlpha = false;
        if ($info['mime'] == 'image/jpeg')
            $image = imagecreatefromjpeg($source);
        elseif ($isAlpha = $info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($source);
        } elseif ($isAlpha = $info['mime'] == 'image/png') {
            $image = imagecreatefrompng($source);
        } else {
            return $source;
        }
        if ($isAlpha) {
            imagepalettetotruecolor($image);
            imagealphablending($image, true);
            imagesavealpha($image, true);
        }
        imagewebp($image, $destination, $quality);

        if ($removeOld)
            unlink($source);

        return $destination;
    }

    public static function doForCategory($category)
    {
        $str = '{'.$category.','.$category.'-*}';
        $destinations = [];
        $folder = glob(_PS_IMG_DIR_ . "c" . DIRECTORY_SEPARATOR . "$str.{jpg,JPG,jpeg,JPEG,png,PNG}", GLOB_BRACE);
        foreach ($folder as $value) {
            $file = basename($value);
            $destination = self::webpImage(_PS_IMG_DIR_ . "c" . DIRECTORY_SEPARATOR . "$file", Configuration::get("MDN_WEBP_QUALITY", null, null, null, 80), false, false);
            $destinations[] = $destination;
        }
        return ($destinations);
    }

    public static function doForPath($path)
    {
        $destinations = [];
        $folder = glob($path, GLOB_BRACE);
        foreach ($folder as $value) {
            $file = basename($value);
            $directory = dirname($path);
            $destination = self::webpImage($directory . DIRECTORY_SEPARATOR . "$file", Configuration::get("MDN_WEBP_QUALITY", null, null, null, 80), false, false);
            $destinations[] = $destination;
        }
        return ($destinations);
    }

    public static function doForProductImage($path)
    {
        $destinations = [];
        $path_without_last = implode("/", array_slice(explode("/", $path), 0, -1));
        $folder = glob(_PS_IMG_DIR_ . "p" . DIRECTORY_SEPARATOR . $path . "*.{jpg,JPG,jpeg,JPEG,png,PNG}", GLOB_BRACE);
        foreach ($folder as $value) {
            $file = basename($value);
            $destination = self::webpImage(_PS_IMG_DIR_ . "p" . DIRECTORY_SEPARATOR . $path_without_last . DIRECTORY_SEPARATOR .   "$file", Configuration::get("MDN_WEBP_QUALITY", null, null, null, 80), false, false);
            $destinations[] = $destination;
        }
        return ($destinations);
    }
}