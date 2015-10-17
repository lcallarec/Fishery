<?php

namespace Lc\Fishery\Schema;

use Doctrine\Common\Collections\ArrayCollection;
use Lc\Fishery\Storage\Persistent\PersistentStorageInterface;

class EntityManager
{
    /** @var ArrayCollection */
    private $cacheStorage;
    /** @var Schema */
    private $schema;

    /**
     * @param PersistentStorageInterface $storage
     */
    public function __construct(PersistentStorageInterface $storage)
    {
        $this->cacheStorage = new ArrayCollection();
        $this->storage      = $storage;
    }

    public function setSchema(Schema $schema)
    {
        $this->schema = $schema;
    }

    public function persist($schema, $tableName, array $entity)
    {
        $this->cacheStorage->set(sprintf('@%s.%s', $schema, $tableName), $entity);
        $this->storage->persist(sprintf('%s.%s', $schema, $tableName), $entity);
    }

    public function get($schemaAlias, $tableName, array $ids)
    {
        $stmt = $this->storage->execute(
            sprintf(
                'SELECT * FROM %s.%s WHERE 1 = 1',
                (string) $this->schema->getSchemaFor($schemaAlias),
                (string) $tableName,
                'id',
                (string) current($ids)
            )
        );

        return $stmt->fetchAll();
    }

    public function getStorage()
    {
        return $this->storage;
    }

    private function getIdentifiersValues(array $identifiers, array $row)
    {
          $values = [];
        foreach ($identifiers as $identifier) {
            $values[] = $row[$identifier];
        }

        return implode('.', $values);
    }

}
