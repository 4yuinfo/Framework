<?php

/**
 *
 * @license
 *
 */

namespace Ntch\Framework\WebRestful;

use Ntch\Framework\Psr\Psr4\AutoloaderClass;
use Ntch\Framework\Http\Server\Nginx;
use Ntch\Framework\Http\Request\Globals;
use Ntch\Framework\Http\Request\Header;
use Ntch\Framework\Http\Request\Body;

use Ntch\Framework\WebRestful\View\View;

class WebRestful
{

    /**
     * @var AutoloaderClass
     */
    protected $psr4;

    /**
     * @var Nginx
     */
    protected $nginx;

    /**
     * @var Globals
     */
    protected $globals;

    /**
     * @var Header
     */
    protected $header;

    /**
     * @var Body
     */
    protected $body;

    /**
     * @var string
     */
    private string $folder_name;

    /**
     * @var string
     */
    private string $class_name;

    /**
     * @var array
     */
    protected array $nginxPath = [];

    /**
     * @var array
     */
    protected array $uri = [];

    /**
     * @staticvar array
     */
    public static array $uriVariable = [];

    /**
     * @var string
     */
    protected string $basePath;

    /**
     * @var string
     */
    protected string $absolutePath;

    /**
     * @var string
     */
    protected string $absoluteFile;

    /**
     * Construct to get Nginx instance
     */
    public function __construct()
    {
        $this->psr4 = new AutoloaderClass();

        $nginx = new Nginx();
        $nginx->setNginx();
        $this->nginx = $nginx->getNginx();

        $globals = new Globals();
        $globals->setGlobals();
        $this->globals = $globals->getGlobals();

        $header = new Header();
        $header->setHeaders();
        $this->header = $header->getHeader();

        $body = new Body();
        $body->setBody();
        $this->body = $body->getBody();
    }

    /**
     * Set Uri variable.
     *
     * @return void
     */
    protected function setUriVariable()
    {
        for ($i = 1; $i <= count($this->nginxPath); $i++) {
            if (strpos($this->uri[$i], ':') === 0) {
                $uriParameter = substr($this->uri[$i], 1);
                $nginxPathValue = $this->nginxPath[$i];
                self::$uriVariable[$uriParameter] = $nginxPathValue;
            }
        }
    }

    /**
     * Set base path.
     *
     * @param string $baseFolder
     *
     * @return void
     */
    protected function setBasePath(string $baseFolder)
    {
        $this->basePath = $this->nginx->conf['project_root'] . $baseFolder;
    }

    /**
     * Set absolute path.
     *
     * @param string $folderName
     * @param string $path
     *
     * @return void
     */
    protected function setAbsolutePath(string $folderName, string $path)
    {
        if ($path !== '/') {
            $path .= '/';
        }
        $this->absolutePath = $this->nginx->conf['project_root'] . $folderName . $path;
    }

    /**
     * Set absolute file path.
     *
     * @param string $fileName
     * @param string $extension
     *
     * @return void
     */
    protected function setAbsoluteFile(string $fileName, string $extension)
    {
        $this->absoluteFile = $this->absolutePath . $fileName . '.' . $extension;
    }

    /**
     * Include file.
     *
     * @return void
     */
    protected function includeFile()
    {
        require_once($this->absoluteFile);
    }

    /**
     * Autoloader file.
     *
     * @return void
     */
    protected function autoloaderFile($prefix, $base_dir)
    {
        $this->psr4->addNamespace($prefix, $base_dir);
        $this->psr4->register();
    }

    /**
     *  Sorting out request uri and nginx uri variables.
     *
     * @param string $uri
     *
     * @return void
     */
    public function withUriMatch(string $uri)
    {
        $this->nginxPath = explode('/', $this->nginx->uri['path']);
        foreach ($this->nginxPath as $key => $value) {
            if (empty($value)) {
                unset($this->nginxPath[$key]);
            }
        }

        $this->uri = explode('/', $uri);
        foreach ($this->uri as $key => $value) {
            if (empty($value)) {
                unset($this->uri[$key]);
            }
        }
    }

