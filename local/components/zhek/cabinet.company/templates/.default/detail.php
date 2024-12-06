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
    <div id='moderation' class="content">
        <div class="row align-items-center pb-3 mb-2">
            <div class="col-5 mb-1 h4"><?= $arResult['DETAIL']['USERNAME'] ?>
                <? //=TruncateText($arResult['DETAIL']['USERNAME'], 50)
                ?>
            </div>
            <div class="col-7 mb-4! card d-flex! flex-row justify-content-between align-items-center px-3 py-2">
                <div class="d-flex flex-column align-items-center">
                    <div class="col-12 h6 my-0 text-uppercase text-blue"><?= TruncateText($arResult['DETAIL']['COURSE'], 28) ?></div>
                    <div class="col-12 h6 my-0 text-secondary"><?= $arResult['DETAIL']['STREAM']['NAME'] . ' - ' . $arResult['DETAIL']['STREAM']['TEXT'] ?></div>

                    <? //gg($arResult['REQUESTS']);
                    ?>
                    <div class="col-12 small fst-italic text-muted">
                        <small>Дата изменения: <?= $arResult['DETAIL']['DATE_UPDATE'] ?></small>
                        <?/*<small>#<?=$arResult['DETAIL']['ID']?> от <?=$arResult['DETAIL']['DATE_CREATE']?></small>*/ ?>
                    </div>
                </div>
                <div class="text-end! text-center col-3! badge! small px-4 py-2 rounded-pill text-bg-light! text-bg-<?= $arResult['DETAIL']['STATUS']['VALUE'] ?> lh-sm">
                    <i class="small"><?= $arResult['DETAIL']['STATUS']['TEXT'] ?></i>
                </div>
            </div>
        </div>


        <!-- <div class="doc_status row g-2" data-user="<?= $arResult['DETAIL']['USER_ID'] ?>">
        <?/*foreach ($arResult['DETAIL']['DOCS'] as $key => $docVal) :?>
        <?if($key=='DOC_CHANGE_NAME' && !$docVal['STATUS'])
						continue;
					?>
        <?=templateItems($docVal, true, $arResult['ADMIN']);?>
        <?endforeach;*/ ?>
    </div> -->

    <? else: ?>
        <font class="errortext">Ошибка доступа</font>
    <? endif; ?>
    <?
    function templateItems($docVal, $useCheck, $admin = false)
    {
        $Format = ToLower(substr($docVal['FILE_NAME'], strrpos($docVal['FILE_NAME'], '.') + 1));
        // dump($docVal[DESC]);
        $result = '<div class="col-12 col-sm-6 col-md-4 col-lg mt-4 mt-md-0">
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
        }
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