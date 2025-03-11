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
            // $APPLICATION->IncludeComponent('bitrix:main.ui.filter', '', [
            //     'FILTER_ID' => 'filter_' . $arResult['GRID_ID'],
            //     'GRID_ID' => $arResult['GRID_ID'],
            //     // 'FILTER' => [],
            //     'FILTER' => $arResult['GRID']['FILTER'],
            //     'ENABLE_LIVE_SEARCH' => true,
            //     'ENABLE_LABEL' => true,
            // ]);
            ?>
        </div>
        <div class="col-auto">
            <!-- <button class="ui-btn ui-btn-primary mt-2 ms-0" data-bs-toggle="modal" data-bs-target="#addCompany">Добавить организацию</button> -->
            <div id="button"></div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="table-responsive">
            <table id="table-reestr" class="table table-bordered align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <?
                        foreach ($arResult['GRID']['COLUMNS'] as $key => $head): ?>
                            <?
                            if (isset($head['colspan']) && $head['colspan'] == 0)
                                continue;
                            ?>
                            <th scope='col' <?= $head['colspan'] ? 'colspan="' . $head['colspan'] . '"' : 'rowspan="2"' ?>><?= $head['text'] ?? $head['name'] ?></th>
                            <?
                            //endif;
                            //echo "<th scope='col'>{$head['name']}</th>";
                            ?>
                        <? endforeach
                        ?>
                    </tr>
                    <tr>
                        <?
                        foreach ($arResult['GRID']['COLUMNS'] as $key => $head): ?>
                            <?
                            if (!isset($head['colspan']))
                                continue;
                            ?>
                            <th scope='col'><?= $head['name'] ?></th>
                            <? //echo "<th scope='col'>{$head['name']}</th>";
                            ?>
                        <? endforeach
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?
                    foreach ($arResult['GRID']['ROWS'] as $rKey => $row) {
                        // gg($arResult['GRID']['ROW_LAYOUT'][$rKey]);
                        echo '<tr>';
                        // gg($row);
                        foreach ($arResult['GRID']['COLUMNS'] as $cKey => $col) : ?>
                            <?
                            $layout =  $arResult['GRID']['ROW_LAYOUT'][$rKey][$cKey];

                            if ($layout['column'] == $col['id']):
                            ?>
                                <? $valueTD = $row['columns'][$col['id']]; ?>
                                <td scope="row" <?= $layout['rowspan'] ? 'rowspan="' . $layout['rowspan'] . '"' : '' ?>
                                    class="<?= is_array($valueTD) ? 'p-0 border-bottom-0 align-baseline!' : '' ?><?= isset($col['colspan']) ? ' text-center' : '' ?>">
                                    <? if (is_array($valueTD)): ?>
                                        <div class="table mb-0 d-flex flex-column gy-1 h-100">
                                            <? foreach ($valueTD as $key => $value): ?>
                                                <div class="table-row align-items-center! px-2 border-bottom"><?= $value ?></div>
                                            <? endforeach ?>
                                        </div>
                                    <? else: ?>
                                        <?= $valueTD; ?>
                                    <? endif; ?>
                                </td>
                            <? endif; ?>
                        <? endforeach ?>
                    <?
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
<? else: ?>
    <font class="errortext">нет доступа</font>
<? endif; ?>
<script>
    const addCompany = new bootstrap.Modal('#addCompany')
    const addUser = new bootstrap.Modal('#addUser')

    var splitButton = new BX.UI.SplitButton({
        text: "Добавить организацию",
        color: BX.UI.Button.Color.PRIMARY,
        // size: BX.UI.Button.Size.LARGE,
        // icon: BX.UI.Button.Icon.BUSINESS,
        menu: {
            items: [{
                    text: "Добавить пользователя",
                    onclick: function(button, event) {
                        addUser.show()
                    },
                },
                // {
                //     delimiter: true
                // },
                // {
                //     text: "Закрыть",
                //     onclick: function(event, item) {
                //         item.getMenuWindow().close();
                //     }
                // }
            ],
        },
        mainButton: {
            onclick: function(button, event) {
                addCompany.show()
            },
            // props: {
            //     href: "/"
            // },
            // tag: BX.UI.Button.Tag.LINK
        },
        menuButton: {
            onclick: function(button, event) {
                button.setActive(!button.isActive());
            },
            props: {
                "data-abc": "123"
            },
            events: {
                mouseenter: function(button, event) {
                    console.log("menu button mouseenter", button, event);
                }
            },
        },
    });

    (function() {
        var container = document.getElementById("button");
        //splitButton.renderTo(container);
    })();
</script>