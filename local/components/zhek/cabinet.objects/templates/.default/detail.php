<?

use Bitrix\Main\Application,
    Bitrix\Main\Web\Uri;

if ($arResult['ACCESS']): ?>

    <div class="d-flex">
        <div class="col">

            <div class="card col-10 col-lg-9">
                <div class="card-header">
                    <h2 class="h5 mb-0">
                        <?= $arResult['DETAIL']['OBJECT']['NAME'] ?></h2>
                </div>
                <div class="card-body py-2 lh-sm">
                    <div class="row">
                        <div class="col-5 fs-6 lead text-body-secondary"><em class="small">Договор:</em> <?= $arResult['DETAIL']['OBJECT']['DOGOVOR'] ?></div>
                        <div class="col-7 fs-6 lead text-body-secondary"><em class="small">Адрес:</em> <?= $arResult['DETAIL']['OBJECT']['ADDRESS'] ?></div>

                    </div>

                </div>
            </div>

            <?
            /*$APPLICATION->IncludeComponent('bitrix:main.ui.filter', '', [
            'FILTER_ID' => $arResult['GRID_DETAIL'],
            'GRID_ID' => $arResult['GRID_DETAIL'],
            // 'FILTER' => $arResult['GRID']['FILTER'],
            'ENABLE_LIVE_SEARCH' => true,
            'ENABLE_LABEL' => true,
            // "FILTER_PRESETS" => [
            // "CURRENT_YEAR" => [
            // 	"name" => 'Текущий год',
            // 	"default" => true, // если true - пресет по умолчанию
            // 	"fields" => [
            //         "DATE_CREATE_datesel" => "YEAR",
            //         "DATE_CREATE_year" => $curentYear,
            // 	    ]
            // ],
            // "LAST_YEAR" => [
            //     "name" => 'Прошлый год',
            //     "default" => false,
            //     "fields" => [
            //         "DATE_CREATE_datesel" => "YEAR",
            //         "DATE_CREATE_year" => $lastYear,
            //     ]
            // ]
            // ]
        ]);*/
            ?>
        </div>
        <div class="col-auto">
            <a class="ui-btn ui-btn-no-caps" href="<?= $arResult['FOLDER'] ?>">вернуться назад</a>
        </div>

    </div>
    <div class="card my-2">
        <div class="card-body p-1">
            <?
            $grid_options = new CGridOptions($arResult["GRID_DETAIL"]);

            //размер страницы в постраничке (передаем умолчания)
            // $nav_params = $grid_options->GetNavParams();

            // $curentYear = date('Y');
            // $lastYear = date('Y', strtotime('-1 year'));

            // $nav = new Bitrix\Main\UI\PageNavigation($arResult["GRID_ID"]);
            // $nav->allowAllRecords(true)
            //     ->setRecordCount($arResult['GRID']['COUNT']) //Для работы кнопки "показать все"
            //     ->setPageSize($nav_params['nPageSize'])
            //     ->initFromUri();

            $gridParams = [
                'GRID_ID' => $arResult['DETAIL']['GRID'],
                'COLUMNS' => $arResult['DETAIL']['COLUMNS'],
                'ROWS' => $arResult['DETAIL']['ROWS'],
                // 'FOOTER' => [
                //     'TOTAL_ROWS_COUNT' => $arResult['GRID']['COUNT'],
                // ],
                'SHOW_ROW_CHECKBOXES' => false,
                'NAV_OBJECT' => $nav,
                'AJAX_MODE' => 'Y',
                //'AJAX_ID' => 'AJAX_'.$arResult['GRID_ID'],
                'AJAX_ID' => \CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''),
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
                'SHOW_NAVIGATION_PANEL' => false,
                'SHOW_PAGINATION' => false,
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

    </div>
    <div class="row">
        <div class="col">
        </div>
        <div class="col-auto">
            <a class="ui-btn ui-btn-primary-dark" href="/cabinet/counters/">Перейти "ввод показаний"</a>
            <?/*<a class="ui-btn ui-btn-primary-dark" href="/cabinet/counters/<?= $arResult['DETAIL']['OBJECT']['ID'] ?>/">Перейти "ввод показаний"</a>*/ ?>
        </div>

    </div>
    <?
    // $request = Application::getInstance()->getContext()->getRequest();
    // $uriString = $request->getRequestUri();
    // $uri = new Uri($uriString);
    // $isArhive = $request->getQuery('arhive');
    // $uri->addParams(array("arhive"=>"Y"));
    // $arhiveUrl = $uri->getUri();

    // dump($arResult['DETAIL']);
    //gg($arResult['DETAIL']['ID']);
    ?>
    <? if ($arResult['RELATED'][$arResult['DETAIL']['ID']]): ?>
        <?
        $relateCounter = $arResult['RELATED'][$arResult['DETAIL']['ID']]["UF_COUNTER"];
        // gg($arResult['COMPANY']);
        ?>
        <div class="content card mt-3">
            <div class="card-body">
                <div class="fs-5">Связанные объекты по ПУ #<?= $relateCounter ?> - <span class="fs-4"> <?= $arResult['COUNTERS'][$relateCounter]['UF_NUMBER'] ?>&nbsp;-&nbsp;<?= $arResult['COUNTERS'][$relateCounter]['UF_NAME'] ?></span></div>
                <table class="table mt-3">
                    <thead>
                        <tr>
                            <th scope="col">Объект</th>
                            <th scope="col">Процент занимаемого объема/площади, %</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?
                        foreach ($arResult['RELATED'] as $key => $value): ?>
                            <tr>
                                <td><?= $arResult['OBJECTS'][$value['UF_OBJECT']]['NAME'] ?><br><?= $arResult['COMPANY'][$arResult['OBJECTS'][$value['UF_OBJECT']]['ORG']]["UF_NAME"] ?></td>
                                <td><?= $value['UF_PERCENT'] ?></td>
                            </tr>
                        <? endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <? endif; ?>
<? else: ?>
    <font class="errortext">Ошибка доступа</font>
<? endif; ?>
<?
$url = $templateFolder . '/ajax.php';
?>
<script>
</script>