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
				<p class="description align-center pb-2">Предоставление лучших услуг для наших клиентов</p>
				<?require_once('includes/service.php');?>
				
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

	<section class="bg-gray flex-box!  page-margin-top-section">
			<div class="container padding-top-70 padding-bottom-100">
				<div class="row justify-content-center">
				<div class="column column-1-2! col-12 col-md-10">
					<h2 class="box-header">О нашей компании</h2>
					<p class="description align-center">Мы - многофункциональная компания, которая обеспечивает работоспособность<br> инженерной инфраструктуры зданий различного назначения.<br>
					Наша цель - создание комфортных условий для жильцов и посетителей, предоставляя широкий спектр жилищно-коммунальных услуг: от холодного и горячего водоснабжения до утилизации мусора. Мы заботимся о вашем комфорте и безопасности!</p>
					<p class="align-center padding-0 margin-top-27 padding-left-right-35"></p>
					<div class="align-center page-margin-top padding-bottom-16">
						<a class="more" href="/about/" title="Читать подробнее...">Читать подробнее...</a>
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

	<section class="row! full-width container-fluid padding-top-60 padding-bottom-60 align-center">
			<h3><div class="button-label padding-bottom-16" style="clear:both;">Нужна консультация? Перезвоните мне</div> </h3>
			<div class="clearfix d-grid d-sm-block gap-2">
				<a class="more d-inline-block margin-top-15 " href="#" title="Purchase now">Диспетчер</a>
				<a class="more d-inline-block ms-sm-2" href="#" title="Purchase now">Приемная</a>
			</div>
		

			<h2 class="box-header padding-top-60">Новости</h2>
			<div class="row! container padding-top-30">
				<div class="row">
				<div class="column! column-3-4! col-12 col-md-8">
					<div class="d-none d-md-block">
					<?require_once('includes/news_main.php');?>
					</div>
	
			</div>
			
			<div class="column! column-1-4! col-12 col-md-4 cm-smart-column!">
				<div class="cm-smart-column-wrapper!" style="position: static!; bottom: auto!; top: auto!; width: auto!;">
					
					<?require_once('includes/news_small.php');?>
					
				</div>
			</div>
		</div>
		</div>
	</section>


	<section class="full-width container-fluid! bg-gray flex-box">
		<div class="container">
				<div class="row">
					<div class="col-12 col-md-6 padding-bottom-50">
						<div class="row! padding-left-right-70 margin-top-70">
							<h4>Раскрытие информации</h4>
							<?require_once('includes/open_info.php');?>
							<?/*<ul class="list margin-top-20">
								<li class="template-tick-1">Водоотведение</li>
								<li class="template-tick-1">Утилизация ТБО</li>
								<li class="template-tick-1">Транспортировка газа</li>
								<li class="template-tick-1">Теплоснабжение</li>
								<li class="template-tick-1">Технологическое присоединение</li>
								<li class="template-tick-1">Водоснабжение</li>
							</ul>
							*/?>
						</div>
					</div>
					<div class="col-12 col-md-6 padding-bottom-100 bg-green">
						<div class="row! padding-left-right-70 margin-top-70">
							<h2 class="font-weight-300">Остались вопросы?</h2>
							<!-- <h2>Please call now: <a href="tel:2507257052">mp-zhehk-3@yandex.ru</a></h2> -->
							<p class="description margin-top-20">Оставьте заявку. Мы её рассмотрим</p>
							<div class="page-margin-top padding-bottom-16">
								<button type="button" class="more btn! btn-primary" data-bs-toggle="modal" data-bs-target="#feedbackModal">
								Обратная связь
								</button>

							</div>
						</div>
					</div>
				</div>
			</div>
	</section>
<?/*Наши специалисты
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
*/?>

<section class="full-width container padding-top-90 padding-bottom-100">
	<div class="row">
		<h2 class="box-header">Памятки</h2>
			<div class="carousel-container margin-top-25 clearfix">
				<?require_once('includes/projects.php');?>
			</div>
	</div>
</section>

<??>
	<section class="bg-gray full-width padding-top-90 padding-bottom-100">
		<div class="row! container">
			<div class="row">
				<div class="col-12 col-lg-6">
					<h3>Наши ресурсы</h3>
					<?require_once('includes/resources.php');?>
					
				</div>
				<div class="col-12 col-lg-6 mt-4 mt-lg-0">
					<h3>Вакансии</h3>
					<?/*<h3>Отзывы пользователей</h3>*/?>
					<div class="row! d-flex testimonials-container type-small margin-top-40">
						<!-- <div class="cm-carousel-pagination d-none d-md-block"></div> -->
						<?require_once('includes/job.php');?>
						
					</div>
				</div>
			</div>
		</div>
	</section>
<??>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>