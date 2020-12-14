<?php
declare(strict_types=1);

namespace Psmoduler\Admin\Sections\Representatives\Grids\Filters;

use PrestaShop\PrestaShop\Core\Search\Filters;
use Psmoduler\Admin\Sections\Representatives\Grids\Definitions\Factories\RepresentativeGridDefinitionFactory;

class RepresentativeFilters extends Filters
{
    protected $filterId = RepresentativeGridDefinitionFactory::GRID_ID;

    /**
     * {@inheritdoc}
     */
    public static function getDefaults()
    {
        return [
            'limit' => 10,
            'offset' => 0,
            'orderBy' => 'id_representative',
            'sortOrder' => 'asc',
            'filters' => [],
        ];
    }
}
