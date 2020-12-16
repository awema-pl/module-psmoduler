
import Grid from '../../common/components/grid/grid';
import ReloadListActionExtension from '../../common/components/grid/extension/reload-list-extension';
import ExportToSqlManagerExtension from '../../common/components/grid/extension/export-to-sql-manager-extension';
import FiltersResetExtension from '../../common/components/grid/extension/filters-reset-extension';
import SortingExtension from '../../common/components/grid/extension/sorting-extension';
import LinkRowActionExtension from '../../common/components/grid/extension/link-row-action-extension';
import SubmitGridExtension from '../../common/components/grid/extension/submit-grid-action-extension';
import SubmitBulkExtension from '../../common/components/grid/extension/submit-bulk-action-extension';
import BulkActionCheckboxExtension from '../../common/components/grid/extension/bulk-action-checkbox-extension';
import SubmitRowActionExtension from '../../common/components/grid/extension/action/row/submit-row-action-extension';

const $ = window.$;

$(() => {
    const representativesGrid = new Grid('representative');

    representativesGrid.addExtension(new ReloadListActionExtension());
    representativesGrid.addExtension(new ExportToSqlManagerExtension());
    representativesGrid.addExtension(new FiltersResetExtension());
    representativesGrid.addExtension(new SortingExtension());
    representativesGrid.addExtension(new LinkRowActionExtension());
    representativesGrid.addExtension(new SubmitGridExtension());
    representativesGrid.addExtension(new SubmitBulkExtension());
    representativesGrid.addExtension(new BulkActionCheckboxExtension());
    representativesGrid.addExtension(new SubmitRowActionExtension());
});
