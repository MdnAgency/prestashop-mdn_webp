<?php
class InstallFixs {
    static function installHtaccess() {
        // actual content
        $file = file_get_contents(_PS_ROOT_DIR_."/.htaccess");
        $htaccess_addon = file_get_contents(_PS_MODULE_DIR_."/mdn_webp/file/htaccess_addon.txt");

        // has MDN WebP Support
        if(preg_match("{(# ~~ mdn_webp_start)}", $file)) {
            return true;
        }
        else {
            $file_handle = fopen(_PS_ROOT_DIR_."/.htaccess", 'w');
            fwrite($file_handle, $htaccess_addon."\n\r". $file);
            fclose($file_handle);
            return true;
        }

        return false;
    }

    static function uninstallHtaccess() {
        $file = file_get_contents(_PS_ROOT_DIR_."/.htaccess");
        $file = preg_replace('/'.preg_quote('# ~~ mdn_webp_start').'[\s\S]+?'.preg_quote('# ~~ mdn_webp_end').'/', '', $file);
        $file_handle = fopen(_PS_ROOT_DIR_."/.htaccess", 'w');
        fwrite($file_handle,  $file);
        fclose($file_handle);
        return true;
    }

    static function addWebPSupportImg() {
        $file = file_get_contents(_PS_ROOT_DIR_."/img/.htaccess");
        $file = str_replace("|ico)", "|ico|webp)", $file);
        $file_handle = fopen(_PS_ROOT_DIR_."/img/.htaccess", 'w');
        fwrite($file_handle,  $file);
        fclose($file_handle);
        return true;
    }
}