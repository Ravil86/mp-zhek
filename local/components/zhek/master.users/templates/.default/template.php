<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\Context,
    Bitrix\Main\Type\DateTime,
    Bitrix\Main\Loader,
    Bitrix\Iblock;
use Bitrix\Highloadblock as HL;

?>
<? if ($arResult['ACCESS']): ?>
    <div class="d-flex">
        <div class="col">
            <?
            $APPLICATION->IncludeComponent('bitrix:main.ui.filter', '', [
                'FILTER_ID' => 'filter_' . $arResult['GRID_ID'],
                'GRID_ID' => $arResult['GRID_ID'],
                // 'FILTER' => [],
                'FILTER' => $arResult['GRID']['FILTER'],
                'ENABLE_LIVE_SEARCH' => true,
                'ENABLE_LABEL' => true,
            ]);

            ?>
        </div>
        <div class="col-auto">
            <button class="ui-btn ui-btn-primary mt-2 ms-0" data-bs-toggle="modal" data-bs-target="#addUser">Добавить пользователя</button>
        </div>
    </div>
    <div class="col-md-12">
        <?
        $grid_options = new CGridOptions($arResult["GRID_ID"]);
        $nav_params = $grid_options->GetNavParams(array("nPageSize" => $arParams['PAGE_SIZE']));

        $nav = new Bitrix\Main\UI\PageNavigation($arResult["GRID_ID"]);
        $nav->allowAllRecords(true)
            ->setRecordCount($arResult['GRID']['COUNT']) //Для работы кнопки "показать все"
            ->setPageSize($nav_params['nPageSize'])
            ->initFromUri();

        $snippet = new Bitrix\Main\Grid\Panel\Snippet();
        $controlPanel['GROUPS'][0]['ITEMS'][] = $snippet->getEditButton();
        $controlPanel['GROUPS'][0]['ITEMS'][] = $snippet->getRemoveButton();

        $gridParams = [
            'GRID_ID' => $arResult['GRID_ID'],
            'COLUMNS' => $arResult['GRID']['COLUMNS'],
            'ROWS' => $arResult['GRID']['ROWS'],
            'TOTAL_ROWS_COUNT' => $arResult['GRID']['COUNT'],
            'SHOW_ROW_CHECKBOXES' => false,  //редактирование
            'DEFAULT_PAGE_SIZE' => $arParams['PAGE_SIZE'],
            'NAV_OBJECT' => $nav,
            // 'CURRENT_PAGE' => $nav->getCurrentPage(),
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
            'SHOW_CHECK_ALL_CHECKBOXES' => false,
            'SHOW_ROW_ACTIONS_MENU' => false,
            'SHOW_GRID_SETTINGS_MENU' => true,
            'SHOW_NAVIGATION_PANEL' => true,
            'SHOW_PAGINATION' => true,
            'SHOW_SELECTED_COUNTER' => true,
            'SHOW_TOTAL_COUNTER' => true,
            'SHOW_PAGESIZE' => true,
            'SHOW_ACTION_PANEL' => true,
            'ALLOW_COLUMNS_SORT' => true,
            'ALLOW_COLUMNS_RESIZE' => true,
            'ALLOW_HORIZONTAL_SCROLL' => true,
            'ALLOW_SORT' => true,
            'ALLOW_PIN_HEADER' => true,
            'AJAX_OPTION_JUMP' => 'N',
            'AJAX_OPTION_HISTORY' => 'N',
            'ACTION_PANEL' => $controlPanel,
        ];
        $APPLICATION->IncludeComponent('bitrix:main.ui.grid', '', $gridParams);
        /*$grid_options = new CGridOptions($arResult["GRID_ID"]);
        $nav_params = $grid_options->GetNavParams(array("nPageSize" => $arResult['PAGE_SIZE']));

        $nav = new Bitrix\Main\UI\PageNavigation($arResult["GRID_ID"]);
        $nav->allowAllRecords(true)
            ->setRecordCount($arResult['GRID']['COUNT']) //Для работы кнопки "показать все"
            ->setPageSize($nav_params['nPageSize'])
            ->initFromUri();

        $snippet = new Bitrix\Main\Grid\Panel\Snippet();
        $controlPanel['GROUPS'][0]['ITEMS'][] = $snippet->getEditButton();
        $controlPanel['GROUPS'][0]['ITEMS'][] = $snippet->getRemoveButton();

        $gridParams = [
            'GRID_ID' => $arResult['GRID_ID'],
            'COLUMNS' => $arResult['GRID']['COLUMNS'],
            'ROWS' => $arResult['GRID']['ROWS'],
            'TOTAL_ROWS_COUNT' => $arResult['GRID']['COUNT'],
            'SHOW_ROW_CHECKBOXES' => true,
            'DEFAULT_PAGE_SIZE' => $arResult['PAGE_SIZE'],
            'NAV_OBJECT' => $nav,
            // 'CURRENT_PAGE' => $nav->getCurrentPage(),
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
            'SHOW_CHECK_ALL_CHECKBOXES' => false,
            'SHOW_ROW_ACTIONS_MENU' => false,
            'SHOW_GRID_SETTINGS_MENU' => true,
            'SHOW_NAVIGATION_PANEL' => true,
            'SHOW_PAGINATION' => true,
            'SHOW_SELECTED_COUNTER' => true,
            'SHOW_TOTAL_COUNTER' => true,
            'SHOW_PAGESIZE' => true,
            'SHOW_ACTION_PANEL' => true,
            'ALLOW_COLUMNS_SORT' => true,
            'ALLOW_COLUMNS_RESIZE' => true,
            'ALLOW_HORIZONTAL_SCROLL' => true,
            'ALLOW_SORT' => true,
            'ALLOW_PIN_HEADER' => true,
            'AJAX_OPTION_JUMP' => 'N',
            'AJAX_OPTION_HISTORY' => 'N',
            'ACTION_PANEL' => $controlPanel,
        ];
        $APPLICATION->IncludeComponent('bitrix:main.ui.grid', '', $gridParams);*/
        ?>
    </div>
    <div class="modal fade" id="addUser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form method="post">
                    <div class="modal-header">
                        <h4 class="modal-title">Добавить пользователя</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input class="ui-ctl-element" type="hidden" name="ADD_USER" value="Y">
                        <?= bitrix_sessid_post() ?>
                        <div class="row gx-2">

                        </div>
                        <div class="row gx-2 mt-3">
                            <div class="col-12 col-md">
                                <label>Имя пользователя</label>
                                <div class="ui-ctl ui-ctl-textarea ui-ctl-resize-y ui-ctl-w100">
                                    <textarea class="ui-ctl-element" name="NAME" placeholder="Имя пользователя" required></textarea>
                                </div>
                            </div>
                            <div class="col-12 col-md">
                                <label>E-mail</label>
                                <div class="ui-ctl ui-ctl-textbox ui-ctl-lg! ui-ctl-w100">
                                    <input class="ui-ctl-element" type="email" name="EMAIL" placeholder="E-mail">
                                </div>
                            </div>
                            <!-- <div class="col-12 col-md">
                                <label>Должность</label>
                                <div class="ui-ctl ui-ctl-textbox ui-ctl-lg! ui-ctl-w100">
                                    <input class="ui-ctl-element" type="text" name="FIELDS[WORK_POST]" placeholder="Должность">
                                </div>
                            </div> -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="ui-btn ui-btn-success">Сохранить</button>
                        <button type="button" class="ui-btn ui-btn-link" data-bs-dismiss="modal">Закрыть</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <div class="modal fade" id="selectUser" tabindex="-1" aria-labelledby="selectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form method="post">
                    <div class="modal-header">
                        <h4 class="modal-title">Выбрать пользователя</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input class="ui-ctl-element" type="hidden" name="SELECT_USER" value="Y">
                        <?= bitrix_sessid_post() ?>
                        <div class="ui-ctl ui-ctl-after-icon ui-ctl-dropdown ui-ctl-w75 w-75!">
                            <div class="ui-ctl-after ui-ctl-icon-angle"></div>
                            <select class="ui-ctl-element">
                                <?php
                                foreach ($arResult['USERS'] as $key => $value) {
                                    echo '<option value="' . $key . '"' . ($value['ORG_ID'] ? ' disabled="disabled"' : '') . '>#' . $key . ' - ' . $value["SHORT_NAME"] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="ui-btn ui-btn-success">Сохранить</button>
                        <button type="button" class="ui-btn ui-btn-link" data-bs-dismiss="modal">Закрыть</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
    <? gg($arResult['USERS']); ?>
<? else: ?>
    <font class="errortext">нет доступа</font>
<? endif; ?>