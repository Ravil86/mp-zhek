<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<?

use Bitrix\Main\Application,
    Bitrix\Main\Web\Uri;

\Bitrix\Main\UI\Extension::load("ui.forms");

if ($arResult['ACCESS']): ?>
    <?
    $request = Application::getInstance()->getContext()->getRequest();
    $uriString = $request->getRequestUri();
    $uri = new Uri($uriString);
    $isArhive = $request->getQuery('arhive');
    $uri->addParams(array("arhive" => "Y"));
    $arhiveUrl = $uri->getUri();
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
        function cmp($a, $b)
        {
            return $a['DATE'] <=> $b['DATE'];
        }

        $dateStr = date('2025-01-01');
        // $dateStr = date('Y-m-d', strtotime('-3 months'));

        $dateEnd = date('Y-m-d', strtotime('last day of last month'));
        // $dateEnd = date("Y-m-d", strtotime('-1 month'));

        $begin = new DateTime($dateStr);
        $end = new DateTime($dateEnd);

        $selectMonth = trim($arResult['MONTH'] . '-' . $arResult['YEAR']);

        $useDate = false;
        //$number = FormatDate("m", MakeTimeStamp('01.' . $arResult['MONTH'] . '.' . $arResult['YEAR']));
        $number = FormatDate("n", MakeTimeStamp('01.' . $arResult['MONTH'] . '.' . $arResult['YEAR']));

        // var_dump(date("m", strtotime("-1 month")));

        if ($arResult['MONTH'] == date("m", strtotime("-1 month")) && $arResult['YEAR'] == date('Y')) {
            $useDate = true;
        }

        if ($arResult['MONTH'] == date('m') && $arResult['YEAR'] == date('Y')) {
            $date = FormatDate("j", MakeTimeStamp(time()));
            $month = date('m');

            $monthYear = FormatDate("f Y", MakeTimeStamp(time()));
            // $monthYear = FormatDate("F Y", MakeTimeStamp(time()));
        } else {
            $month = $arResult['MONTH'];
            // $date = '20';
            // $date =  rand(15, 20);

            $monthYear = FormatDate("f Y", MakeTimeStamp('01.' . $arResult['MONTH'] . '.' . $arResult['YEAR']));
            // $monthYear = FormatDate("F Y", MakeTimeStamp('01.' . $arResult['MONTH'] . '.' . $arResult['YEAR']));
        }

        function render($arResult, $month, $number, $monthYear, $response = false)
        {
            $count = $arResult['DETAIL']['PROVIDER'] ? count($arResult['DETAIL']['PROVIDER']) : 0;

            foreach ($arResult['DETAIL']['PROVIDER'] as $k => $provider) {

                echo '<div class="reestr card me-2 border-' . $provider["COLOR"] . ' ' . ($k + 1 < $count ? " pageBreak mb-3" : "") . '" id="ref' . $provider["ID"] . '">
                    <div class="card-body">
                        <div class="row gx-2 align-items-center mb-2">
                            <div class="col-3 col-xxl-2 pt-2 pb-1">Заказчик:</div>
                            <div class="col-9 text-center pt-2 pb-1 border-bottom">
                                ' . $arResult["COMPANY"]["NAME"] . ', ' . $arResult["COMPANY"]["INN"] . '
                            </div>
                        </div>
                        <div class="row gx-2 align-items-center mb-2">
                            <div class="col-3 col-xxl-2 pt-2 pb-1">Юридический адрес:</div>
                            <div class="col-9 text-center pt-2 pb-1 border-bottom">' . $arResult["COMPANY"]["ADDRESS"] . '
                            </div>
                        </div>
                        <div class="row gx-2 mb-4">
                            <div class="col-3 col-xxl-2 pt-2 pb-1">Номер и дата договора:</div>
                            <div class="col-9 text-center pt-2 pb-1 border-bottom text-' . $provider["COLOR"] . '">МК №' . $arResult["DETAIL"]["NUMBER"] . '/' . $provider["LITERA"] . '-' . $arResult["DETAIL"]["YEAR"] . ' от ' . $arResult["DETAIL"]["DATE"] . ' г.
                            </div>
                        </div>
                        <div class="card! mt-4 mb-1">
                            <div class="card-body!">
                                <div class="h5 text-center">СПРАВКА №' . /*$provider["LITERA"] . '-' . $arResult["DETAIL"]["ID"] . '/' .*/ $number . ' ' . ToLower($monthYear) . 'г </div>';
                if ($arResult["SERVICE"][$provider["ID"]]["OBJECTS"]) {
                    echo '<div class="' . ($response ? 'table-responsive' : '') . '">
                                        <table class="table table-sm! mb-1">
                                            <thead class="small">
                                                <tr class="text-center">
                                                    <th width="40">№<br>п/п</th>
                                                    <th>Наименование объекта</th>
                                                    <!-- <th>ID</th> -->
                                                    <th width="220">Адрес</th>
                                                    <th>Вид услуги</th>
                                                    <!-- <th>ID</th> -->
                                                    <th>Дата очередной поверки ПУ</th>
                                                    <th>Номер ПУ</th>
                                                    <th>Ед. изм.</th>
                                                    <th>Предыдущие показания ПУ</th>
                                                    <th>Текущие показания ПУ</th>
                                                    <th>Потребленный объем (разница)</th>
                                                    <? if ($provider["ID"] == 2): ?>
                                                        <th>Потери</th>
                                                    <? endif; ?>
                                                </tr>
                                            </thead>
                                            <tbody> ';
                    $i = 1;
                    foreach ($arResult["SERVICE"][$provider["ID"]]["OBJECTS"] as $key => $value): ?>
        <?
                        $prevMeters = null;
                        $lastMeters = null;
                        $potreb = null;

                        $potery = $arResult["LOSSES"][$key][$arResult["MONTH_CODE"][$month]] ?: null;

                        $normativ =  $arResult["NORMATIV"][$key][$arResult["MONTH_CODE"][$month]] ?: null;

                        if (is_array($arResult["PREV_METERS"][$key][$value["COUNTER"]["ID"]])) {
                            // dump($arResult["PREV_METERS"][$key]);
                            $prevMeters = $arResult["PREV_METERS"][$key][$value["COUNTER"]["ID"]][0]["METER"];
                        }

                        if (is_array($arResult["LAST_METERS"][$key][$value["COUNTER"]["ID"]])) {

                            $lastMeters = $arResult["LAST_METERS"][$key][$value["COUNTER"]["ID"]][0]["METER"];
                            // $lastMeters = array_shift($arResult["LAST_METERS"][$key][$value["COUNTER"]["ID"]])["METER"];
                        }


                        if ($prevMeters && $lastMeters)
                            $potreb = $lastMeters - $prevMeters;

                        $losses = $arResult["LOSSES"][$key][$arResult["MONTH_CODE"][$month]];

                        echo '
                            <tr class="text-center">
                                <td>' . $i . '</td>
                                <td class="text-start">' . $value["INFO"]["NAME"] . '</td>
                                <td class="text-start">' . $value["INFO"]["ADDRESS"] . '</td>
                                <td>' . $provider["NAME"] . '</td>

                                <td>' . ($value["COUNTER"]["UF_NUMBER"] && $value["COUNTER"]["UF_NUMBER"] != '-' ? $value["COUNTER"]["UF_CHECK"] : '') . '</td>
                                <td>' . $value["COUNTER"]["UF_NUMBER"] . '</td>
                                <td>' . $provider["UNIT"] . '</td>
                                <td>' . ($prevMeters ?? ' - ') . '</td>
                                <td>' . ($lastMeters ?? ' - ') . '</td>
                                <td> ';
                        if ($provider["ID"] == 2) {
                            echo $potreb ?? ($normativ ? '<i class="small">норма</i> ' . $normativ : '-');
                        } else {
                            echo $potreb ?? " - ";
                        }
                        echo '</td>';
                        echo '<td>' . ($provider["ID"] == 2 ? $losses : '') . '</td>';
                        echo '</tr>';
                        $i++;
                    endforeach;
                    echo '</tbody>
                    </table>
                </div>';
                }
                echo '</div>
                        </div>
                        <div class="row gx-2 my-0">
                            <div class="col-3 col-xxl-2 pt-2 pb-1">Потребитель:</div>
                            <div class="col-9"></div>
                        </div>
                        <table class="table table-borderless mb-0">
                            <tr class="text-center">
                                <td width="40"></td>
                                <td width="400" class="border-bottom text-start">' . $arResult["COMPANY"]["RESPONIBLE"]["WORK_POSITION"] . '</td>
                                <td width="20"></td>
                                <td class="border-bottom" width="300"></td>
                                <td width="20"></td>
                                <td class="border-bottom" width="300">' . $arResult["COMPANY"]["RESPONIBLE"]["FULL_NAME"] . '</td>
                                <td></td>
                            </tr>
                            <tr class="text-center small">
                                <td width="40"></td>
                                <td width="400"><sup>Должность ответственного лица</sup></td>
                                <td width="20"></td>
                                <td width="300"></td>
                                <td width="20"></td>
                                <td width="300"><sup>ФИО ответственного лица</sup></td>
                                <td></td>
                            </tr>
                            <tr class="text-center">
                                <td colspan="3"></td>
                                <td class="pb-0">"___" __________________ ' . date("Y") . 'г.</td>
                                <td colspan="3"></td>
                            </tr>
                            <tr class="text-center small">
                                <td colspan="3"></td>
                                <td><sup>МП</sup></td>
                                <td colspan="3"></td>
                            </tr>
                        </table>
                    </div>
                </div>';
            }
        }
        ?>
        <div class="row mb-1">
            <div class="col">
                <div class="ui-ctl ui-ctl-after-icon ui-ctl-dropdown">
                    <div class="ui-ctl-after ui-ctl-icon-angle"></div>
                    <select class="ui-ctl-element" onchange="redirect(this)">
                        <!-- <option value=""></option> -->
                        <?= LKClass::setMonth($selectMonth, $begin, $end);
                        ?>
                    </select>
                    <script>
                        function redirect(list) {
                            var selection = list.options[list.selectedIndex].value
                            var folder = '<?= $arResult['FOLDER'] ?>'
                            var contract = '<?= $arResult['CONTRACT'] ?>'
                            let split = selection.split('-')

                            // console.log('this.value', selection.split('-'));

                            document.location.href = folder + '/' + contract + '/' + split[1] + '/' + split[0];
                        }
                    </script>
                </div>
            </div>
            <div class="col-auto me-2">
                <? if ($useDate || $arResult['ADMIN'] || $arResult['MODERATOR']): ?>
                    <!-- <a class="ui-btn ui-btn-primary-dark" onclick="saveButton('<? //= $month
                                                                                    ?>')">Скачать справки</a> -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#renderPDF" onclick="saveButton('<?= $month ?>')">
                        Справки
                    </button>
                    <!-- Modal -->
                    <div class="modal fade" id="renderPDF" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-fullscreen-xl-down modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Скачать справки</h1>
                                    <a class="ui-btn ui-btn-primary-dark ms-4" onclick="saveButton('<?= $month ?>')">Скачать</a>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div id="reestr">
                                        <?= render($arResult, $month, $number, $monthYear); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <? endif; ?>
            </div>
        </div>
        <?= render($arResult, $month, $number, $monthYear, true); ?>
    </div>
