<?php

namespace Lc\Fishery\Schema;

use Lc\Fishery\Storage\Persistent\PersistentStorageInterface;
use Symfony\Component\Finder\Finder;

/**
 * Class Schema
 *
 * @package Lc\Fishery\Schema
 *
 * @author  Laurent Callarec <l.callarec@gmail.com>
 */
class Schema
{
    /**
     * @var array
     * Hash of aliases => schemaName
     */
    protected $schemas;

    /**
     * @param EntityManager $entities
     * @param $config
     * @param Finder $finder
     * @param $rootPath
     */
    public function __construct(EntityManager $entityManager, $config, Finder $finder, $rootPath)
    {
        $this->entityManager = $entityManager;
        $this->entityManager->setSchema($this);


        $this->finder  = $finder;

        $this->tables  = new Tables();

        $this->schemas = [];

        $this->prepare($config, $rootPath);
    }



    /**
     * Get the tables
     *
     * @return Tables
     */
    public function tables()
    {
        return $this->tables;
    }

    public function getSchemaFor($alias)
    {
        return $this->schemas[$alias];
    }

    /**
     *
     */
    public function buildSchemas()
    {
        foreach ($this->tables as $table) {
            $table->create($this->entityManager->getStorage());
        }
    }

    /**
     *
     */
    public function destroySchemas()
    {
        foreach ($this->tables as $table) {
            $table->destroy($this->entityManager->getStorage());
        }
    }

    /**
     *
     */
    public function __destruct()
    {
        //$this->destroySchemas();
    }

    public function persist($schema, $tableName, array $entity)
    {
        return $this->entityManager->persist($this->getSchemaFor($schema), $tableName, $entity);
    }

    /**
     * @param array  $config
     * @param string $rootPath
     */
    private function prepare(array $config, $rootPath)
    {
        $this->finder->files()->in($rootPath);
        /** @var \SplFileInfo $file */
        foreach ($this->finder as $table) {

            if (!$table->isFile()) {
                continue;
            }

            $schemaAlias = $schemaName = basename(dirname($table->getRealpath()));

            $tableName = str_replace('.sql', '', $table->getFilename());

            if (isset($config[$schemaAlias])) {
                $schemaName  = $config[$schemaAlias]['realname'];
            }

            $this->schemas[$schemaAlias] = $schemaName;

            $this->tables->set(
                '@' . $schemaAlias . '.' . $tableName,
                new Table($schemaName, $schemaAlias, $tableName, $config[$schemaAlias][$tableName], ['id'], $table->getContents())
            );
        }
    }
}
