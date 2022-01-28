<?php

/**
 *
 * @license
 *
 */

namespace Ntch\Framework\WebRestful\Settings;

use Ntch\Framework\WebRestful\WebRestful;

class Base extends WebRestful
{

    /**
     * @var array
     */
    protected static array $settingVariable = [];

    /**
     * Setting entry point.
     *
     * @param string $path
     * @param string $class
     *
     * @return array
     */
    public function settingBase(string $path, string $class)
    {
        $this->webRestfulCheckList('setting', null, $path, $class, null);
        $this->setSettingData($class, $this->absoluteFile);
    }

    /**
     * set ini file data.
     *
     * @param string $fileName
     * @param string $absoluteFile
     *
     * @return void
     */
    private function setSettingData(string $fileName, string $absoluteFile)
    {
        switch ($fileName) {
            case 'libraries':
                $process_sections = false;
                break;
            case 'oracle':
            case 'mysql':
            case 'mssql':
                $process_sections = true;
                break;
            default:
                die('【ERROR】Setting fileName is not exist.');
        }

        self::$settingVariable[$fileName] = parse_ini_file($absoluteFile, $process_sections);
    }

    /**
     * Get setting data.
     * 
     * @param string $type
     *
     * @return array
     */
    public function getSettingData(string $type): array
    {
        return self::$settingVariable;
    }

}