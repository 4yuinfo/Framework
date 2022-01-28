<?php

/**
 *
 * @license
 *
 */

namespace Ntch\Framework\WebRestful\Librarys;

use Ntch\Framework\WebRestful\WebRestful;
use Ntch\Framework\WebRestful\Settings\Base as SettingBase;

class Base extends WebRestful
{

    /**
     * @var SettingBase
     */
    protected $settingBase;

    /**
     * Library entry point.
     *
     * @param array $libraries
     *
     * @return void
     */
    public function libraryBase(array $libraries)
    {
        $this->webRestfulCheckList('library', null, null, null, null);

        $this->settingBase = new SettingBase();
        $this->autoloaderLibrary($libraries);
    }

    /**
     * Autoloader library files from psr-4.
     *
     * @param array $libraries
     *
     * @return void
     */
    private function autoloaderLibrary(array $libraries)
    {
        $librarySetting = $this->settingBase->getSettingData('libraries')['libraries'];
        foreach ($libraries as $key => $value) {
            if (isset($librarySetting[$value])) {
                $prefix = str_replace('/', '\\', substr($librarySetting[$value], 1));
                $base_dir = $this->basePath . $librarySetting[$value];
                $this->autoloaderFile($prefix, $base_dir);
            }
        }
    }

}