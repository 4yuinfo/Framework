<?php

/**
 *
 * @license
 *
 */

namespace Ntch\Framework\Http\Response;

class Header
{
    use \Ntch\Framework\Psr\Psr7\Header;

    /**
     * Set Response Header.
     *
     * @param string $name
     * @param string $value
     *
     * @return void
     */
    public function setHeader($name, $value)
    {
        $isName = $this->normalizationHeaderName($name);
        $isValue = $this->normalizationHeaderValue($value);
        if($isName && $isValue) {
            $name = strtolower(trim($name));
            $value = strtolower(trim($value));
            header('\'' . $name . '\':\'' . $value . '\'');
        } elseif (!$isName){
            die('【ERROR】Header name 【' . $name . '】must be an RFC 7230 compatible string.');
        } elseif (!$isValue) {
            die('【ERROR】Header value 【' . $value . '】 must be an RFC 7230 compatible string.');
        }
    }    
    
}