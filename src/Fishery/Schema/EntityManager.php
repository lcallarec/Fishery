<?php

namespace Lc\Fishery\Schema;

use Doctrine\Common\Collections\ArrayCollection;
use Lc\Fishery\Storage\Persistent\PersistentStorageInterface;

class EntityManager
{
    /** @var ArrayCollection */
    private $proxyStorage;

    public function __construct(PersistentStorageInterface $storage)
    {
        $this->proxyStorage = new ArrayCollection();
        $this->storage      = $storage;
    }

    public function persist($schema, $tableName, array $entity)
    {
        $this->proxyStorage->set(sprintf('@%s.%s', $schema, $tableName), $entity);
        $this->storage->persist(sprintf('%s.%s', $schema, $tableName), $entity);
    }

    public function get($schema, $tableName, $id)
    {
        return ['id' => 5, 'name' => 'coco'];
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
