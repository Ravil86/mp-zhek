<?

use Bitrix\Main\Application,
    Bitrix\Main\Web\Uri;

if ($arResult['ACCESS']): ?>
    <?
    $request = Application::getInstance()->getContext()->getRequest();
    $uriString = $request->getRequestUri();
    $uri = new Uri($uriString);
    $isArhive = $request->getQuery('arhive');
    $uri->addParams(array("arhive" => "Y"));
    $arhiveUrl = $uri->getUri();

    // dump($arResult['DETAIL']);
    ?>
    <div id='contracts' class="content">
        <?/*<div class="py-3">
            ID: <?= $arResult['DETAIL']['ID'] ?>
        </div>
        */ ?>
        <?/*
        <div class="card mb-3">
            <div class="card-body">
                <div class="row gx-2 align-items-center mb-2">
                    <div class="col-3 pt-2 pb-1">Заказчик:</div>
                    <div class="col-9 text-center pt-2 pb-1 border-bottom">
                        <?= $arResult['DETAIL']['COMPANY_INFO']['NAME'] ?>
                    </div>
                </div>
                <div class="row gx-2 align-items-center mb-2">
                    <div class="col-3 pt-2 pb-1">Юридический адрес:</div>
                    <div class="col-9 text-center pt-2 pb-1 border-bottom"><?= $arResult['DETAIL']['COMPANY_INFO']['ADDRESS'] ?>
                    </div>
                </div>
            </div>
        </div>
        */ ?>
        <?
        // dump($provider);
        function cmp($a, $b)
        {
            return $a['DATE'] <=> $b['DATE'];
        }
        ?>
        <?
        foreach ($arResult['DETAIL']['PROVIDER'] as $key => $provider) : ?>
            <? if ($arResult['SERVICE'][$provider['ID']]['OBJECTS']): ?>
                <div class="row mb-1">
                    <div class="col">
                    </div>
                    <div class="col-auto">
                        <a class="ui-btn ui-btn-primary-dark" href="#">скачать справку</a>
                    </div>
                </div>
            <? endif; ?>
            <div class="card mb-3 border-<?= $provider['COLOR'] ?>">
                <div class="card-body">
                    <div class="row gx-2 align-items-center mb-2">
                        <div class="col-3 pt-2 pb-1">Заказчик:</div>
                        <div class="col-9 text-center pt-2 pb-1 border-bottom">
                            <?= $arResult['DETAIL']['COMPANY_INFO']['NAME'] ?>
                        </div>
                    </div>
                    <div class="row gx-2 align-items-center mb-2">
                        <div class="col-3 pt-2 pb-1">Юридический адрес:</div>
                        <div class="col-9 text-center pt-2 pb-1 border-bottom"><?= $arResult['DETAIL']['COMPANY_INFO']['ADDRESS'] ?>
                        </div>
                    </div>
                    <div class="row gx-2 mb-4">
                        <div class="col-3 pt-2 pb-1">Номер и дата договора:</div>
                        <div class="col-9 text-center pt-2 pb-1 border-bottom text-<?= $provider['COLOR'] ?>">МК №<?= $arResult['DETAIL']['NUMBER'] ?>/<?= $provider['LITERA'] ?>-<?= $arResult['DETAIL']['YEAR'] ?> от <?= $arResult['DETAIL']['DATE'] ?> г.
                        </div>
                    </div>
                    <div class="card! mt-4">
                        <div class="card-body!">
                            <div class="h5 text-center">СПРАВКА № <?= $key + 1 ?> от «<?= FormatDate("j", MakeTimeStamp(time())) ?>» <?= FormatDate("F Y", MakeTimeStamp(time())) ?>г.</div>
                            <? if ($arResult['SERVICE'][$provider['ID']]['OBJECTS']): ?>
                                <table class="table table-sm!">
                                    <thead class="small">
                                        <tr class="text-center">
                                            <th>№<br>п/п</th>
                                            <th>Наименование объекта</th>
                                            <!-- <th>ID</th> -->
                                            <th width="200">Адрес</th>
                                            <th>Вид услуги</th>
                                            <th>ID</th>
                                            <th>Дата очередной поверки ПУ</th>
                                            <th>Номер пломбы ПУ</th>
                                            <th>Ед. изм.</th>
                                            <th>Предыдущие показания ПУ</th>
                                            <th>Текущие показания ПУ</th>
                                            <th>Потребленный объем (разница)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?
                                        $i = 1;

                                        // dump($arResult['PREV_METERS']);
                                        foreach ($arResult['SERVICE'][$provider['ID']]['OBJECTS'] as $key => $value): ?>
                                            <?
                                            $prevMeters = null;
                                            $lastMeters = null;
                                            $potreb = null;

                                            // dump($arResult['PREV_METERS'][$key][$value['COUNTER']['ID']]);

                                            if (is_array($arResult['PREV_METERS'][$key][$value['COUNTER']['ID']])) {
                                                $prevMeters = $arResult['PREV_METERS'][$key][$value['COUNTER']['ID']][0]['METER'];
                                            }
                                            //$prevMeters = array_shift($arResult['PREV_METERS'][$key][$value['COUNTER']['ID']])['METER'];

                                            if (is_array($arResult['LAST_METERS'][$key][$value['COUNTER']['ID']]))
                                                $lastMeters = array_shift($arResult['LAST_METERS'][$key][$value['COUNTER']['ID']])['METER'];


                                            if ($prevMeters && $lastMeters)
                                                $potreb = $lastMeters - $prevMeters;
                                            // $meterLast = array_shift($value['METERS']);

                                            // dump($value['LAST_METERS']);
                                            ?>
                                            <tr>
                                                <td><?= $i; ?></td>
                                                <td><?= $value['INFO']['NAME']; ?></td>
                                                <!-- <th><?= $key
                                                            ?></th> -->
                                                <td><?= $value['INFO']['ADDRESS']; ?></td>
                                                <td><?= $provider['NAME']; ?></td>
                                                <td><?= $value['COUNTER']['ID']; ?></td>
                                                <td><?= $value['COUNTER']['UF_DATE']; ?></td>
                                                <td><?= $value['COUNTER']['UF_NUMBER']; ?></td>
                                                <td><?= $provider['UNIT']; ?></td>
                                                <td><?= $prevMeters; ?></td>
                                                <td><?= $lastMeters; ?></td>
                                                <td><?= $potreb ?></td>
                                            </tr>
                                            <? $i++; ?>
                                        <? endforeach ?>
                                    </tbody>
                                </table>
                            <? endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <? endforeach; ?>
    </div>

