<?php

namespace Psmoduler\Admin\Sections\Representatives\Repositories;

use Psmoduler\Admin\Sections\Representatives\Repositories\Contracts\RepresentativeRepository as RepresentativeRepositoryContract;
class RepresentativeRepository implements RepresentativeRepositoryContract
{
    public function getData()
    {
       return ['wef'];
    }
}
