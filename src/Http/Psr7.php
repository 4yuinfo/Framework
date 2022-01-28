<?php

/**
 *
 * @license
 *
 */

namespace Ntch\Framework\Http;

use Ntch\Framework\Psr\Psr7\Message;
use Ntch\Framework\Psr\Psr7\Request;
use Ntch\Framework\Psr\Psr7\Response;
use Ntch\Framework\Psr\Psr7\ServerRequest;
use Ntch\Framework\Psr\Psr7\Stream;
use Ntch\Framework\Psr\Psr7\UploadedFile;
use Ntch\Framework\Psr\Psr7\Uri;

class Psr7
{

    /**
     * @var Message
     */
    protected $message;

    /**
     * @var Request
     */
    public $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var ServerRequest
     */
    protected $serverRequest;

    /**
     * @var Stream
     */
    protected $stream;

    /**
     * @var UploadedFile
     */
    protected $uploadedFile;

    /**
     * @var Uri
     */
    public $uri;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->message = new Message();
        $this->request = new Request();
        $this->response = new Response();
        $this->serverRequest = new ServerRequest();
        $this->stream = new Stream();
        $this->uploadedFile = new UploadedFile();
        $this->uri = new Uri();
    }

}