<? else: ?>
    <font class="errortext">нет доступа</font>
<? endif; ?>
<?
// $html = <<<HTML
// 	<!DOCTYPE html>
// 	<html>
// 		<head>
// 			<meta charset="utf-8">
// 			<title>Test Page</title>
// 		</head>
// 		<body>
// 			<p>Привет, <span style="color: green">Мир</span>!</p>
// 		</body>
// 	</html>
// HTML;
// reference the Dompdf namespace

// use Dompdf\Dompdf;

// $dompdf = new Dompdf();
// $dompdf->set_option('isRemoteEnabled', TRUE);
// $dompdf->setPaper('A4', 'portrait');
// $dompdf->loadHtml($html, 'UTF-8');
// $dompdf->render();

// Вывод файла в браузер:
// $dompdf->stream();

// Или сохранение на сервере:
// $pdf = $dompdf->output();
// file_put_contents(__DIR__ . '/schet.pdf', $pdf);


$url = $templateFolder . '/ajax.php';
?>
<script>
    function saveButton(month) {

        console.log('month', month);

        var filename = '#' + <?= $arResult['COMPANY']['ID'] ?> + '_' + month + '_' + <?= $arResult['DETAIL']['YEAR'] ?> + '_<?= $arResult['DETAIL']['UF_NUMBER'] ?>_<?= TruncateText($arResult['COMPANY']['NAME'], 55) ?>.pdf'

        element = document.getElementById('reestr');
        // var element = document.getElementById('ref' + event);
        // console.log(element);
        var opt = {
            margin: 7,
            filename: filename,
            // image: {
            //     type: 'jpeg',
            //     quality: 0.98
            // },
            html2canvas: {
                scale: 3
            },
            jsPDF: {
                unit: 'mm',
                // format: 'letter',
                format: 'a4',
                orientation: 'landscape',
            },
            pagebreak: {
                // mode: 'avoid-all',
                // mode: ['avoid-all', 'css', 'legacy'],
                after: '.pageBreak',
                // after: '#pageBreak',
                // before: '#page2el'
            }
        };

        // New Promise-based usage:
        html2pdf().set(opt).from(element).save();

        // // Old monolithic-style usage:
        // html2pdf(element, opt);
    }

    /* var url = <? //= json_encode($url)
                    ?>;
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