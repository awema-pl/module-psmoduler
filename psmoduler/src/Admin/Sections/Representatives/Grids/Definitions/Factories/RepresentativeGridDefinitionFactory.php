<?php
declare(strict_types=1);

namespace Psmoduler\Admin\Sections\Representatives\Grids\Definitions\Factories;

use PrestaShop\PrestaShop\Core\Grid\Action\Bulk\BulkActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Bulk\Type\SubmitBulkAction;
use PrestaShop\PrestaShop\Core\Grid\Action\GridActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\RowActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\SubmitRowAction;
use PrestaShop\PrestaShop\Core\Grid\Action\Type\SimpleGridAction;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\BulkActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\AbstractGridDefinitionFactory;
use PrestaShop\PrestaShop\Core\Grid\Filter\Filter;
use PrestaShop\PrestaShop\Core\Grid\Filter\FilterCollection;
use PrestaShopBundle\Form\Admin\Type\SearchAndResetType;
use Psmoduler\Admin\Sections\Commons\Grids\Columns\IncludeTwigTypeColumn;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RepresentativeGridDefinitionFactory extends AbstractGridDefinitionFactory
{
    const GRID_ID = 'representative';

    /**
     * {@inheritdoc}
     */
    protected function getId()
    {
        return self::GRID_ID;
    }

    /**
     * {@inheritdoc}
     */
    protected function getName()
    {
        return $this->trans('Representatives', [], 'Modules.Psmoduler.Admin');
    }

    /**
     * {@inheritdoc}
     */
    protected function getColumns()
    {
        return (new ColumnCollection())
            ->add((new BulkActionColumn('bulk'))
                ->setOptions([
                    'bulk_field' => 'id_representative',
                ])
            )
            ->add((new DataColumn('id_representative'))
                ->setName($this->trans('ID', [], 'Admin.Global'))
                ->setOptions([
                    'field' => 'id_representative',
                ])
            )
            ->add((new IncludeTwigTypeColumn('id_employee'))
                ->setName($this->trans('Employee', [], 'Admin.Global'))
                ->setOptions([
                    'view' =>'@Modules/psmoduler/resources/views/admin/sections/representatives/grids/columns/employee.html.twig',
                    'parameters' =>[],
                ])
            )
            ->add((new DataColumn('code'))
                ->setName($this->trans('Representative code', [], 'Modules.Psmoduler.Admin'))
                ->setOptions([
                    'field' => 'code',
                ])
            )
            ->add((new DataColumn('phone'))
                ->setName($this->trans('Phone', [], 'Modules.Psmoduler.Admin'))
                ->setOptions([
                    'field' => 'phone',
                ])
            )
            ->add((new ActionColumn('actions'))
                ->setName($this->trans('Actions', [], 'Admin.Global'))
                ->setOptions([
                    'actions' => (new RowActionCollection())
                        ->add((new LinkRowAction('edit'))
                            ->setName($this->trans('Edit', [], 'Admin.Actions'))
                            ->setIcon('edit')
                            ->setOptions([
                                'route' => 'psmoduler_admin_representatives_edit',
                                'route_param_name' => 'idRepresentative',
                                'route_param_field' => 'id_representative',
                            ])
                        )
                        ->add((new SubmitRowAction('delete'))
                            ->setName($this->trans('Delete', [], 'Admin.Actions'))
                            ->setIcon('delete')
                            ->setOptions([
                                'method' => 'DELETE',
                                'route' => 'psmoduler_admin_representatives_delete',
                                'route_param_name' => 'idRepresentative',
                                'route_param_field' => 'id_representative',
                                'confirm_message' => $this->trans(
                                    'Delete selected item?',
                                    [],
                                    'Admin.Notifications.Warning'
                                ),
                            ])
                        ),
                ])
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function getFilters()
    {
        return (new FilterCollection())
            ->add((new Filter('id_employee', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->trans('Employee', [], 'Admin.Global'),
                    ],
                ])
                ->setAssociatedColumn('id_employee')
            )
            ->add((new Filter('code', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->trans('Representative code ', [], 'Modules.Psmoduler.Admin'),
                    ],
                ])
                ->setAssociatedColumn('code')
            )
            ->add((new Filter('phone', TextType::class))
                ->setTypeOptions([
                    'required' => false,
                    'attr' => [
                        'placeholder' => $this->trans('Phone', [], 'Modules.Psmoduler.Admin'),
                    ],
                ])
                ->setAssociatedColumn('phone')
            )
            ->add((new Filter('actions', SearchAndResetType::class))
                ->setTypeOptions([
                    'reset_route' => 'admin_common_reset_search_by_filter_id',
                    'reset_route_params' => [
                        'filterId' => self::GRID_ID,
                    ],
                    'redirect_route' => 'psmoduler_admin_representatives_index',
                ])
                ->setAssociatedColumn('actions')
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function getGridActions()
    {
        return (new GridActionCollection())
            ->add((new SimpleGridAction('common_refresh_list'))
                ->setName($this->trans('Refresh list', [], 'Admin.Advparameters.Feature'))
                ->setIcon('refresh')
            )
            ->add((new SimpleGridAction('common_show_query'))
                ->setName($this->trans('Show SQL query', [], 'Admin.Actions'))
                ->setIcon('code')
            )
            ->add((new SimpleGridAction('common_export_sql_manager'))
                ->setName($this->trans('Export to SQL Manager', [], 'Admin.Actions'))
                ->setIcon('storage')
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function getBulkActions()
    {
        return (new BulkActionCollection())
            ->add((new SubmitBulkAction('delete_bulk'))
                ->setName($this->trans('Delete selected', [], 'Admin.Actions'))
                ->setOptions([
                    'submit_route' => 'psmoduler_admin_representatives_bulk_delete',
                    'confirm_message' => $this->trans('Delete selected items?', [], 'Admin.Notifications.Warning'),
                ])
            );
    }
}
