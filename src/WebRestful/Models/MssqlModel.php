<?php

/**
 *
 * @license
 *
 */

namespace Ntch\Framework\WebRestful\Models;

use Ntch\Framework\WebRestful\Models\Database\Mssql\Base as MssqlBase;
use Ntch\Framework\WebRestful\Models\Database\Mssql\Ddl;
use Ntch\Framework\WebRestful\Models\Database\Mssql\Dml;
use Ntch\Framework\WebRestful\Models\Database\Mssql\Dql;
use Ntch\Framework\WebRestful\Models\Database\Mssql\Dcl;

class MssqlModel
{

    /**
     * @var string
     */
    public string $modelType = '';

    /**
     * @var string
     */
    public string $modelName = '';

    /**
     * @var string
     */
    public string $tableName = '';

    /**
     * @var string
     */
    public string $action = '';

    /**
     * @var string
     */
    public string $sql = '';

    /**
     * @var array
     */
    public array $data = [];

    /**
     * @var string|null
     */
    public $keyName = null;

    /**
     * @var int
     */
    public int $offset = 0;

    /**
     * @var int
     */
    public int $limit = -1;

    // Base for query
    function __call(string $fun, array $args)
    {
        $count = count($args);
        switch ($fun) {
            case 'query':
                switch ($count) {
                    case 0:
                        $result = MssqlBase::query($this->modelType, $this->modelName, $this->tableName, $this->sql, $this->data, $this->keyName, $this->offset, $this->limit);
                        break;
                    case 1:
                        $result = MssqlBase::query($this->modelType, $this->modelName, $this->tableName, $args[0], $sqlBind = null, $this->keyName, $this->offset, $limit = -1);
                        break;
                    case 2:
                        $result = MssqlBase::query($this->modelType, $this->modelName, $this->tableName, $args[0], $args[1], $this->keyName, $this->offset, $limit = -1);
                        break;
                    case 3:
                        $result = MssqlBase::query($this->modelType, $this->modelName, $this->tableName, $args[0], $args[1], $args[2], $this->offset, $limit = -1);
                        break;
                    case 4:
                        $result = MssqlBase::query($this->modelType, $this->modelName, $this->tableName, $args[0], $args[1], $args[2], $args[3], $limit = -1);
                        break;
                    case 5:
                        $result = MssqlBase::query($this->modelType, $this->modelName, $this->tableName, $args[0], $args[1], $args[2], $args[3], $args[4]);
                        break;
                    default:
                        die("【ERROR】Wrong parameters for \"$fun\".");
                }
                $this->clean();
                return $result;
            default:
                die("【ERROR】Not support \"$fun\"");
        }
    }

    public function clean()
    {
        $this->action = '';
        $this->sql = '';
        $this->data = [];
        $this->keyName = null;
        $this->offset = 0;
        $this->limit = -1;
    }

    public function keyName(?string $keyName)
    {
        $this->keyName = $keyName;
        return $this;
    }

    public function top(int $count)
    {
        $this->sql = str_replace('SELECT', "SELECT TOP $count", $this->sql);
        return $this;
    }

    public function offset(int $offset)
    {
        $this->sql .= "\nOFFSET $offset Rows";
        return $this;
    }

    public function fetch(int $limit)
    {
        $this->sql .= "\nFetch Next $limit Rows Only";
        return $this;
    }

    // DDL
    public function createTable()
    {
        return Ddl::createTable($this->modelType, $this->modelName, $this->tableName);
    }

    public function commentTable()
    {
        return Ddl::commentTable($this->modelType, $this->modelName, $this->tableName);
    }

    // Dml
    public function insert()
    {
        empty($this->action) ? $this->action = 'INSERT' : null;
        $this->sql = Dml::insert($this->modelType, $this->modelName, $this->tableName);

        return $this;
    }

    public function value(array $data = [])
    {
        $res = Dml::value($this->modelType, $this->modelName, $this->tableName, $data);
        $this->sql .= $res['command'];
        $this->data = array_merge($this->data, $res['data']);

        return $this;
    }

    public function delete()
    {
        empty($this->action) ? $this->action = 'DELETE' : null;
        $this->sql = Dml::delete($this->modelType, $this->modelName, $this->tableName);

        return $this;
    }

    public function update()
    {
        empty($this->action) ? $this->action = 'UPDATE' : null;
        $this->sql = Dml::update($this->modelType, $this->modelName, $this->tableName);

        return $this;
    }

    public function set(array $data = [])
    {
        $res = Dml::set($this->modelType, $this->modelName, $this->tableName, $data);
        $this->sql .= $res['command'];
        $this->data = array_merge($this->data, $res['data']);

        return $this;
    }

    // Dql
    public function select(array $data = [])
    {
        empty($this->action) ? $this->action = 'SELECT' : null;
        $this->sql = Dql::select($this->modelType, $this->modelName, $this->tableName, $data);

        return $this;
    }

    public function where(array $data)
    {
        $res = Dql::where($this->modelType, $this->modelName, $this->tableName, $data);
        $this->sql .= $res['command'];
        $this->data = array_merge($this->data, $res['data']);
        return $this;
    }

    public function orderby(array $data)
    {
        $this->sql .= Dql::orderby($data);
        return $this;
    }

    public function groupby(array $data)
    {
        $this->sql .= Dql::groupby($data);
        return $this;
    }

    // Dcl
    public function commit()
    {
        return Dcl::commit($this->modelType, $this->modelName);
    }

    public function rollback()
    {
        return Dcl::rollback($this->modelType, $this->modelName);
    }

}