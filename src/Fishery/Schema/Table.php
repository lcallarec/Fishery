<?php

namespace Lc\Fishery\Schema;

use Lc\Fishery\Storage\Persistent\PersistentStorageInterface;

/**
 * Class Table
 *
 * @package Lc\Fishery\Schema
 *
 * @author  Laurent Callarec <l.callarec@gmail.com>
 */
class Table
{
    /** @var string */
    private $schemaName;
    /** @var string */
    private $schemaAlias;
    /** @var string */
    private $name;
    /** @var array */
    private $columns;
    /** @var string */
    private $ddl;

    /**
     * @param string $schemaName
     * @param string $schemaAlias
     * @param string $name
     * @param array  $columns
     * @param string $ddl
     */
    public function __construct($schemaName, $schemaAlias, $name, array $columns, $identifiers, $ddl)
    {
        $this->schemaName  = $schemaName;
        $this->schemaAlias = $schemaAlias ;
        $this->name        = $name;
        $this->columns     = $columns;
        $this->ddl         = str_replace('%schema%', $schemaName, $ddl);
    }

    /**
     * @param string $name
     * @return ?
     */
    public function column($name)
    {
        return $this->columns[$name];
    }

    /**
     * @param PersistentStorageInterface $storage
     * @return mixed
     */
    public function create(PersistentStorageInterface $storage)
    {
        return $storage->execute($this->ddl);
    }

    /**
     * @param PersistentStorageInterface $storage
     * @return mixed
     */
    public function destroy(PersistentStorageInterface $storage)
    {
        return $storage->execute(sprintf('DROP TABLE IF EXISTS %s.%s ', $this->schemaName, $this->name));
    }

}
