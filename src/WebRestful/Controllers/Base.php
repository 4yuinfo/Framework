<?php

/**
 *
 * @license
 *
 */

namespace Ntch\Framework\WebRestful\Controllers;

use Ntch\Framework\WebRestful\WebRestful;
use LaLit\XML2Array;

class Base extends WebRestful
{

    /**
     * Controller entry point.
     *
     * @param string $uri
     * @param string $path
     * @param string $class
     * @param string $method
     *
     * @return void
     */
    public function controllerBase(string $uri, string $path, string $class, string $method)
    {
        $isWebRestfulPass = $this->webRestfulCheckList('controller', $uri, $path, $class, $method);
        if ($isWebRestfulPass) {
            $this->controllerExecute($class, $method);
            exit();
        }
    }

    /**
     * Execute controller method.
     *
     * @param string $class
     * @param string $method
     *
     * @return void
     */
    private function controllerExecute(string $class, string $method = 'index')
    {
        $controller = new $class();
        $controller->$method();
    }

    /**
     * Get UUID.
     *
     * @return string
     */
    public function getUuid(): string
    {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((double)microtime() * 10000);
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);
            $uuid =
                substr($charid, 0, 8) . $hyphen
                . substr($charid, 8, 4) . $hyphen
                . substr($charid, 12, 4) . $hyphen
                . substr($charid, 16, 4) . $hyphen
                . substr($charid, 20, 12);
            return $uuid;
        }
    }

    /**
     * Get Headers.
     *
     * @return array
     */
    public function getHeaders(): array
    {
        $headers = $this->header->headers;
        $ignore = ['cookie', 'authorization'];
        foreach ($ignore as $name) {
            unset($headers[$name]);
        }

        return $headers;
    }

    /**
     * Get authorization.
     *
     * @return null|array
     */
    public function getAuthorization(): ?array
    {
        return $this->nginx->headers['authorization'];
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

    /**
     * Get request method.
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->nginx->request['method'];
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
     * Get input data.
     * If input data is json or xml then transform to array.
     *
     * @return null|string|array
     */
    public function getInput()
    {
        $input = $this->body->input;

        // json to array
        if (!is_null(json_decode($input))) {
            $input = json_decode($input, true);
            return $input;
        }

        // xml to array
        if (@simplexml_load_string($input)) {
            $xml = new XML2Array();
            $input = $xml->createArray($input);
            return $input;
        }

        return $input;
    }

    /**
     * Get attribute.
     *
     * @return array
     */
    public function getAttribute(): array
    {
        return $this->globals->attributes;
    }

    /**
     * Get client ip.
     *
     * @return string
     */
    public function getUriHost(): string
    {
        return $this->nginx->uri['host'];
    }

    /**
     * Get client port.
     *
     * @return string
     */
    public function getUriPort(): string
    {
        return $this->nginx->uri['port'];
    }

    /**
     * Get request time (microsecond).
     *
     * @return array
     */
    public function getTime(): array
    {
        $unix = $this->nginx->request['time'];
        $time['unix'] = $unix;
        $time['date'] = date('Y-m-d', $unix);
        $time['time'] = date('H:i:s', $unix);
        return $time;
    }

    /**
     * Get files.
     *
     * @return array
     */
    public function getFiles(): array
    {
        return $this->globals->files;
    }

}