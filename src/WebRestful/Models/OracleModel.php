<?php

/**
 *
 * @license
 *
 */

namespace Ntch\Framework\WebRestful\Models;

use Ntch\Framework\WebRestful\Models\Database\Oracle\Base as OracleBase;
use Ntch\Framework\WebRestful\Models\Database\Oracle\Ddl;
use Ntch\Framework\WebRestful\Models\Database\Oracle\Dml;
use Ntch\Framework\WebRestful\Models\Database\Oracle\Dql;
use Ntch\Framework\WebRestful\Models\Database\Oracle\Dcl;

class OracleModel
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
                        $result = OracleBase::query($this->modelType, $this->modelName, $this->tableName, $this->sql, $this->data, $this->keyName, $this->offset, $this->limit);
                        break;
                    case 1:
                        $result = OracleBase::query($this->modelType, $this->modelName, $this->tableName, $args[0], $sqlBind = null, $this->keyName, $this->offset, $limit = -1);
                        break;
                    case 2:
                        $result = OracleBase::query($this->modelType, $this->modelName, $this->tableName, $args[0], $args[1], $this->keyName, $this->offset, $limit = -1);
                        break;
                    case 3:
                        $result = OracleBase::query($this->modelType, $this->modelName, $this->tableName, $args[0], $args[1], $args[2], $this->offset, $limit = -1);
                        break;
                    case 4:
                        $result = OracleBase::query($this->modelType, $this->modelName, $this->tableName, $args[0], $args[1], $args[2], $args[3], $limit = -1);
                        break;
                    case 5:
                        $result = OracleBase::query($this->modelType, $this->modelName, $this->tableName, $args[0], $args[1], $args[2], $args[3], $args[4]);
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

    public function offset(int $offset)
    {
        $this->offset = $offset;
        return $this;
    }

    public function limit(int $limit)
    {
        $this->limit = $limit;
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
        if ($this->action === 'INSERT') {
            $this->sql = Dml::insert($this->modelType, $this->modelName, $this->tableName);
        } elseif ($this->action === 'MERGE') {
            $this->sql .= Dml::mergeInsert();
        }

        return $this;
    }

    public function value(array $data = [])
    {
        if ($this->action === 'INSERT') {
            $res = Dml::value($this->modelType, $this->modelName, $this->tableName, $data);
            $this->sql .= $res['command'];
            $this->data = array_merge($this->data, $res['data']);
        } elseif ($this->action === 'MERGE') {
            $this->sql .= Dml::mergeValue($this->modelType, $this->modelName, $this->tableName, $data);
        }

        return $this;
    }

    public function delete()
    {
        empty($this->action) ? $this->action = 'DELETE' : null;
        if ($this->action === 'DELETE') {
            $this->sql = Dml::delete($this->modelType, $this->modelName, $this->tableName);
        } elseif ($this->action === 'MERGE') {

        }

        return $this;
    }

    public function update()
    {
        empty($this->action) ? $this->action = 'UPDATE' : null;
        if ($this->action === 'UPDATE') {
            $this->sql = Dml::update($this->modelType, $this->modelName, $this->tableName);
        } elseif ($this->action === 'MERGE') {
            $this->sql .= Dml::mergeUpdate();
        }

        return $this;
    }

    public function set(array $data = [])
    {
        if ($this->action === 'UPDATE') {
            $res = Dml::set($this->modelType, $this->modelName, $this->tableName, $data);
            $this->sql .= $res['command'];
            $this->data = array_merge($this->data, $res['data']);
        } elseif ($this->action === 'MERGE') {
            $this->sql .= Dml::mergeSet($this->modelName, $data);
        }

        return $this;
    }

    public function merge()
    {
        empty($this->action) ? $this->action = 'MERGE' : null;
        $this->sql = Dml::merge($this->modelType, $this->modelName, $this->tableName);

        return $this;
    }

    public function using(string $user, string $tableName)
    {
        $this->sql .= Dml::using($user, $tableName);

        return $this;
    }

    public function on(string $target, string $source)
    {
        $this->sql .= Dml::on($target, $source);

        return $this;
    }

    public function matched()
    {
        $this->sql .= Dml::matched();

        return $this;
    }

    public function not()
    {
        $this->sql .= Dml::not();

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