<?php

/**
 *
 * @license
 *
 */

namespace Ntch\Framework\WebRestful\Models;

use Ntch\Framework\WebRestful\WebRestful;
use Ntch\Framework\WebRestful\Settings\Base as SettingBase;
use Ntch\Framework\WebRestful\Models\Database\Oracle\Base as OracleBase;
use Ntch\Framework\WebRestful\Models\Database\Mysql\Base as MysqlBase;
use Ntch\Framework\WebRestful\Models\Database\Mssql\Base as MssqlBase;

class Base extends WebRestful
{

    /**
     * @var SettingBase
     */
    protected $settingBase;

    /**
     * @var array
     */
    protected static array $databaseList = [];

    /**
     * @var array
     */
    protected static array $databaseObject = [];

    /**
     * Model entry point.
     *
     * @param string $driver
     * @param array $models
     *
     * @return void
     */
    public function modelBase(string $driver, array $models)
    {
        $this->settingBase = new SettingBase();
        $this->setDriverList($driver, $models);
        $this->checkModelConfig($driver, $models);
        $this->createModelObject($driver, $models);

        switch ($driver) {
            case 'oracle':
                $oracle = new OracleBase();
                $oracle->execute();
                break;
            case 'mysql':
                $mysql = new MysqlBase();
                $mysql->execute();
                break;
            case 'mssql':
                $mssql = new MssqlBase();
                $mssql->execute();
                break;
        }

        $this->loadWebRestful($driver, $models);
        $this->loadModelTableSchema($driver);

    }

    /**
     * Set models driver list.
     *
     * @param string $driver
     * @param array $models
     *
     * @return void
     */
    private function setDriverList(string $driver, array $models)
    {
        $settingList = $this->settingBase->getSettingData($driver);
        $serverNameList = [];

        foreach ($models as $key) {
            // if - table , else - server
            if (isset($settingList[$driver][$key]['server']) && isset($settingList[$driver][$key]['type']) && $settingList[$driver][$key]['type'] == 'table') {
                $serverName = $settingList[$driver][$key]['server'];

                if (!in_array($serverName, $serverNameList)) {
                    if (isset($settingList[$driver][$serverName]['type']) && $settingList[$driver][$serverName]['type'] == 'server') {
                        self::$databaseList[$driver]['server'][$serverName] = $settingList[$driver][$serverName];
                        array_push($serverNameList, $serverName);
                    } else {
                        die("【ERROR】Models $driver.ini server \"$serverName\" is not exist.");
                    }
                }

                self::$databaseList[$driver]['table'][$key] = $settingList[$driver][$key];
            } else {
                if (!in_array($key, $serverNameList)) {
                    if (isset($settingList[$driver][$key]['type']) && $settingList[$driver][$key]['type'] == 'server') {
                        self::$databaseList[$driver]['server'][$key] = $settingList[$driver][$key];
                        array_push($serverNameList, $key);
                    } else {
                        die("【ERROR】Models $driver.ini server \"$key\" is not exist.");
                    }
                }
            }
        }
    }

    /**
     * Check model config.
     *
     * @param string $driver
     * @param array $models
     *
     * @return void
     */
    private function checkModelConfig(string $driver, array $models)
    {
        $modelConfigList = ['path', 'class'];

        foreach ($models as $key) {
            foreach ($modelConfigList as $key2) {
                (isset(self::$databaseList[$driver]['server'][$key][$key2]) || isset(self::$databaseList[$driver]['table'][$key][$key2])) ? null : die("【ERROR】Model $key tag \"$key2\" is not exist.");
            }
        }
    }

    /**
     * Create model and driver object.
     *
     * @param string $driver
     * @param array $models
     *
     * @return void
     */
    private function createModelObject(string $driver, array $models)
    {
        self::$databaseObject[$driver] = new \stdClass();

        // server
        foreach (self::$databaseList[$driver]['server'] as $serverName => $serverInfo) {
            foreach ($models as $modelName) {
                if ($serverName === $modelName) {
                    self::$databaseObject[$driver]->server[$serverName] = new \stdClass();
                }
            }
        }

        // table
        if (isset(self::$databaseList[$driver]['table'])) {
            foreach (self::$databaseList[$driver]['table'] as $tableName => $tableInfo) {
                self::$databaseObject[$driver]->table[$tableName] = new \stdClass();
            }
        }
    }

    /**
     * Load webRestful.
     *
     * @param string $driver
     * @param array $models
     *
     * @return void
     */
    private function loadWebRestful(string $driver, array $models)
    {
        // server
        foreach (self::$databaseList[$driver]['server'] as $serverName => $serverInfo) {
            foreach ($models as $modelName) {
                if ($serverName === $modelName) {
                    $this->webRestfulCheckList('model', null, $serverInfo['path'], $serverInfo['class'], null);

                    $tableNames = array_keys((array)self::$databaseObject[$driver]->server[$serverName]);
                    foreach ($tableNames as $tableName) {
                        $schema = self::$databaseObject[$driver]->server[$serverName]->$tableName->schema;
                        self::$databaseObject[$driver]->server[$serverName]->$tableName = new $serverInfo['class']();
                        self::$databaseObject[$driver]->server[$serverName]->$tableName->tableName = $tableName;
                        self::$databaseObject[$driver]->server[$serverName]->$tableName->schema = $schema;
                    }
                }
            }
        }

        // table
        if (isset(self::$databaseList[$driver]['table'])) {
            foreach (self::$databaseList[$driver]['table'] as $tableName => $tableInfo) {
                $this->webRestfulCheckList('model', null, $tableInfo['path'], $tableInfo['class'], 'schema');

                $modelObjet = new $tableInfo['class']();
                self::$databaseObject[$driver]->table[$tableName] = $modelObjet;
                self::$databaseObject[$driver]->table[$tableName]->tableName = self::$databaseList[$driver]['table'][$tableName]['table'];
            }
        }
    }

    /**
     * Load model table define in object schema method.
     *
     * @param string $driver
     *
     * @return void
     */
    private function loadModelTableSchema(string $driver)
    {
        if (isset(self::$databaseObject[$driver]->table)) {
            foreach (self::$databaseObject[$driver]->table as $tableName => $tableInfo) {
                $modelObject = self::$databaseObject[$driver]->table[$tableName];
                self::$databaseObject[$driver]->table[$tableName]->schema = $modelObject->schema();
            }
        }
    }

    /**
     * Get databaseList.
     * Password masking.
     *
     * @return array
     */
    public function getDatabaseList()
    {
        $driveList = ['oracle', 'mysql', 'mssql'];
        foreach ($driveList as $dbName => $type) {
            if (isset(self::$databaseList[$type])) {
                foreach (self::$databaseList[$type]['server'] as $serverName => $serverTag) {
                    self::$databaseList[$type]['server'][$serverName]['password'] = '***************';
                }
            }
        }
        return self::$databaseList;
    }

    /**
     * Get database object.
     *
     * @return array
     */
    public function getDatabaseObject()
    {
        return self::$databaseObject;
    }

}