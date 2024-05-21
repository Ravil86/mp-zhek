<!-- Slider Revolution -->
<?$APPLICATION->IncludeComponent(
						"bitrix:news.list", 
						"slider", 
						array(
							"DISPLAY_DATE" => "N",
							"DISPLAY_NAME" => "Y",
							"DISPLAY_PICTURE" => "N",
							"DISPLAY_PREVIEW_TEXT" => "Y",
							"AJAX_MODE" => "N",
							"IBLOCK_TYPE" => "info",
							"IBLOCK_ID" => "3",
							"NEWS_COUNT" => "20",
							"SORT_BY1" => "SORT",
							"SORT_ORDER1" => "ASC",
							"SORT_BY2" => "ID",
							"SORT_ORDER2" => "ASC",
							"FILTER_NAME" => "",
							"FIELD_CODE" => array(
								0 => "ID",
								1 => "",
							),
							"PROPERTY_CODE" => array(
								0 => "",
								1 => "DESCRIPTION",
								2 => "",
							),
							"CHECK_DATES" => "Y",
							"DETAIL_URL" => "",
							"PREVIEW_TRUNCATE_LEN" => "",
							"ACTIVE_DATE_FORMAT" => "d.m.Y",
							"SET_TITLE" => "N",
							"SET_BROWSER_TITLE" => "N",
							"SET_META_KEYWORDS" => "N",
							"SET_META_DESCRIPTION" => "N",
							"SET_LAST_MODIFIED" => "N",
							"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
							"ADD_SECTIONS_CHAIN" => "N",
							"HIDE_LINK_WHEN_NO_DETAIL" => "N",
							"PARENT_SECTION" => "",
							"PARENT_SECTION_CODE" => "",
							"INCLUDE_SUBSECTIONS" => "N",
							"CACHE_TYPE" => "A",
							"CACHE_TIME" => "36000",
							"CACHE_FILTER" => "N",
							"CACHE_GROUPS" => "N",
							"DISPLAY_TOP_PAGER" => "N",
							"DISPLAY_BOTTOM_PAGER" => "N",
							"PAGER_TITLE" => "Услуги",
							"PAGER_SHOW_ALWAYS" => "N",
							"PAGER_TEMPLATE" => "",
							"PAGER_DESC_NUMBERING" => "N",
							"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
							"PAGER_SHOW_ALL" => "N",
							"PAGER_BASE_LINK_ENABLE" => "N",
							"SET_STATUS_404" => "N",
							"SHOW_404" => "N",
							"MESSAGE_404" => "",
							"PAGER_BASE_LINK" => "",
							"PAGER_PARAMS_NAME" => "arrPager",
							"AJAX_OPTION_JUMP" => "N",
							"AJAX_OPTION_STYLE" => "Y",
							"AJAX_OPTION_HISTORY" => "N",
							"AJAX_OPTION_ADDITIONAL" => "",
							"COMPONENT_TEMPLATE" => "carusel",
							"STRICT_SECTION_CHECK" => "N",
							"FILE_404" => ""
						),
						false,
						['HIDE_ICONS'=> true]
					);
			?>
