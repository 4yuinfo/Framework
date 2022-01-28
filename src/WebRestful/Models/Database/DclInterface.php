<?php

/**
 *
 * @license
 *
 */

namespace Ntch\Framework\WebRestful\Models\Database;


interface DclInterface
{

    /**
     * Commit.
     *
     * @param string $modelType
     * @param string $modelName
     *
     * @return void
     */
    public static function commit(string $modelType, string $modelName);

    /**
     * Rollback.
     *
     * @param string $modelType
     * @param string $modelName
     *
     * @return void
     */
    public static function rollback(string $modelType, string $modelName);

}