<?php

/**
 *
 * @license
 *
 */

namespace Ntch\Framework\Http\Response;

class Api
{

    /**
     * Output header Content-Type : application/json
     *
     * @param string $code
     * @param array $data
     * @param string $encoding
     *
     * @return void
     */
    public function api(string $code, array $data = null, $encoding = 'UTF-8')
    {
        $json['requestId'] = 'ntch';
        if($this->apiCodeCheck($code)) {
            $json['code'] = $code;
            $json['message'] = $this->apiCodeMessage($code);
        } else {
            $json['code'] = '4110';
            $json['message'] = 'Code is not Exist';
        }
        if(is_null($data)){
            $json['result'] = null;
        } else{
            $json['result']['total'] = count($data);
            $json['result']['data'] = $data;
        }
        $apiJson = json_encode($json, JSON_FORCE_OBJECT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        echo $apiJson;
    }

    /**
     * Check json code
     *
     * @param string $code
     *
     * @return boolean
     */
    public function apiCodeCheck(string $code): bool
    {
        $codes = array('1000');
        return in_array($code, $codes);
    }

    /**
     * Get json message
     *
     * @param string $code
     *
     * @return string
     */
    public function apiCodeMessage(string $code): string
    {
        $codes = array('1000' => 'Complete Request');
        return $codes[$code];
    }

}