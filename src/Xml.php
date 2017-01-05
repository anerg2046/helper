<?php

namespace anerg\helper;

class Xml {

    public static function xmlToArr($xml) {
        return (array) simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
    }

    public static function arrToXml($array) {
        if (!is_array($array) || count($array) <= 0) {
            exception("无法转为XML");
        }
        $xml = "<xml>";
        foreach ($array as $key => $val) {
            if (is_numeric($val)) {
                $xml.="<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml.="<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }

}
