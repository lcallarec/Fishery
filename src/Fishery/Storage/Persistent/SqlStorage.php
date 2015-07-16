<?php

namespace Lc\Fishery\Storage\Persistent;

use Doctrine\DBAL\Connection;
use Lc\Fishery\Storage\Persistent\PersistentStorageInterface;

/**
 * Class SqlStorage
 *
 * @package Lc\Fishery\Storage\Persistent\DoctrineDbal
 *
 * @author  Laurent Callarec <l.callarec@gmail.com>
 */
class SqlStorage implements PersistentStorageInterface
{
    /** @var Connection */
    protected $conn;

    /**
     * @param Connection $conn
     */
    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    public function persist($in, array $values)
    {
        $this->conn->insert($in, $values);

    }

    public function execute($definition)
    {
       return $this->conn->exec($definition);
    }
}
