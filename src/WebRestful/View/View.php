<?php


namespace Ntch\Framework\WebRestful\View;

use Ntch\Framework\WebRestful\View\Base as ViewBase;

class View
{

    /**
     * @var object
     */
    public object $request;

    public function __construct($absoluteFile, $data)
    {
        $viewBase = new ViewBase();
        $request = new \stdClass();

        $request->uri = $viewBase->getUri();
        $request->query = $viewBase->getQuery();
        $request->cookies = $viewBase->getCookie();

        $this->request = $request;
        $this->data = $data;

        include $absoluteFile;
    }

}