<?php

namespace Lc\Fishery\Config\Formatter;

class YamlEntitiesDefinitionFormatter implements DefinitionFormatterInterface
{

    /**
     *
     * @param mixed $definitions Array of schemaAliases => tableNames => $entities
     * @return array
     */
    public function format($definitions)
    {
        $rows = explode(PHP_EOL, $definitions);

        $headers = [];
        foreach(explode('|', trim(array_shift($rows), '|')) as $header) {
            $headers[] = trim($header);
        }

        $entities = [];
        foreach ($rows as $rowNum => $row) {

            foreach(explode('|', trim($row, '|')) as $colNumber => $column) {
                //Empty lines, start and end
                if ($column) {
                    $entities[$rowNum][$headers[$colNumber]] = trim($column);
                }
            }
        }

        return $entities;
    }
}
