<?php


namespace Ntch\Framework\WebRestful\Models\Database\Mssql;

use Ntch\Framework\WebRestful\Models\Database\Mssql\Base as MssqlBase;
use Ntch\Framework\WebRestful\Models\Database\DmlInterface;

class Dml extends MssqlBase implements DmlInterface
{

    /**
     * @inheritDoc
     */
    public static function insert(string $modelType, string $modelName, string $tableName)
    {
        // config
        if($modelType === 'server') {
            $serverName = $modelName;
            $table = $tableName;
        } else {
            $serverName = self::$databaseList['mssql']['table'][$modelName]['server'];
            $table = self::$databaseList['mssql']['table'][$modelName]['table'];
        }

        $sql = "\nINSERT INTO [dbo].[$table] ";
        return $sql;
    }

    /**
     * @inheritDoc
     */
    public static function value(string $modelType, string $modelName, string $tableName, array $data)
    {
        $serverName = self::$databaseList['mssql']['table'][$modelName]['server'];
        $schema = self::$databaseObject['mssql']->table[$modelName]->schema;

        $data = MssqlBase::systemSet('INSERT', $schema, $data);

        $sql_key = '(';
        $sql_value = '(';
        foreach ($data as $key => $value) {
            $sql_key .= "[$key], ";
            if (@$schema[$key]['DATA_TYPE'] === 'DATE') {
                $data_size = $schema[$key]['DATA_SIZE'];
                $sql_value .= "TO_DATE(?, '$data_size'), ";
            } else {
                $sql_value .= "?, ";
            }
        }
        $sql_key = substr(trim($sql_key), 0, -1);
        $sql_value = substr(trim($sql_value), 0, -1);
        $sql_key .= ')';
        $sql_value .= ')';

        $sqlCommand = "$sql_key \nVALUES $sql_value\n";
        return $sql = ['command' => $sqlCommand, 'data' => $data];
    }

    /**
     * @inheritDoc
     */
    public static function delete(string $modelType, string $modelName, string $tableName)
    {
        // config
        if($modelType === 'server') {
            $serverName = $modelName;
            $table = $tableName;
        } else {
            $serverName = self::$databaseList['mssql']['table'][$modelName]['server'];
            $table = self::$databaseList['mssql']['table'][$modelName]['table'];
        }

        $sql = "\nDELETE FROM [dbo].[$table] ";
        return $sql;
    }

    /**
     * @inheritDoc
     */
    public static function update(string $modelType, string $modelName, string $tableName)
    {
        // config
        if($modelType === 'server') {
            $serverName = $modelName;
            $table = $tableName;
        } else {
            $serverName = self::$databaseList['mssql']['table'][$modelName]['server'];
            $table = self::$databaseList['mssql']['table'][$modelName]['table'];
        }

        $sql = "\nUPDATE [dbo].[$table] ";
        return $sql;
    }

    /**
     * @inheritDoc
     */
    public static function set(string $modelType, string $modelName, string $tableName, array $data)
    {
        // config
        if($modelType === 'server') {
            $serverName = $modelName;
            $schema = self::$databaseObject['mssql']->$modelType[$modelName]->$tableName->schema;
        } else {
            $serverName = self::$databaseList['mssql']['table'][$modelName]['server'];
            $schema = self::$databaseObject['mssql']->$modelType[$modelName]->schema;
        }

        $data = MssqlBase::systemSet('UPDATE', $schema, $data);

        $sql_set = '';
        foreach ($data as $key => $value) {
            $sql_set .= "[$key] = ";
            if (is_null($value)) {
                $sql_set .= "?, ";
            } else {
                if ($schema[$key]['DATA_TYPE'] === 'DATE') {
                    $data_size = $schema[$key]['DATA_SIZE'];
                    $sql_set .= "TO_DATE(?, '$data_size'), ";
                } else {
                    $sql_set .= "?, ";
                }
            }
        }
        $sql_set = substr(trim($sql_set), 0, -1);

        $sqlCommand = "\nSET $sql_set";
        return $sql = ['command' => $sqlCommand, 'data' => $data];
    }

}