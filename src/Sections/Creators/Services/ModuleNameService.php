<?php

namespace AwemaPL\Psmoduler\Sections\Creators\Services;
use Illuminate\Support\Str;

class ModuleNameService
{

    /**
     * Build name
     *
     * @param $blankNameModule
     * @return string
     */
    public function buildName($blankNameModule)
    {
        return Str::ucfirst(mb_strtolower(preg_replace("/[^a-zA-Z]+/", "", $blankNameModule)));
    }
}
