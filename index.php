<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>
<?php
	require_once('includes/slider.php');
?>

<!-- <div class="theme-page">
	<div class="clearfix"> -->

	<section>
		<div class="row! container margin-top-90">
			<div class="row!">
				<h2 class="box-header">Наши услуги</h2>
				<p class="description align-center">Предоставление лучших услуг для наших клиентов</p>
					<?$APPLICATION->IncludeComponent(
						"bitrix:news.list", 
						"carusel", 
						array(
							"DISPLAY_DATE" => "N",
							"DISPLAY_NAME" => "Y",
							"DISPLAY_PICTURE" => "N",
							"DISPLAY_PREVIEW_TEXT" => "Y",
							"AJAX_MODE" => "N",
							"IBLOCK_TYPE" => "info",
							"IBLOCK_ID" => "2",
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
					);?>
				<??>
				
						<!-- <li class="column column-1-3">
							<a href="?page=service_green_spaces_maintenance" title="Green Spaces Maintenance">
								<span class="service-icon features-flower"></span>
							</a>
							<h4><a href="?page=service_green_spaces_maintenance" title="Green Spaces Maintenance">GREEN SPACES MAINTENANCE</a></h4>
							<p>The right maintenance methods will make keeping your garden beautiful.</p>
							<div class="align-center margin-top-42 padding-bottom-16">
								<a class="more" href="#<?//?page=service_green_spaces_maintenance?>" title="Читать подробнее...">Читать подробнее...</a>
							</div>
						</li> -->
			</div>
		</div>
</section>

		<?/*<div class="row full-width bg-gray flex-box page-margin-top-section">
			
		</div>
		*/?>

		<?/*?>
		<div class="row full-width bg-gray flex-box page-margin-top-section">
			<div class="column column-1-2 background-1">
				<a class="flex-hide" href="?page=project_garden_maintenance" title="Garden Maintenance">
					<img src="<?=SITE_TEMPLATE_PATH?>/images/samples/960x750/placeholder.jpg" alt="">
				</a>
			</div>
			<div class="column column-1-2 padding-bottom-96">
				<div class="row padding-left-right-100">
					<h2 class="box-header align-left margin-top-89">REASONS TO CHOOSE US</h2>
					<p class="description">Cleanmate opperates in Ottawa and provides a variety of cleaning services. Choose us because of our reputation for excellence.</p>
					<div class="row page-margin-top">
						<ol class="features-list">
							<li class="column column-1-2">
								<span class="list-number">1</span>
								<h4>SPARKLING CLEAN</h4>
								<p>We keep your home sparkling clean and germ free. Our disinfecting process kills 99% of common bacteria and viruses.</p>
							</li>
							<li class="column column-1-2">
								<span class="list-number">2</span>
								<h4>LEADING TECHNOLOGIES</h4>
								<p>We use safe hospital-grade disinfectants, HEPA filtrations and microfiber cleaning cloths.</p>
							</li>
						</ol>
					</div>
					<div class="row page-margin-top">
						<ol class="features-list">
							<li class="column column-1-2">
								<span class="list-number">3</span>
								<h4>INSURED AND BONDED</h4>
								<p>Our cleaners are insured and bonded so no need to worry about your apartment.</p>
							</li>
							<li class="column column-1-2">
								<span class="list-number">4</span>
								<h4>RELIABLE CREWS</h4>
								<p>Our reliable and stable crews understand your specific clearning service needs.</p>
							</li>
						</ol>
					</div>
				</div>
			</div>
		</div>
		<?*/?>
<??>


		<section class="bg-gray flex-box!  page-margin-top-section">
			<div class="container padding-top-70 padding-bottom-100">
				<div class="row justify-content-center">
				<div class="column column-1-2! col-12 col-md-6">
					<h2 class="box-header">О нашей компании</h2>
					<p class="description align-center">Lorem ipsum dolor sit amet consectetur suspendisse nulla aliquam. Risus rutrum tellus eget ultrices pretium nisi amet facilisis</p>
					<p class="align-center padding-0 margin-top-27 padding-left-right-35"></p>
					<div class="align-center page-margin-top padding-bottom-16">
						<a class="more" href="?page=about" title="Читать подробнее...">Читать подробнее...</a>
					</div>
				</div>
				<!-- <div class="column column-1-4! col-12 col-md-3">
					<a href="<?=SITE_TEMPLATE_PATH?>/images/samples/480x693/placeholder.jpg" class="prettyPhoto cm-preload" title="Gutter Cleaning">
						<img src='<?=SITE_TEMPLATE_PATH?>/images/samples/480x693/placeholder.jpg' alt='img'>
					</a>
				</div> -->
				<!-- <div class="column column-1-4! col-12 col-md-3">
					<div class="row">
						<a href="<?=SITE_TEMPLATE_PATH?>/images/samples/480x320/placeholder.jpg" class="prettyPhoto cm-preload" title="House Cleaning">
							<img src='<?=SITE_TEMPLATE_PATH?>/images/samples/480x320/placeholder.jpg' alt='img'>
						</a>
					</div>
					<div class="row margin-top-30">
						<a href="<?=SITE_TEMPLATE_PATH?>/images/samples/480x320/placeholder.jpg" class="prettyPhoto cm-preload" title="After Renovation Cleaning">
							<img src='<?=SITE_TEMPLATE_PATH?>/images/samples/480x320/placeholder.jpg' alt='img'>
						</a>
					</div>
				</div> -->
				</div>
			</div>
</section>

<??>
		<?/*
		<div class="row full-width bg-gray padding-top-89 padding-bottom-100">
			<div class="row">
				<h2 class="box-header">SIMPLE PLANS. SIMPLE PRICING</h2>
				<p class="description align-center">Cleanmate comes with cost calculator - a unique tool which allows you to easily create<br>price estimation forms to give your client idea of the cost of your service.</p>
				<form class="cost-calculator-container row margin-top-65 prevent-submit" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
					<fieldset class="column column-1-2">
						<div class="cost-calculator-box clearfix">
							<label>Number of bedrooms:</label>
							<input type="hidden" name="bedrooms-label" value="Number of bedrooms">
							<div class="cost-slider-container">
								<input id="bedrooms" class="cost-slider-input" name="bedrooms" type="number" value="4">
								<div class="cost-slider" data-value="4" data-step="1" data-min="0" data-max="8" data-input="bedrooms"></div>
							</div>
						</div>
					</fieldset>
					<fieldset class="column column-1-2">
						<div class="cost-calculator-box clearfix">
							<label>Number of bathrooms:</label>
							<input type="hidden" name="bathrooms-label" value="Number of bathrooms">
							<div class="cost-slider-container">
								<input id="bathrooms" class="cost-slider-input" name="bathrooms" type="number" value="2">
								<div class="cost-slider" data-value="2" data-step="1" data-min="0" data-max="5" data-input="bathrooms"></div>
							</div>
						</div>
					</fieldset>
				</form>
				<div class="row margin-top-30 flex-box">
					<form class="cost-calculator-container column column-1-3" method="post" action="?page=service_calculator">
						<div class="cost-calculator-box cost-calculator-sum clearfix">
							<h4>BASIC SERVICE</h4>
							<span class="cost-calculator-price small-currency margin-top-33" id="basic-service-cost-2"><span class="currency">$</span>0.00</span>
							<input type="hidden" id="basic-service-total-cost" name="basic-service-total-cost">
							<p class="cost-calculator-price-description">/ per month</p>
							<ul class="simple-list margin-top-20">
								<li>Weekly cleaning service</li>
								<li>Up to 1200 square feet</li>
								<li>1 Livingroom cleaning</li>
								<li>Small kitchen (0 - 150 ft2)</li>
								<li>Up to 2 additional rooms cleaning</li>
								
								<!--<li>Wiping the dust from furniture</li>
								<li>Cleaning window sills</li>
								<li>Cleaning mirrors</li>
								<li>Vacumming floors and floor coverings</li>
								<li>Emptying garbage cans</li>
								<li>Cleaning kitchen worktops, sink and hob</li>
								<li>Cleaning toilets and bathroom fixtures</li>-->
							</ul>
							<div class="cost-calculator-submit-container">
								<input type="hidden" class="bedrooms-hidden" name="bedrooms" value="4">
								<input type="hidden" class="bathrooms-hidden" name="bathrooms" value="2">
								<input type="hidden" id="basic-service-clean-area" name="clean-area" value="1200">
								<input type="hidden" id="basic-service-cleaning-frequency" name="cleaning-frequency" value="0.4">
								<input type="hidden" id="basic-service-livingrooms" name="livingrooms" value="1">
								<input type="hidden" id="basic-service-kitchen-size" name="kitchen-size" value="15">
								<input type="hidden" id="basic-service-bathroom-includes" name="bathroom-includes" value="">
								<input type="hidden" id="basic-service-pets" name="pets" value="0">
								<input type="hidden" id="basic-service-cleaning-supplies" name="cleaning-supplies" value="0">
								<input type="hidden" id="basic-service-dining-room" name="dining-room" value="10">
								<input type="hidden" id="basic-service-play-room" name="play-room" value="0">
								<input type="hidden" id="basic-service-laundry" name="laundry" value="0">
								<input type="hidden" id="basic-service-gym" name="gym" value="0">
								<input type="hidden" id="basic-service-garage" name="garage" value="20">
								<input type="hidden" id="basic-service-refrigerator-clean" name="refrigerator-clean" value="20">
								<input type="submit" name="submit_basic" value="Customize" class="more bg-gray">
							</div>
						</div>
					</form>
					<form class="cost-calculator-container column column-1-3" method="post" action="?page=service_calculator">
						<div class="cost-calculator-box cost-calculator-sum clearfix">
							<h4>PREMIUM SERVICE</h4>
							<span class="cost-calculator-price small-currency margin-top-33" id="premium-service-cost-2"><span class="currency">$</span>0.00</span>
							<input type="hidden" id="premium-service-multipler" name="premium-service-multipler" value="2">
							<input type="hidden" id="premium-service-total-cost" name="premium-service-total-cost">
							<p class="cost-calculator-price-description">/ per month</p>
							<ul class="simple-list margin-top-20">
								<li>Weekly cleaning service</li>
								<li>Up to 1200 square feet</li>
								<li>Up to 2 livingrooms cleaning</li>
								<li>Medium kitchen (151 - 250 ft2)</li>
								<li>Pets hair removing</li>
								<li>Up to 5 additional rooms cleaning</li>
								
								<!--<li>The scope of basic service +</li>
								<li>Washing floors</li>
								<li>Cleaning lamps and heaters</li>
								<li>Cleaning of bathroom and kitchen tiles</li>
								<li>Cleaning cabinets fronts</li>
								<li>Cleaning doors and doorframes</li>-->
							</ul>
							<div class="cost-calculator-submit-container">
								<input type="hidden" class="bedrooms-hidden" name="bedrooms" value="4">
								<input type="hidden" class="bathrooms-hidden" name="bathrooms" value="2">
								<input type="hidden" id="premium-service-clean-area" name="clean-area" value="1200">
								<input type="hidden" id="premium-service-cleaning-frequency" name="cleaning-frequency" value="0.4">
								<input type="hidden" id="premium-service-livingrooms" name="livingrooms" value="2">
								<input type="hidden" id="premium-service-kitchen-size" name="kitchen-size" value="20">
								<input type="hidden" id="premium-service-bathroom-includes" name="bathroom-includes" value="10">
								<input type="hidden" id="premium-service-pets" name="pets" value="30">
								<input type="hidden" id="premium-service-cleaning-supplies" name="cleaning-supplies" value="300">
								<input type="hidden" id="premium-service-dining-room" name="dining-room" value="10">
								<input type="hidden" id="premium-service-play-room" name="play-room" value="15">
								<input type="hidden" id="premium-service-laundry" name="laundry" value="0">
								<input type="hidden" id="premium-service-gym" name="gym" value="17">
								<input type="hidden" id="premium-service-garage" name="garage" value="20">
								<input type="hidden" id="premium-service-refrigerator-clean" name="refrigerator-clean" value="20">
								<input type="submit" name="submit_premium" value="Customize" class="more bg-gray">
							</div>
						</div>
					</form>
					<form class="cost-calculator-container column column-1-3" method="post" action="?page=service_calculator">
						<div class="cost-calculator-box cost-calculator-sum clearfix">
							<h4>POST RENOVATION</h4>
							<span class="cost-calculator-price small-currency margin-top-33" id="post-renovation-service-cost-2"><span class="currency">$</span>0.00</span>
							<input type="hidden" id="post-renovation-service-multipler" name="post-renovation-service-multipler" value="3">
							<input type="hidden" id="post-renovation-service-total-cost" name="post-renovation-service-total-cost">
							<p class="cost-calculator-price-description">/ per month</p>
							<ul class="simple-list margin-top-20">
								<li>Weekly cleaning service</li>
								<li>Up to 1200 square feet</li>
								<li>Up to 3 livingrooms cleaning</li>
								<li>Large kitchen (>250 ft2)</li>
								<li>Pets hair removing</li>
								<li>Up to 5 additional rooms cleaning</li>
								<li>Refrigerator cleaning</li>
								
								<!--<li>The scope of premium service +</li>
								<li>Dust removal after renovation</li>
								<li>Cleaning cabinets inside</li>
								<li>Cleaning windows and window frames</li>
								<li>Washing the inside of the refrigerator</li>-->
							</ul>
							<div class="cost-calculator-submit-container">
								<input type="hidden" class="bedrooms-hidden" name="bedrooms" value="4">
								<input type="hidden" class="bathrooms-hidden" name="bathrooms" value="2">
								<input type="hidden" id="post-renovation-clean-area" name="clean-area" value="1200">
								<input type="hidden" id="post-renovation-cleaning-frequency" name="cleaning-frequency" value="0.4">
								<input type="hidden" id="post-renovation-livingrooms" name="livingrooms" value="3">
								<input type="hidden" id="post-renovation-kitchen-size" name="kitchen-size" value="25">
								<input type="hidden" id="post-renovation-bathroom-includes" name="bathroom-includes" value="15">
								<input type="hidden" id="post-renovation-pets" name="pets" value="30">
								<input type="hidden" id="post-renovation-cleaning-supplies" name="cleaning-supplies" value="500">
								<input type="hidden" id="post-renovation-dining-room" name="dining-room" value="10">
								<input type="hidden" id="post-renovation-play-room" name="play-room" value="15">
								<input type="hidden" id="post-renovation-laundry" name="laundry" value="14">
								<input type="hidden" id="post-renovation-gym" name="gym" value="17">
								<input type="hidden" id="post-renovation-garage" name="garage" value="20">
								<input type="hidden" id="post-renovation-refrigerator-clean" name="refrigerator-clean" value="20">
								<input type="submit" name="submit_post_renovation" value="Customize" class="more bg-gray">
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
*/?>
<??>

		<section class="row! full-width container-fluid padding-top-60 padding-bottom-60 align-center">
			<h3><div class="button-label padding-bottom-16" style="clear:both;">Нужна консультация? Перезвоните мне</div> </h3>
			<div class="clearfix">
				<a class="more d-inline-block margin-top-15 " href="#" title="Purchase now">Диспетчер</a>
				<a class="more d-inline-block" style="margin-left:5px" href="#" title="Purchase now">Приемная</a>
			</div>
		

			<h2 class="box-header padding-top-60">Новости</h2>
			<div class="row! container padding-top-30">
				<div class="row">
				<div class="column! column-3-4! col col-md-8">

					<?require_once('includes/news_main.php');?>
					<?/*<ul class="blog news row small! clearfix">
							<li class="column! column-1-2! col-12 col-md-6 mt-0">
								<a href="#<?//?page=post?>" title="How to: deep clean your kitchen" class="post-image">
									<div class="post-date">
										<div class="month">авг</div>
										<h4>24</h4>
									</div>
									<img src="<?=SITE_TEMPLATE_PATH?>/images/samples/480x320/placeholder.jpg" alt="">
								</a>
								<h3><a href="#">Новость 1</a></h3>
								<div class="post-content-details-container clearfix">
									<ul class="post-content-details">
										<li>24 августа, 2024</li>
										<!-- <li>in <a href="?page=category&amp;cat=house_cleaning" title="House Cleaning">House Cleaning</a></li> -->
										<!-- <li>by <a href="?page=team_paige_morgan" title="Paige Morgan">Paige Morgan</a></li> -->
									</ul>
								</div>
								<p>Lorem ipsum dolor sit amet consectetur suspendisse nulla aliquam. Risus rutrum tellus eget ultrices pretium nisi amet facilisis... <a href="?page=post" title="Read more">Читать далее</a></p>
								<div class="post-content-details-container clearfix">
									<ul class="post-content-details">
										<li class="template-display"><a href="#<?//?page=post?>">250</a></li>
										<li class="template-comment"><a href="#<?//?page=post#comments-list?>" title="3 comments">3</a></li>
									</ul>
								</div>
							</li>
							<li class="column! column-1-2! col-12 col-md-6 mt-0">
								<a href="#" title="10 ways to save more &amp; waste less" class="post-image">
									<div class="post-date">
										<div class="month">июл</div>
										<h4>22</h4>
									</div>
									<img src="<?=SITE_TEMPLATE_PATH?>/images/samples/480x320/placeholder.jpg" alt="">
								</a>
								<h3><a href="#">Новость 2</a></h3>
								<div class="post-content-details-container clearfix">
									<ul class="post-content-details">
										<li>22 июля 2024</li>
										<!-- <li>in <a href="?page=category&amp;cat=commercial_cleaning" title="Commercial Cleaning">Commercial Cleaning</a></li>
										<li>by <a href="?page=team_paige_morgan" title="Paige Morgan">Paige Morgan</a></li> -->
									</ul>
								</div>
								<p>Lorem ipsum dolor sit amet consectetur suspendisse nulla aliquam. Risus rutrum tellus eget ultrices pretium nisi amet facilisis... <a href="?page=post" title="Read more">Read more</a></p>
								<div class="post-content-details-container clearfix">
									<ul class="post-content-details">
										<li class="template-display"><a href="?page=post">350</a></li>
										<li class="template-comment"><a href="?page=post#comments-list" title="5 comments">5</a></li>
									</ul>
								</div>
							</li>
						</ul>*/?>
			</div>
			
			<div class="column! column-1-4! col col-md-4 cm-smart-column">
				<div class="cm-smart-column-wrapper!" style="position: static!; bottom: auto!; top: auto!; width: auto!;">
					
					<?require_once('includes/news_small.php');?>
					<!-- <h6 class="box-header page-margin-top">LATEST POSTS</h6> -->
					<?/*<ul class="blog small d-flex flex-column margin-top-30! clearfix">
						<li>
							<a href="#" title="How to: deep clean your kitchen" class="post-image">
								<img src="<?=SITE_TEMPLATE_PATH?>/images/samples/90x90/placeholder.jpg" alt="" style="display: block;">
							</a>
							<div class="post-content!">
								<a href="#" title="How to: deep clean your kitchen">Новость 5</a>
								<ul class="post-details">
									<li class="date">August 24, 2023</li>
								</ul>
							</div>
						</li>
						<li>
							<a href="#" title="10 ways to save more &amp; waste less" class="post-image">
								<img src="<?=SITE_TEMPLATE_PATH?>/images/samples/90x90/placeholder.jpg" alt="" style="display: block;">
							</a>
							<div class="post-content!">
								<a href="#" title="10 ways to save more &amp; waste less">Новость 10</a>
								<ul class="post-details">
									<li class="date">August 22, 2023</li>
								</ul>
							</div>
						</li>
						<li>
							<a href="#" title="Before move-in cleaning checklist" class="post-image">
								<img src="<?=SITE_TEMPLATE_PATH?>/images/samples/90x90/placeholder.jpg" alt="" style="display: block;">
							</a>
							<div class="post-content!">
								<a href="#" title="Before move-in cleaning checklist">Новость 20</a>
								<ul class="post-details">
									<li class="date">July 10, 2022</li>
								</ul>
							</div>
						</li>
					</ul>*/?>
				</div>
			</div>
		</div>
	</div>
</section>


<section class="full-width container-fluid! bg-gray flex-box">
	<div class="container">
			<div class="row">
				<div class="column! column-1-3! col-12 col-md-6 padding-bottom-95">
					<div class="row padding-left-right-70 margin-top-90">
						<h4>Раскрытие информации</h4>
						<ul class="list margin-top-20">
							<li class="template-tick-1">Водоотведение</li>
							<li class="template-tick-1">Утилизация ТБО</li>
							<li class="template-tick-1">Транспортировка газа</li>
							<li class="template-tick-1">Теплоснабжение</li>
							<li class="template-tick-1">Технологическое присоединение</li>
							<li class="template-tick-1">Водоснабжение</li>
						</ul>
					</div>
				</div>
				<div class="column! column-1-3! col-12 col-md-6 padding-bottom-100 bg-green">
					<div class="row padding-left-right-70 margin-top-90">
						<h2 class="font-weight-300">Остались вопросы?</h2>
						<!-- <h2>Please call now: <a href="tel:2507257052">mp-zhehk-3@yandex.ru</a></h2> -->
						<p class="description margin-top-20">Оставьте заявку. Мы её рассмотрим</p>
						<div class="page-margin-top padding-bottom-16">
							<a class="more" href="#<?//?page=contact?>" title="Contact form">Обратная связь</a>
						</div>
					</div>
				</div>
			</div>
		</div>
</section>

<section class="full-width container padding-top-90 padding-bottom-100">
			<div class="row">
				<h2 class="box-header">Наши специалисты</h2>
				<p class="description align-center">Lorem ipsum dolor sit amet consectetur suspendisse nulla aliquam. Risus rutrum tellus eget ultrices pretium nisi amet facilisis</p>
				<div class="team-list item-gray row gx-5 margin-top-65 clearfix">
					<div class="col-12 col-md-4">
						<div class="team-box column column-1-3!">
							<a href="?page=team_karisma_taruda" title="Karisma Taruda" class="image-box">
								<img alt="Karisma Taruda" src="<?=SITE_TEMPLATE_PATH?>/images/samples/260x260/placeholder.jpg">
							</a>
							<h4><a href="?page=team_karisma_taruda" title="Karisma Taruda">Сергей Иваненко</a></h4>
							<p>Слесарь</p>
							<ul class="social-icons align-center">
								<li>
									<a target="_blank" href="#" class="social-twitter" title="twitter"></a>
								</li>
								<li>
									<a target="_blank" href="#" class="social-facebook" title="google-plus"></a>
								</li>
							</ul>
						</div>
					</div>
					<div class="col-12 col-md-4">
						<div class="team-box column column-1-3!">
							<a href="?page=team_paige_morgan" title="Paige Morgan" class="image-box">
								<img alt="Paige Morgan" src="<?=SITE_TEMPLATE_PATH?>/images/samples/260x260/placeholder.jpg">
							</a>
							<h4><a href="?page=team_paige_morgan" title="Paige Morgan">Иван Петрович</a></h4>
							<p>Сантехник</p>
							<ul class="social-icons align-center">
								<li>
									<a target="_blank" href="https://facebook.com/QuanticaLabs" class="social-facebook" title="facebook"></a>
								</li>
								<li>
									<a target="_blank" href="https://twitter.com/QuanticaLabs" class="social-twitter" title="twitter"></a>
								</li>
							</ul>
						</div>
					</div>
					<li class="team-box column column-1-3! col-12 col-md-4">
						<a href="?page=team_celevic_parkhill" title="Celevic Parkhill" class="image-box">
							<img alt="Celevic Parkhill" src="<?=SITE_TEMPLATE_PATH?>/images/samples/260x260/placeholder.jpg">
						</a>
						<h4><a href="?page=team_celevic_parkhill" title="Celevic Parkhill">Мэт Деймонт</a></h4>
						<p>Начальник цеха</p>
						<ul class="social-icons align-center">
							<li>
								<a target="_blank" href="#" class="social-twitter" title="pinterest"></a>
							</li>
							<li>
								<a target="_blank" href="#" class="social-facebook" title="envato"></a>
							</li>
						</ul>
					</li>
				</div>
				<div class="align-center margin-top-65 padding-bottom-16">
					<a class="more" href="#<?//?page=team?>" title="All our technicians">Все наши техники</a>
				</div>
			</div>
</section>

<??>
		<section class="bg-gray full-width padding-top-90 padding-bottom-100">
			<div class="row! container">
				<div class="row">
					<div class="column! column-1-2! col-12 col-lg-6">
						<h3>Наши ресурсы</h3>
						<div class="our-clients-list-container margin-top-40 type-list">
							<ul class="our-clients-list type-list">
								<li class="vertical-align">
									<div class="our-clients-item-container">
										<div class="vertical-align-cell">
											<a target="_blank" href="#">
												<img src="<?=SITE_TEMPLATE_PATH?>/images/logos/centrenerghmao.png" alt="">
											</a>
										</div>
									</div>
								</li>
								<li class="vertical-align">
									<div class="our-clients-item-container">
										<div class="vertical-align-cell">
											<a target="_blank" href="#">
												<img src="<?=SITE_TEMPLATE_PATH?>/images/logos/admhmrn-2.jpg" alt="">
											</a>
										</div>
									</div>
								</li>
								<li class="vertical-align">
									<div class="our-clients-item-container">
										<div class="vertical-align-cell">
											<a target="_blank" href="#">
												<img src="<?=SITE_TEMPLATE_PATH?>/images/logos/jkh86.png" alt="">
											</a>
										</div>
									</div>
								</li>
								<li class="vertical-align">
									<div class="our-clients-item-container">
										<div class="vertical-align-cell">
											<a target="_blank" href="#">
												<img src="<?=SITE_TEMPLATE_PATH?>/images/logos/admhmrn-2.jpg" alt="">
											</a>
										</div>
									</div>
								</li>
								<li class="vertical-align">
									<div class="our-clients-item-container">
										<div class="vertical-align-cell">
											<a target="_blank" href="#">
												<img src="<?=SITE_TEMPLATE_PATH?>/images/logos/jkh86.png" alt="">
											</a>
										</div>
									</div>
								</li>
								<li class="vertical-align">
									<div class="our-clients-item-container">
										<div class="vertical-align-cell">
											<a target="_blank" href="#">
												<img src="<?=SITE_TEMPLATE_PATH?>/images/logos/centrenerghmao.png" alt="">
											</a>
										</div>
									</div>
								</li>
							</ul>
						</div>
					</div>
					<div class="column! column-1-2 col-12 col-lg-6">
						<h3>Отзывы пользователей</h3>
						<div class="row! d-flex testimonials-container type-small margin-top-40">
							<div class="cm-carousel-pagination"></div>
							<ul class="testimonials-list testimonials-carousel autoplay-0 pause_on_hover-1">
								<li class="col-12">
									<p class="template-quote">Lorem ipsum dolor sit amet consectetur suspendisse nulla aliquam. Risus rutrum tellus eget ultrices pretium nisi amet facilisis. Augue eu vulputate tortor egestas cursus vivamus. Commodo dictum iaculis eget massa phasellus ultrices as nunc dignissim. Id nulla amet tincidunt urna sed massa the sed massa ultrices amet eget.</p>
									<div class="author-details-box">
										<div class="author">Даниил Викторович</div>
										<!--<div class="author-details">CLEANING TECHNICAN</div>-->
									</div>
								</li>
								<li class="col-12">
									<p class="template-quote">Lorem ipsum dolor sit amet consectetur suspendisse nulla aliquam. Risus rutrum tellus eget ultrices pretium nisi amet facilisis. Augue eu vulputate tortor egestas cursus vivamus. Commodo dictum iaculis eget massa phasellus ultrices as nunc dignissim. Id nulla amet tincidunt urna sed massa the sed massa ultrices amet eget.</p>
									<div class="author-details-box">
										<div class="author">Петр Семёнович</div>
										<!--<div class="author-details">CLEANING TECHNICAN</div>-->
									</div>
								</li>
								<li class="col-12">
									<p class="template-quote">Lorem ipsum dolor sit amet consectetur suspendisse nulla aliquam. Risus rutrum tellus eget ultrices pretium nisi amet facilisis. Augue eu vulputate tortor egestas cursus vivamus. Commodo dictum iaculis eget massa phasellus ultrices as nunc dignissim. Id nulla amet tincidunt urna sed massa the sed massa ultrices amet eget.</p>
									<div class="author-details-box">
										<div class="author">Анна Ивановна</div>
										<!--<div class="author-details">CLEANING TECHNICAN</div>-->
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<??>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>