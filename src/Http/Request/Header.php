<?php

/**
 *
 * @license
 *
 */

namespace Ntch\Framework\Http\Request;

use Ntch\Framework\Http\Psr7;

class Header extends Psr7
{

    /**
     * get Globals information.
     *
     * @return \stdClass
     */
    public function getHeader(): \stdClass
    {
        $header = new \stdClass();
        $header->headers = $this->message->getHeaders();
        return $header;
    }

    /**
     * Set Header.
     *
     * @return void
     */
    public function setHeaders()
    {
        foreach (getallheaders() as $name => $value) {
            $this->message->withHeader($name, $value);
        }
    }

}