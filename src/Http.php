<?php

/**
 * 基于CURL的Http请求类
 * @author Coeus <r.anerg@gmail.com>
 */

namespace anerg\helper;

class Http {

    private static function init() {
        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            return $ch;
        } else {
            exception('服务器不支持CURL');
        }
    }

    private static function exec($ch) {
        $rsp = curl_exec($ch);
        if ($rsp !== false) {
            curl_close($ch);
            return $rsp;
        } else {
            $errorCode = curl_errno($ch);
            $errorMsg  = curl_error($ch);
            curl_close($ch);
            exception("curl出错，$errorMsg", $errorCode);
        }
    }

    public static function request($url, $data = null, $method = 'get') {
        $method = strtolower($method);
        return self::$method($url, $data);
    }

    public static function get($url, $data = null) {
        $ch  = self::init();
        $url = rtrim($url, '?');
        if (!is_null($data)) {
            $url .= '?' . http_build_query($data);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        return self::exec($ch);
    }

    public static function post($url, $data = null) {
        $ch = self::init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        if (!is_null($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }
        return self::exec($ch);
    }

    public static function postRaw($url, $raw) {
        $ch = self::init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $raw);
        return self::exec($ch);
    }

    public static function postRawSsl($url, $raw, $options) {
        $ch = self::init();
        if (!array_key_exists('cert_path', $options) || !array_key_exists('key_path', $options) || !array_key_exists('ca_path', $options)) {
            exception('证书文件路径不能为空');
        }
        curl_setopt($ch, CURLOPT_SSLCERT, $options['cert_path']);
        curl_setopt($ch, CURLOPT_SSLKEY, $options['key_path']);
        curl_setopt($ch, CURLOPT_CAINFO, $options['ca_path']);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $raw);
        return self::exec($ch);
    }

    public static function saveImage($url, $path, $filename = null) {
        $ch  = self::init();
        curl_setopt($ch, CURLOPT_URL, $url);
        $img = curl_exec($ch);
        if ($img !== false) {
            $file_info = curl_getinfo($ch);
            curl_close($ch);
        } else {
            $errorCode = curl_errno($ch);
            $errorMsg  = curl_error($ch);
            curl_close($ch);
            exception("获取头像出错，$errorMsg", $errorCode);
        }
        $content_type = explode('/', $file_info['content_type']);
        if (strtolower($content_type[0]) != 'image') {
            exception('下载地址文件不是图片');
        }

        $file_path = '/' . trim($path, '/') . '/';
        if (is_null($filename)) {
            $filename = md5($url);
        }
        $file_path .= $filename . '.' . end($content_type);
        return file_put_contents($file_path, $img);
    }

}
