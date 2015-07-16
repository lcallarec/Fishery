<?php

namespace Lc\Fishery\Config\Formatter;

interface DefinitionFormatterInterface
{
    /**
     * @param mixed $definitions
     * @return mixed
     */
    public function format($definitions);
}