<?/*
<div class="revolution-slider-container">
	<div class="revolution-slider" data-version="5.4.5" style="display: none;">
		<ul>
			<!-- SLIDE 1 -->
			<li data-transition="fade" data-masterspeed="500" data-slotamount="1" data-delay="6000">
				<!-- MAIN IMAGE -->
				<img src="<?=SITE_TEMPLATE_PATH?>/images/slider/placeholder.jpg" alt="slidebg1" data-bgfit="cover">
				<!-- LAYERS -->
				<!-- LAYER 01 -->
				<div class="tp-caption"
					data-frames='[{"delay":1500,"speed":1500,"from":"y:-40;o:0;","ease":"easeInOutExpo"},{"delay":"wait","speed":500,"to":"o:0;","ease":"easeInOutExpo"}]'
					data-x="center"
					data-y="['130', '197', '120', '148']"
					>
					<h4>Какое-то описание</h4>
				</div>
				<!-- LAYER 02 -->
				<div class="tp-caption"
					data-frames='[{"delay":900,"speed":2000,"from":"y:40;o:0;","ease":"easeInOutExpo"},{"delay":"wait","speed":500,"to":"o:0;","ease":"easeInOutExpo"}]'
					data-x="center"
					data-y="['173', '253', '160', '190']"
					>
					<h2><a href="#<?//?page=service_calculator?>" title="Estimate Total Costs">Теплоснабжение</a></h2>
				</div>
				<!-- LAYER 03 -->
				<!-- <div class="tp-caption"
					data-frames='[{"delay":1100,"speed":2000,"from":"y:40;o:0;","ease":"easeInOutExpo"},{"delay":"wait","speed":500,"to":"o:0;","ease":"easeInOutExpo"}]'
					data-x="center"
					data-y="['245', '308', '196', '220']"
					>
					<h2 class="slider-subtitle"><strong>ещё что-то</strong></h2>
				</div> -->
				<!-- LAYER 04 -->
				<div class="tp-caption"
					data-frames='[{"delay":1500,"speed":1500,"from":"y:40;o:0;","ease":"easeInOutExpo"},{"delay":"wait","speed":500,"to":"o:0;","ease":"easeInOutExpo"}]'
					data-x="center"
					data-y="['316', '418', '264', '283']"
					>					
					<div class="align-center">
						<a class="more" href="#" title="Подробнее...">Подробнее...</a>
					</div>
				</div>
				<!-- / -->
			</li>
			<!-- SLIDE 2 -->
			<li data-transition="fade" data-masterspeed="500" data-slotamount="1" data-delay="6000">
				<!-- MAIN IMAGE -->
				<img src="<?=SITE_TEMPLATE_PATH?>/images/slider/placeholder.jpg" alt="slidebg2" data-bgfit="cover">
				<!-- LAYERS -->
				<!-- LAYER 01 -->
				<div class="tp-caption customin customout"
					data-frames='[{"delay":1500,"speed":1500,"from":"y:-40;o:0;","ease":"easeInOutExpo"},{"delay":"wait","speed":500,"to":"o:0;","ease":"easeInOutExpo"}]'
					data-x="center"
					data-y="['130', '197', '120', '148']"
					>
					<h4>Какое-то описание</h4>
				</div>
				<!-- LAYER 02 -->
				<div class="tp-caption customin customout"
					data-frames='[{"delay":900,"speed":2000,"from":"y:40;o:0;","ease":"easeInOutExpo"},{"delay":"wait","speed":500,"to":"o:0;","ease":"easeInOutExpo"}]'
					data-x="center"
					data-y="['173', '253', '160', '190']"
					>
					<h2><a href="#" title="Estimate Total Costs">Транспортировка газа</a></h2>
				</div>
				<!-- LAYER 03 -->
				<!-- <div class="tp-caption customin customout"
					data-frames='[{"delay":1100,"speed":2000,"from":"y:40;o:0;","ease":"easeInOutExpo"},{"delay":"wait","speed":500,"to":"o:0;","ease":"easeInOutExpo"}]'
					data-x="center"
					data-y="['245', '308', '196', '220']"
					>
					<h2 class="slider-subtitle"><strong>ещё что-то</strong></h2>
				</div> -->
				<!-- LAYER 04 -->
				<div class="tp-caption customin customout"
					data-frames='[{"delay":1500,"speed":1500,"from":"y:40;o:0;","ease":"easeInOutExpo"},{"delay":"wait","speed":500,"to":"o:0;","ease":"easeInOutExpo"}]'
					data-x="center"
					data-y="['316', '418', '264', '283']"
					>
					<div class="align-center">
						<a class="more" href="#<?//?page=service_calculator?>" title="Подробнее...">Подробнее...</a>
					</div>
				</div>
				<!-- / -->
			</li>
			
			<!-- SLIDE 3 -->
			<li data-transition="fade" data-masterspeed="500" data-slotamount="1" data-delay="6000">
				<!-- MAIN IMAGE -->
				<img src="<?=SITE_TEMPLATE_PATH?>/images/slider/placeholder.jpg" alt="slidebg3" data-bgfit="cover">
				<!-- LAYERS -->
				<!-- LAYER 01 -->
				<div class="tp-caption customin customout"
					data-frames='[{"delay":1500,"speed":1500,"from":"y:-40;o:0;","ease":"easeInOutExpo"},{"delay":"wait","speed":500,"to":"o:0;","ease":"easeInOutExpo"}]'
					data-x="center"
					data-y="['130', '197', '120', '148']"
					>
					<h4>Какое-то описание</h4>
				</div>
				<!-- LAYER 02 -->
				<div class="tp-caption customin customout"
					data-frames='[{"delay":900,"speed":2000,"from":"y:40;o:0;","ease":"easeInOutExpo"},{"delay":"wait","speed":500,"to":"o:0;","ease":"easeInOutExpo"}]'
					data-x="center"
					data-y="['173', '253', '160', '190']"
					>
					<h2><a href="#<?//?page=service_calculator?>" title="Estimate Total Costs">Водоснабжение</a></h2>
				</div>
				<!-- LAYER 03 -->
				<!-- <div class="tp-caption customin customout"
					data-frames='[{"delay":1100,"speed":2000,"from":"y:40;o:0;","ease":"easeInOutExpo"},{"delay":"wait","speed":500,"to":"o:0;","ease":"easeInOutExpo"}]'
					data-x="center"
					data-y="['245', '308', '196', '220']"
					>
					<h2 class="slider-subtitle"><strong>FOR PERFECTION</strong></h2>
				</div> -->
				<!-- LAYER 04 -->
				<div class="tp-caption customin customout"
					data-frames='[{"delay":1500,"speed":1500,"from":"y:40;o:0;","ease":"easeInOutExpo"},{"delay":"wait","speed":500,"to":"o:0;","ease":"easeInOutExpo"}]'
					data-x="center"
					data-y="['316', '418', '264', '283']"
					>
					<div class="align-center">
						<a class="more" href="#<?//?page=service_calculator?>" title="Подробнее...">Подробнее...</a>
					</div>
				</div>
				<!-- / -->
			</li>
		</ul>
	</div>
</div>
*/?>
<!--/-->