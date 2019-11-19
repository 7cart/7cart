<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

class Deserializer
{

    private function _changeKeys($arr, &$_new = []) {

        foreach ($arr as $k => &$v) {
            if (is_array($arr[$k])){
                $_new[$k]= [];
                $this->_changeKeys($arr[$k], $_new[$k]);
            } else {
                if (strpos($k, '-') !== false) {
                    $key = lcfirst(implode('', array_map('ucfirst', explode('-', $k))));
                    $_new[$key] = $v;
                } else {
                    $_new[$k] = $v;
                }
            }

        }

        return $_new;
    }

    public function deserializeRequest(Request $request)
    {
        $content = $request->getContent();
        $params = [];

        if (!empty($content))
        {
            $params = $this->_changeKeys(json_decode($content, true));

        }

        return $params;
    }

    public function deserializeRequestAttributes(Request $request)
    {
       $result = [];

       $data = $this->deserializeRequest($request);
       if (isset($data['data']) && isset($data['data']['attributes'])){
           $result = $data['data']['attributes'];
       }

       return $result;
    }

}