<? else: ?>
    <font class="errortext">нет доступа</font>
<? endif; ?>
<?
function templateItems($docVal, $useCheck, $admin = false)
{
    $result = '<div class="card col-12 col-sm-6 col-md-4 col-lg mt-4 mt-md-0">';
    /*$result = '<div class="col-12 col-sm-6 col-md-4 col-lg mt-4 mt-md-0">
		<div class="row"><div class="col doc_title small!">' . ($docVal['DESC'] ?: TruncateText($docVal['NAME'], 28)) . '</div></div>';
        if (!$docVal['STATUS']) {
            $result .= '<div class="row">
				<span class="col">не загружен</span>
			</div>';
        } else {
            $docStatus = $docVal['STATUS'];
            $result .= '<div class="row g-2" >
				<div class="col-auto">';
            $result .= '<a class="mb-2 pt-2 btn btn-sm icon_file border-' . $docStatus['UF_CODE'] . ' text-' . $docStatus['UF_CODE'] . '" data-fancybox ' . ($Format == 'pdf' ? 'data-type="iframe" data-options=\'{"iframe\" : {\"preload\" : true, \"css\" : {\"height\" : \"80%\"}}}\'' : '') . ' data-src="' . $docVal['SRC'] . '" href="javascript:;">
							<i class="bi bi bi-file-earmark-' . $docStatus['UF_XML_ID'] . ' image text-' . $docStatus['UF_CODE'] . '"></i><div class="small"><small><small>' . TruncateText($docStatus['UF_NAME'], 8) . '</small></small></div>
						</a>
					</div>
					<div class="col-8" id="' . $docVal['ID'] . '">';

            $result .= '<div class="mt-1 mb-2 text-muted">';
            $result .= '<div class=""><span>' . TruncateText($docVal['FILE_NAME'], 35) . '</span></div>';
            //$result .= '<div class="text-'.$docStatus['UF_CODE'].'"><i class="bi bi-file-earmark-'.$docStatus['UF_XML_ID'].'" style="font-size: 1.3rem;"></i><span>'.$docStatus['UF_NAME'].'</span></div>';
            $result .= '<div class="info_status_inner info_item_date mt-1 pl-0"><span class="">' . $docVal['DATE'] . '</span></div>';
            if ($docStatus['ID'] == 3 && $docVal['INFO']):
                $result .= '<div class="mt-1 ms-1 info_status_note"><div class="ps-3">' . $docVal['INFO'] . '</div></div>';
            endif;
            $result .= '</div>

					</div>
			</div>';
            if ($admin && $docVal['ID'] || $useCheck && $docStatus['ID'] == 1): ?>
    <?
                $textCheck = !$admin ? 'Одобрить' : 'load';
                $textRefuse = !$admin ? 'Отказать' : 'deny';
                $result .= '<div class="btn_status">
								<a class="btn_yes btn button-outline mb-1' . ($admin ? ' w-auto' : '') . '" data-id="' . $docVal['ID'] . '">
								<i class="bi bi-check2' . (!$admin ? ' fs-6' : '') . '"></i>
									' . $textCheck . '</a>
								<a class="btn_no open_popup btn button-outline mb-1' . ($admin ? ' w-auto' : '') . '" data-id="' . $docVal['ID'] . '">
									<i class="bi bi-x' . (!$admin ? ' fs-6' : '') . '"></i>
									' . $textRefuse . '</a>';
                if ($admin) {
                    $result .= '<a class="btn_load btn button-outline ms-1 mb-1' . ($admin ? ' w-auto' : '') . '" data-id="' . $docVal['ID'] . '">
							<i class="bi bi-arrow-down"></i></a>';
                }
                //<!--button class="btn_no open_popup" data-id="'.$docVal['VALUE'].'">Отказать</!--button-->
                $result .= '</div><!--btn-status-->';
            endif;
        }*/
    $result .= '</div>';
    return $result;
}



