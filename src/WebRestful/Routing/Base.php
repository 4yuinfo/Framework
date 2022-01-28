<?php

/**
 *
 * @license
 *
 */

namespace Ntch\Framework\WebRestful\Routing;

use Ntch\Framework\WebRestful\WebRestful;

class Base extends WebRestful
{

    /**
     * Router entry point.
     *
     * @return void
     */
    public function routerBase()
    {
        $this->webRestfulCheckList('router',null, null, 'router', null);
    }
    
}