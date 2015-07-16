<?php

namespace Lc\Fishery\Storage\Persistent;

/**
 * Interface StorageInterface
 *
 * @package Lc\Fishery\Storage
 */
interface PersistentStorageInterface
{
    /**
     * @param array $entity
     *
     * @return bool
     */
    public function persist($in, array $entity);

    public function execute($definition);
}