    /**
     * Check Uri is Match.
     *
     * @return boolean
     */
    public function checkUriMatch()
    {
        if (count($this->nginxPath) != count($this->uri)) {
            return false;
        }

        for ($i = 1; $i <= count($this->nginxPath); $i++) {
            if (strpos($this->uri[$i], ':') === 0) {
                continue;
            } else {
                if ($this->nginxPath[$i] != $this->uri[$i]) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Check path is exist.
     *
     * @param string $path
     *
     * @return void
     */
    protected function checkPathExist(string $path)
    {
        return is_dir($path) ? null : die("【ERROR】Folder does not exist. Please create the folder in the following path： $path");
    }

    /**
     * Check file is exist.
     *
     * @param string $file
     *
     * @return void
     */
    protected function checkFileExist(string $file)
    {
        return is_file($file) ? null : die("【ERROR】File \"$this->class_name\" does not exist. Please create the File in the following file： $this->absoluteFile");
    }

    /**
     * Check function is exist.
     *
     * @param string $class
     * @param string $method
     *
     * @return void
     */
    protected function checkMethodExist(string $class, string $method)
    {
        return method_exists($class, $method) ? null : die("【ERROR】Method \"$method\" does not exist. Please create the method in the following file： $this->absoluteFile");
    }

    /**
     * Check function is can execute.
     *
     * @param string $class
     * @param string $method
     *
     * @return void
     */
    protected function checkMethodCanExecute(string $class, string $method)
    {
        $class = new $class();
        return is_callable([$class, $method]) ? null : die("【ERROR】Method \"$method\" Access modifiers is not available. Please check the Access modifiers in the following file： $this->absoluteFile");
    }

    /**
     * Check method config.
     *
     * @param string $mvc
     * @param string|null $uri
     * @param string|null $path
     * @param string|null $class
     * @param string|null $method
     *
     * @return boolean|void
     */
    protected function webRestfulCheckList(string $mvc, ?string $uri, ?string $path, ?string $class, ?string $method)
    {
        is_null($class) ? null : $this->class_name = $class;

        switch ($mvc) {
            case 'router':
                $this->folder_name = 'routes';

                $this->setBasePath($this->folder_name);
                $this->checkPathExist($this->basePath);

                $this->setAbsolutePath($this->folder_name, '/');
                $this->setAbsoluteFile($class, 'php');
                $this->checkFileExist($this->absoluteFile);

                $this->includeFile();
                break;
            case 'controller':
                $this->folder_name = 'controllers';

                $this->setBasePath($this->folder_name);
                $this->checkPathExist($this->basePath);

                $this->withUriMatch($uri);
                $uriExist = $this->checkUriMatch();

                if ($uriExist) {
                    $this->setUriVariable();

                    $this->setAbsolutePath($this->folder_name, $path);
                    $this->checkPathExist($this->absolutePath);

                    $this->setAbsoluteFile($class, 'php');
                    $this->checkFileExist($this->absoluteFile);

                    $this->includeFile();

                    $this->checkMethodExist($class, $method);
                    $this->checkMethodCanExecute($class, $method);
                    return true;
                }
                break;
            case 'view':
                $this->folder_name = 'view';

                $this->setBasePath($this->folder_name);
                $this->checkPathExist($this->basePath);

                if (!is_null($uri)) {
                    $this->withUriMatch($uri);
                    $uriExist = $this->checkUriMatch();
                    if ($uriExist) {
                        $this->setUriVariable();
                    }
                }

                if (is_null($uri) || $uriExist) {
                    $this->setAbsolutePath($this->folder_name, $path);
                    $this->checkPathExist($this->absolutePath);

                    $this->setAbsoluteFile($class, 'php');
                    $this->checkFileExist($this->absoluteFile);
                    return true;
                }
                break;
            case 'setting':
                $this->folder_name = 'settings';

                $this->setBasePath($this->folder_name);
                $this->checkPathExist($this->basePath);

                $this->setAbsolutePath($this->folder_name, $path);
                $this->checkPathExist($this->absolutePath);

                $this->setAbsoluteFile($class, 'ini');
                $this->checkFileExist($this->absoluteFile);
                break;
            case 'model':
                $this->folder_name = 'models';

                $this->setBasePath($this->folder_name);
                $this->checkPathExist($this->basePath);

                $this->setAbsolutePath($this->folder_name, $path);
                $this->checkPathExist($this->absolutePath);

                $this->setAbsoluteFile($class, 'php');
                $this->checkFileExist($this->absoluteFile);

                $this->includeFile();

                if (!is_null($method)) {
                    $this->checkMethodExist($class, $method);
                    $this->checkMethodCanExecute($class, $method);
                }
                break;
            case 'library':
                $this->folder_name = 'libraries';

                $this->setBasePath($this->folder_name);
                $this->checkPathExist($this->basePath);
                break;
            default:
                die('【ERROR】WebRestful does not handle this method.');
        }
    }

}