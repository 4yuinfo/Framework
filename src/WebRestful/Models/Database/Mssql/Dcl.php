<?php


namespace Ntch\Framework\WebRestful\Models\Database\Mssql;

use Ntch\Framework\WebRestful\Models\Database\Mssql\Base as MssqlBase;
use Ntch\Framework\WebRestful\Models\Database\DclInterface;

class Dcl extends MssqlBase implements DclInterface
{

    /**
     * @inheritDoc
     */
    public static function commit(string $modelType, string $modelName)
    {
        // config
        if ($modelType === 'server') {
            $serverName = $modelName;
        } else {
            $serverName = self::$databaseList['mssql']['table'][$modelName]['server'];
        }

        $serverStatus = self::$databaseList['mssql']['server'][$serverName]['connect']['status'];
        if ($serverStatus === 'success') {
            $serverResult = self::$databaseList['mssql']['server'][$serverName]['connect']['result'];
            @sqlsrv_commit($serverResult);
        }
    }

    /**
     * @inheritDoc
     */
    public static function rollback(string $modelType, string $modelName)
    {
        // config
        if ($modelType === 'server') {
            $serverName = $modelName;
        } else {
            $serverName = self::$databaseList['mssql']['table'][$modelName]['server'];
        }

        $serverStatus = self::$databaseList['mssql']['server'][$serverName]['connect']['status'];
        if ($serverStatus === 'success') {
            $serverResult = self::$databaseList['mssql']['server'][$serverName]['connect']['result'];
            @sqlsrv_rollback($serverResult);
        }
    }

}