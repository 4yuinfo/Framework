<?php

/**
 *
 * @license
 *
 */

namespace Ntch\Framework\Http\Server;

use Ntch\Framework\Http\Psr7;

class Nginx extends Psr7
{

    /**
     * @param array
     */
    private array $authorization = [];

    /**
     * Get Nginx information.
     *
     * @return \stdClass
     */
    public function getNginx(): \stdClass
    {
        $nginx = new \stdClass();
        $nginx->headers = $this->message->getHeaders();
        $nginx->headers['authorization'] = $this->getAuthorization();
        $nginx->response['code'] = $this->response->getStatusCode();
        $nginx->response['reason_phrase'] = $this->response->getReasonPhrase();
        $nginx->request['target'] = $this->request->getRequestTarget();
        $nginx->request['method'] = $this->request->getMethod();
        $nginx->request['time'] = $this->getTime();
        $nginx->uri['scheme'] = $this->uri->getScheme();
        $nginx->uri['host'] = $this->uri->getHost();
        $nginx->uri['port'] = $this->uri->getPort();
        $nginx->uri['path'] = $this->uri->getPath();
        $nginx->uri['query'] = $this->uri->getQuery();
        $nginx->uri['query'] = $this->uri->getQuery();
        $nginx->conf['project_root'] = $this->getProjectRoot();
        return $nginx;
    }

    /**
     * Set Nginx information.
     *
     * @return void
     */
    public function setNginx()
    {
        $this->setHttpHeader();
        $this->setAuthorization();
        $this->checkHttpProtocol();
        $this->checkResponseStatusCode();
        $this->setRequestTarget();
        $this->setRequestMethod();
        $this->setUriScheme();
        $this->setUriHost();
        $this->setUriPort();
        $this->setUriPath();
        $this->setUriQuery();
        $this->setProjectRoot();
        $this->setTime();
    }

    /**
     * Check http protocol with nginx.
     *
     * @return void
     */
    private function checkHttpProtocol()
    {
        $protocol = explode('/', $_SERVER['SERVER_PROTOCOL'])[1];
        $this->message->withProtocolVersion($protocol);
    }

    /**
     * Check http status code with nginx.
     *
     * @return void
     */
    private function checkResponseStatusCode()
    {
        $code = $_SERVER['REDIRECT_STATUS'];
        $this->response->withStatus((int)$code);
    }

    /**
     * Check project root with nginx.
     *
     * @return boolean
     */
    private function checkProjectRoot(): bool
    {
        if (isset($_SERVER['PROJECT_ROOT'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Set http header with nginx.
     *
     * @return void
     */
    private function setHttpHeader()
    {
        foreach (getallheaders() as $name => $value) {
            $this->message->withHeader($name, $value);
        }
    }

    /**
     * Set authorization with nginx.
     *
     * @return void
     */
    private function setAuthorization()
    {
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $type = explode(' ', $_SERVER['HTTP_AUTHORIZATION'])[0];
            $token = explode(' ', $_SERVER['HTTP_AUTHORIZATION'])[1];
            $this->authorization['type'] = $type;
            $this->authorization['token'] = $token;
        }
    }

    /**
     * Set request target with nginx.
     *
     * @return void
     */
    private function setRequestTarget()
    {
        $target = $_SERVER['REQUEST_URI'];
        $this->request->withRequestTarget($target);
    }

    /**
     * Set request method with nginx.
     *
     * @return void
     */
    private function setRequestMethod()
    {
        $target = $_SERVER['REQUEST_METHOD'];
        $this->request->withMethod($target);
    }

    /**
     * Set uri scheme with nginx.
     *
     * @return void
     */
    private function setUriScheme()
    {
        $scheme = $_SERVER['REQUEST_SCHEME'];
        $this->uri->withScheme($scheme);
    }

    /**
     * Set uri host with nginx.
     *
     * @return void
     */
    private function setUriHost()
    {
        $ip = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
        foreach ($ip as $key) {
            if (!empty($_SERVER[$key]) && filter_var($_SERVER[$key], FILTER_VALIDATE_IP)) {
                $this->uri->withHost($_SERVER[$key]);
            }
        }
    }

    /**
     * Set uri port with nginx.
     *
     * @return void
     */
    private function setUriPort()
    {
        $port = $_SERVER['REMOTE_PORT'];
        $this->uri->withPort((int)$port);
    }

    /**
     * Set uri path with nginx.
     *
     * @return void
     */
    private function setUriPath()
    {
        $path = explode('?', $_SERVER['REQUEST_URI'])[0];
        $this->uri->withPath($path);
    }

    /**
     * Set uri query with nginx.
     *
     * @return void
     */
    private function setUriQuery()
    {
        $query = $_SERVER['QUERY_STRING'];
        $this->uri->withQuery($query);
    }

    /**
     * Set project root with nginx.
     *
     * @return void
     */
    private function setProjectRoot()
    {
        if ($this->checkProjectRoot()) {
            $this->project_root = $_SERVER['PROJECT_ROOT'];
        } else {
            die('【ERROR】Nginx conf does not set the PROJECT_ROOT parameter.');
        }
    }

    /**
     * Set request time (microsecond) with nginx.
     *
     * @return void
     */
    private function setTime()
    {
        $this->time = $_SERVER['REQUEST_TIME_FLOAT'];
    }

    /**
     * Get authorization with nginx.
     *
     * @return array
     */
    public function getAuthorization(): array
    {
        return $this->authorization;
    }

    /**
     * Get project root with nginx.
     *
     * @return string
     */
    public function getProjectRoot(): string
    {
        return $this->project_root;
    }

    /**
     * Get request time (microsecond) with nginx.
     *
     * @return void
     */
    public function getTime()
    {
        return $this->time;
    }

}