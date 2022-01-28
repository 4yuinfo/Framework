<?php

/**
 *
 * @license
 *
 */

namespace Ntch\Framework\WebRestful\Controllers;

use Ntch\Framework\WebRestful\Controllers\Base as ControllerBase;
use Ntch\Framework\WebRestful\Models\Base as ModelBase;

class Controller
{

    public function __construct()
    {
        // controller
        $controllerBase = new ControllerBase();
        $request = new \stdClass();

        $request->uuid = $controllerBase->getUuid();
        $request->method = $controllerBase->getMethod();
        $request->uri = $controllerBase->getUri();
        $request->query = $controllerBase->getQuery();
        $request->input = $controllerBase->getInput();
        $request->attribute = $controllerBase->getAttribute();
        $request->authorization = $controllerBase->getAuthorization();
        $request->cookies = $controllerBase->getCookie();
        $request->files = $controllerBase->getFiles();
        $request->client['ip'] = $controllerBase->getUriHost();
        $request->client['port'] = $controllerBase->getUriPort();
        $request->time = $controllerBase->getTime();
        $request->headers = $controllerBase->getHeaders();

        $this->request = $request;

        // setting & model
        $modelBase = new ModelBase();
        $this->setting = $modelBase->getDatabaseList();
        $this->models = $modelBase->getDatabaseObject();

    }

}