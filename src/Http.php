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

}