$url = $templateFolder . '/ajax.php';
?>
<script>
    /* var url = <?= json_encode($url) ?>;
    //var idIblock = <? //=$arResult['ID_STATUS']
                        ?>;
    function refresh() {
        $('#moderation').load(document.URL + ' #moderation');
    }
    $(document).ready(function() {
        $('#moderation').on('click', '.btn_yes', function(e) {

            var id = $(this).data('id');
            var userid = $(this).closest('.doc_status').data('user');

            //console.log(id)

            if (id > 0) {

                $.ajax({
                    type: "POST",
                    url: url,
                    data: "ID=" + id + "&USER=" + userid + "&STATUS=" + 2,
                    success: function(data) {
                        console.log(data)
                        refresh();
                    }
                });
            }
            e.preventDefault();

        });
        $('#moderation').on('click', '.btn_no', function(e) {
            var id = $(this).data('id');
            var userid = $(this).closest('.doc_status').data('user');
            if (id > 0) {
                idDenied = id;
                userDenied = userid;
            }
            e.preventDefault();

        });
        $('#moderation').on('click', '.btn_load', function(e) {
            var id = $(this).data('id');
            var userid = $(this).closest('.doc_status').data('user');
            if (id > 0) {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: "ID=" + id + "&USER=" + userid + "&STATUS=" + 1,
                    success: function(data) {
                        console.log(data)
                        refresh();
                    }
                });
            }
            e.preventDefault();

        });
        $(".btn_send").on('click', function(e) {
            let COMENTS = $("#reason").val();
            console.log(idDenied)
            console.log(userDenied)
            $.ajax({
                type: "POST",
                url: url,
                data: "ID=" + idDenied + "&USER=" + userDenied + "&STATUS=" + 3 + "&COMENTS=" + COMENTS,
                success: function(data) {
                    console.log(data)
                    refresh();
                }
            });
            $("#reason").val('');
            e.preventDefault();

        });

        $('#moderation').on('click', '.open_popup', function() {
            $('.popup_moderator').css({
                'top': $(window).scrollTop() - 65
            }).addClass('active_popup_moderator');
            $('.bg_popup').fadeIn();
            $('.bg_popup').click(function() {
                $('.popup_moderator').removeClass('active_popup_moderator');
                $('.bg_popup').fadeOut();
                $("#reason").val('');
            });
            $('.btn_send').click(function() {
                $('.popup_moderator').removeClass('active_popup_moderator');
                $('.bg_popup').fadeOut();
            });

            $(window).scroll(function() {
                $('.popup_moderator').css({
                    'top': $(window).scrollTop() - 65
                });
            }).scroll();
        });


        $('#moderation').on('change', '.checkbox', function(e) {
            checkAll();
            $.each($("input[name='checkboxN']"), function() {
                if (this.checked) {
                    document.getElementById('divCheckbox').style.display = 'block';
                    return false;
                } else {
                    document.getElementById('divCheckbox').style.display = 'none';

                }
            });
            e.preventDefault();
        });

    });

    function changeCheckBox(idCheckbox, check) {
        for (let id of idCheckbox) {
            document.getElementById(id).checked = check;
        }
    }*/
</script>