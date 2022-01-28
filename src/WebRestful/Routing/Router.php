<?php

/**
 *
 * @license
 *
 */

namespace Ntch\Framework\WebRestful\Routing;

use Ntch\Framework\WebRestful\WebRestful;
use Ntch\Framework\WebRestful\Controllers\Base as ControllerBase;
use Ntch\Framework\WebRestful\View\Base as ViewBase;
use Ntch\Framework\WebRestful\Settings\Base as SettingBase;
use Ntch\Framework\WebRestful\Models\Base as ModelBase;
use Ntch\Framework\WebRestful\Librarys\Base as LibraryBase;

class Router
{

    /**
     * Router to mvc.
     *
     * @param string $uri
     * @param array $mvc
     *
     * @return void
     */
    public function mvc(string $uri, array $mvc)
    {
        $webRestful = new WebRestful();
        $webRestful->withUriMatch($uri);
        $uriExist = $webRestful->checkUriMatch();

        if ($uriExist) {
            // model
            $modelList = ['oracle' => [], 'mysql' => [], 'mssql' => []];
            foreach ($mvc as $key => $value) {
                if (isset($modelList[$key])) {
                    $modelList[$key] = $value;
                }
            }
            $this->model($modelList);

            // library
            isset($mvc['libraries']) ? $this->library($mvc['libraries']) : null;

            // controller
            $path = $mvc['controller'][0];
            $class = $mvc['controller'][1];
            $method = isset($mvc['controller'][2]) ? $mvc['controller'][2] : 'index';
            $this->controller($uri, $path, $class, $method);
        }
    }

    /**
     * Router to controller.
     *
     * @param string $uri
     * @param string $path
     * @param string $class
     * @param string $method
     *
     * @return void
     */
    public function controller(string $uri, string $path, string $class, string $method = 'index')
    {
        $controllerBase = new ControllerBase();
        $controllerBase->controllerBase($uri, $path, $class, $method);
    }

    /**
     * Router to view.
     *
     * @param string|null $uri
     * @param string $path
     * @param string $class
     * @param array $data
     *
     * @return void
     */
    public function view(string $uri = null, string $path, string $class, array $data = [])
    {
        $viewBase = new ViewBase();
        $viewBase->viewBase($uri, $path, $class, $data);
    }

    /**
     * Router to setting.
     *
     * @param string $path
     * @param string $class
     *
     * @return void
     */
    private function setting(string $path, string $class)
    {
        $settingBase = new SettingBase();
        $settingBase->settingBase($path, $class);
    }

    /**
     * Include model.
     *
     * @param array $modelList
     *
     * @return void
     */
    private function model($modelList)
    {
        $modelBase = new ModelBase();
        foreach ($modelList as $driver => $models) {
            if (!empty($models)) {
                $this->setting('/', $driver);
                $modelBase->modelBase($driver, $models);
            }
        }
    }

    /**
     * Include library.
     *
     * @param array $libraries
     *
     * @return void
     */
    private function library(array $libraries)
    {
        $libraryBase = new LibraryBase();
        $this->setting('/', 'libraries');
        $libraryBase->libraryBase($libraries);
    }

}