<?php

/**
 *
 * @license
 *
 */

namespace Ntch\Framework\WebRestful\View;

use Ntch\Framework\WebRestful\WebRestful;

class Base extends WebRestful
{
    
    /**
     * View entry point.
     *
     * @param string|null $uri
     * @param string $path
     * @param string $class
     * @param array $data
     *
     * @return void
     */
    public function viewBase(?string $uri, string $path, string $class, array $data = [])
    {
        $isWebRestfulPass = $this->webRestfulCheckList('view', $uri, $path, $class, null);
        if($isWebRestfulPass) {
            $this->viewExecute($data);
        }
    }

    /**
     * Execute view.
     *
     * @param array $data
     *
     * @return void
     */
    private function viewExecute(array $data)
    {
        new View($this->absoluteFile, $data);
        exit();
    }

    /**
     * Get uri variable.
     *
     * @return array
     */
    public function getUri(): array
    {
        return self::$uriVariable;
    }

    /**
     * Get query.
     *
     * @return array
     */
    public function getQuery(): array
    {
        return $this->globals->query;
    }

    /**
     * Get cookie variable.
     *
     * @return array
     */
    public function getCookie(): array
    {
        return $this->globals->cookie;
    }

}