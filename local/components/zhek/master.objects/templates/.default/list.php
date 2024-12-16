<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

// $APPLICATION->AddHeadScript("//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js");
// $APPLICATION->AddHeadScript("//cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js");
// // $APPLICATION->AddHeadScript("//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js");

// $APPLICATION->SetAdditionalCSS("//cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css", true);

use Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\Context,
    Bitrix\Main\Type\DateTime,
    Bitrix\Main\Loader,
    Bitrix\Iblock;
use Bitrix\Highloadblock as HL;

?>
<? if ($arResult['ACCESS']): ?>
    <div class="col-md-12">
        <?
        $grid_options = new CGridOptions($arResult["GRID_ID"]);

        //размер страницы в постраничке (передаем умолчания)
        $nav_params = $grid_options->GetNavParams();

        $curentYear = date('Y');
        $lastYear = date('Y', strtotime('-1 year'));

        $nav = new Bitrix\Main\UI\PageNavigation($arResult["GRID_ID"]);
        $nav->allowAllRecords(true)
            ->setRecordCount($arResult['GRID']['COUNT']) //Для работы кнопки "показать все"
            ->setPageSize($nav_params['nPageSize'])
            ->initFromUri();

        // $APPLICATION->IncludeComponent('bitrix:main.ui.filter', '', [
        //     'FILTER_ID' => $arResult['GRID_ID'],
        //     'GRID_ID' => $arResult['GRID_ID'],
        //     'FILTER' => $arResult['GRID']['FILTER'],
        //     'ENABLE_LIVE_SEARCH' => true,
        //     'ENABLE_LABEL' => true,
        // ]);

        $gridParams = [
            'GRID_ID' => $arResult['GRID_ID'],
            'COLUMNS' => $arResult['GRID']['COLUMNS'],
            'ROWS' => $arResult['GRID']['ROWS'],
            // 'FOOTER' => [
            //     'TOTAL_ROWS_COUNT' => $arResult['GRID']['COUNT'],
            // ],
            'SHOW_ROW_CHECKBOXES' => false,
            'NAV_OBJECT' => $nav,
            'AJAX_MODE' => 'Y',
            'AJAX_ID' => 'AJAX_' . $arResult['GRID_ID'],
            // 'AJAX_ID' => \CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''),
            'PAGE_SIZES' => [
                ['NAME' => "5", 'VALUE' => '5'],
                ['NAME' => '10', 'VALUE' => '10'],
                ['NAME' => '20', 'VALUE' => '20'],
                ['NAME' => '50', 'VALUE' => '50'],
                ['NAME' => '100', 'VALUE' => '100']
            ],
            'AJAX_OPTION_JUMP' => 'Y',
            'SHOW_CHECK_ALL_CHECKBOXES' => false,
            'SHOW_ROW_ACTIONS_MENU' => false,
            'SHOW_GRID_SETTINGS_MENU' => true,
            'SHOW_NAVIGATION_PANEL' => true,
            'SHOW_PAGINATION' => true,
            'SHOW_SELECTED_COUNTER' => true,
            'SHOW_TOTAL_COUNTER' => true,
            'SHOW_PAGESIZE' => true,
            'SHOW_ACTION_PANEL' => false,
            'ALLOW_COLUMNS_SORT' => true,
            'ALLOW_COLUMNS_RESIZE' => true,
            'ALLOW_HORIZONTAL_SCROLL' => true,
            'ALLOW_SORT' => true,
            'ALLOW_PIN_HEADER' => true,
            'AJAX_OPTION_HISTORY' => 'N',
        ];
        $APPLICATION->IncludeComponent('bitrix:main.ui.grid', '', $gridParams);
        ?>
    </div>
<? else: ?>
    <font class="errortext">нет доступа</font>
<? endif; ?>