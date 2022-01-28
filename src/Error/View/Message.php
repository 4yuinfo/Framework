<?php

/**
 *
 * @license
 *
 */

namespace Ntch\Framework\Error\View;

use Ntch\Framework\Http\Response\Header;

class Message
{

    /**
     * @var Header
     */
    private $header;

    /**
     * Construct
     */
    public function __construct($message)
    {
        $this->header = new Header();
        $this->setHeader();
        $this->show($message);
    }

    /**
     * set response header.
     *
     * @return void
     */
    private function setHeader()
    {
        $this->header->setHeader('content-type', 'application/json');
    }

    /**
     * show response message.
     *
     * @param string $message
     *
     * @return void
     */
    private function show($message)
    {
        echo $message;
        exit();
    }


}