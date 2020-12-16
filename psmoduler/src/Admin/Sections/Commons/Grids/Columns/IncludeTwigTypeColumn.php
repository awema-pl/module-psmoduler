<?php

namespace Psmoduler\Admin\Sections\Commons\Grids\Columns;
use PrestaShop\PrestaShop\Core\Grid\Column\AbstractColumn;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IncludeTwigTypeColumn extends AbstractColumn
{

    /**
     * Get column type.
     *
     * @return string
     */
    public function getType()
    {
        return 'psmoduler_include_twig';
    }

    /**
     * Default column options configuration. You can override or extend it needed options.
     *
     * @param OptionsResolver $resolver
     */
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired([
                'view',
                'parameters',
            ])
            ->setAllowedTypes('view', 'string')
            ->setAllowedTypes('parameters', 'array');
    }


}
