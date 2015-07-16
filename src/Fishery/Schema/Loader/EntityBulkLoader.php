<?php

namespace Lc\Fishery\Schema\Loader;

use Lc\Fishery\Config\Formatter\DefinitionFormatterInterface;
use Lc\Fishery\Schema\Schema;

class EntityBulkLoader
{

    public function __construct(Schema $schema, DefinitionFormatterInterface $formatter)
    {
        $this->schema = $schema;
        $this->formatter = $formatter;
    }


    public function load(array $definitions)
    {
        foreach($definitions as $schemaAlias => $tables) {

            foreach ($tables as $tableName => $definitions) {

                $entities = $this->formatter->format($definitions);
                foreach ($entities as $entity) {
                    $this->schema->persist($schemaAlias, $tableName, $entity);
                }
            }
        }
    }

}
