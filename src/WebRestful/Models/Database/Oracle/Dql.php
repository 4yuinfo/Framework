<?php


namespace Ntch\Framework\WebRestful\Models\Database\Oracle;

use Ntch\Framework\WebRestful\Models\Database\Oracle\Base as OracleBase;
use Ntch\Framework\WebRestful\Models\Database\DqlInterface;
use Saint\Loader\Psr4;

class Dql extends OracleBase implements DqlInterface
{

    /**
     * @inheritDoc
     */
    public static function select(string $modelType, string $modelName, string $tableName, array $data)
    {
        // config
        if ($modelType === 'server') {
            $serverName = $modelName;
            $table = $tableName;
            $schema = self::$databaseObject['oracle']->$modelType[$modelName]->$tableName->schema;
        } else {
            $serverName = self::$databaseList['oracle']['table'][$modelName]['server'];
            $table = self::$databaseList['oracle']['table'][$modelName]['table'];
            $schema = self::$databaseObject['oracle']->$modelType[$modelName]->schema;
        }
        $user = self::$databaseList['oracle']['server'][$serverName]['user'];

        if (empty($data)) {
            $sql_search = '*';
        } else {
            $data = array_flip($data);
            $sql_search = '';
            foreach ($data as $key => $value) {
                if (preg_match('/[a-zA-Z]+\(+\w+\)$/', $key)) {
                    $sql_search .= "$key, ";
                } else {
                    if ($schema[$key]['DATA_TYPE'] === 'DATE') {
                        $data_size = $schema[$key]['DATA_SIZE'];
                        $sql_search .= "TO_CHAR($key, '$data_size') as $key, ";
                    } else {
                        $sql_search .= "$key, ";
                    }
                }
            }
            $sql_search = substr(trim($sql_search), 0, -1);
        }

        $sqlCommand = "\nSELECT $sql_search \nFROM $user.$table ";
        return $sqlCommand;
    }

    /**
     * @inheritDoc
     */
    public static function where(string $modelType, string $modelName, string $tableName, array $data)
    {
        // config
        if ($modelType === 'server') {
            $serverName = $modelName;
            $schema = self::$databaseObject['oracle']->$modelType[$modelName]->$tableName->schema;
        } else {
            $serverName = self::$databaseList['oracle']['table'][$modelName]['server'];
            $schema = self::$databaseObject['oracle']->$modelType[$modelName]->schema;
        }

        $sql_where = '';
        foreach ($data as $key => $value) {
            $sql_where .= "$key = ";
            if (is_null($value)) {
                $sql_where .= " null, ";
            } else {
                if ($schema[$key]['DATA_TYPE'] === 'DATE') {
                    $data_size = isset($schema[$key]['DATA_SIZE']) ? empty($schema[$key]['DATA_SIZE']) ? 'YYYY-MM-DD HH24:MI:SS' : $schema[$key]['DATA_SIZE'] : 'YYYY-MM-DD HH24:MI:SS';
                    $sql_where .= "TO_DATE(:$key, '$data_size'), ";
                } else {
                    $sql_where .= ":$key AND ";
                }
            }
        }
        $sql_where = substr(trim($sql_where), 0, -4);

        $sqlCommand = "\nWHERE $sql_where";
        return $sql = ['command' => $sqlCommand, 'data' => $data];
    }

    /**
     * @inheritDoc
     */
    public static function orderby(array $data)
    {
        $sql_sort = '';
        foreach ($data as $key => $value) {
            $sql_sort .= "$value, ";
        }
        $sql_sort = substr(trim($sql_sort), 0, -1);

        $sqlCommand = "\nORDER BY $sql_sort";
        return $sqlCommand;
    }

    /**
     * @inheritDoc
     */
    public static function groupby(array $data)
    {
        $sql_sort = '';
        foreach ($data as $key => $value) {
            $sql_sort .= "$value, ";
        }
        $sql_sort = substr(trim($sql_sort), 0, -1);

        $sqlCommand = "\nGROUP BY $sql_sort";
        return $sqlCommand;
    }

}