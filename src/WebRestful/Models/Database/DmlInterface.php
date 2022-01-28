<?php

/**
 *
 * @license
 *
 */

namespace Ntch\Framework\WebRestful\Models\Database;

interface DmlInterface
{

    /**
     * Insert data.
     *
     * @param string $modelType
     * @param string $modelName
     * @param string $tableName
     *
     * @return string
     */
    public static function insert(string $modelType, string $modelName, string $tableName);

    /**
     * Insert value.
     *
     * @param string $modelType
     * @param string $modelName
     * @param string $tableName
     * @param array $data
     *
     * @return string
     */
    public static function value(string $modelType, string $modelName, string $tableName, array $data);

    /**
     * Delete data.
     *
     * @param string $modelType
     * @param string $modelName
     * @param string $tableName
     *
     * @return string
     */
    public static function delete(string $modelType, string $modelName, string $tableName);

    /**
     * Update data.
     *
     * @param string $modelType
     * @param string $modelName
     * @param string $tableName
     *
     * @return string
     */
    public static function update(string $modelType, string $modelName, string $tableName);

    /**
     * Update set.
     *
     * @param string $modelType
     * @param string $modelName
     * @param string $tableName
     * @param array $data
     *
     * @return array
     */
    public static function set(string $modelType, string $modelName, string $tableName, array $data);

//    /**
//     * Merge table.
//     *
//     * @param string $modelType
//     * @param string $modelName
//     * @param string $tableName
//     *
//     * @return string
//     */
//    public static function merge(string $modelType, string $modelName, string $tableName);
//
//    /**
//     * Merge using.
//     *
//     * @param string $user
//     * @param string $tableName
//     *
//     * @return string
//     */
//    public static function using(string $user, string $tableName);
//
//    /**
//     * Merge on.
//     *
//     * @param string $target
//     * @param string $source
//     *
//     * @return string
//     */
//    public static function on(string $target, string $source);
//
//    /**
//     * Merge matched.
//     *
//     * @return string
//     */
//    public static function matched();
//
//    /**
//     * Merge update.
//     *
//     * @return string
//     */
//    public static function mergeUpdate();
//
//    /**
//     * Merge update set key.
//     * 
//     * @param string $modelName
//     * @param array $colName
//     *
//     * @return string
//     */
//    public static function mergeSet(string $modelName, array $colName);
//
//    /**
//     * Merge not matched.
//     *
//     * @return string
//     */
//    public static function not();
//
//    /**
//     * Merge insert.
//     *
//     * @return string
//     */
//    public static function mergeInsert();
//
//    /**
//     * Merge insert value.
//     *
//     * @param string $modelType
//     * @param string $modelName
//     * @param string $tableName
//     * @param array $colName
//     *
//     * @return string
//     */
//    public static function mergeValue(string $modelType, string $modelName, string $tableName, array $colName);

}