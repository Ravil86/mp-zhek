<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$GLOBALS["IS_HOME"] = $APPLICATION->GetCurPage(true) === SITE_DIR . "index.php";
use Bitrix\Main\Page\Asset;
$rsSites = CSite::GetByID(SITE_ID);
$arSite = $rsSites->Fetch();
// dump($arSite);
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?=$arSite['SITE_NAME']?></title>
		<!--meta-->
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.2" />
		<!-- <meta name="format-detection" content="telephone=no" /> -->
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<?$APPLICATION->ShowHead(); ?>
		<!--slider revolution-->
		<link rel="stylesheet" type="text/css" href="rs-plugin/css/settings.css">
		<?/*
		<!--style-->
		<link rel="stylesheet" type="text/css" href="<?=SITE_TEMPLATE_PATH?>/css/reset.css">
		<link rel="stylesheet" type="text/css" href="<?=SITE_TEMPLATE_PATH?>/css/superfish.css">
		<link rel="stylesheet" type="text/css" href="<?=SITE_TEMPLATE_PATH?>/css/prettyPhoto.css">
		<link rel="stylesheet" type="text/css" href="<?=SITE_TEMPLATE_PATH?>/css/jquery.qtip.css">
		<link rel="stylesheet" type="text/css" href="<?=SITE_TEMPLATE_PATH?>/css/main.css">
		<link rel="stylesheet" type="text/css" href="<?=SITE_TEMPLATE_PATH?>/css/animations.css">
		<link rel="stylesheet" type="text/css" href="<?=SITE_TEMPLATE_PATH?>/css/responsive.css">
		<link rel="stylesheet" type="text/css" href="<?=SITE_TEMPLATE_PATH?>/css/odometer-theme-default.css">
		<!--fonts-->
		<link rel="stylesheet" type="text/css" href="<?=SITE_TEMPLATE_PATH?>/fonts/fonts.css">
		<!-- <link rel="stylesheet" type="text/css" href="fonts/features/style.css"> -->
		<link rel="stylesheet" type="text/css" href="<?=SITE_TEMPLATE_PATH?>/fonts/template/style.css">
		<link rel="stylesheet" type="text/css" href="<?=SITE_TEMPLATE_PATH?>/fonts/social/style.css">
*/?>
		<link rel="shortcut icon" href="<?=SITE_TEMPLATE_PATH?>/images/favicon.ico">
		<?
		$arCss = array(
			'//cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
			SITE_TEMPLATE_PATH.'/css/reset.css',
			SITE_TEMPLATE_PATH.'/css/superfish.css',

			SITE_TEMPLATE_PATH.'/css/prettyPhoto.css',
			SITE_TEMPLATE_PATH.'/css/jquery.qtip.css',
			SITE_TEMPLATE_PATH.'/css/main.css',
			SITE_TEMPLATE_PATH.'/css/animations.css',
			SITE_TEMPLATE_PATH.'/css/responsive.css',
			//fonts
			SITE_TEMPLATE_PATH.'/fonts/fonts.css',
			SITE_TEMPLATE_PATH.'/fonts/template/style.css',
			SITE_TEMPLATE_PATH.'/fonts/social/style.css',
		);

		foreach ($arCss as $key => $css) {
			Asset::getInstance()->addCss($css);
		}

		$arJs = array(
			SITE_TEMPLATE_PATH.'/js/jquery-3.3.1.min.js',
			// SITE_TEMPLATE_PATH.'/js/jquery-1.12.4.min.js',
			SITE_TEMPLATE_PATH.'/js/jquery-migrate-1.4.1.min.js',
			// //--slider revolution
			SITE_TEMPLATE_PATH.'/lib/jquery.themepunch.tools.min.js',	//rs-plugin
			SITE_TEMPLATE_PATH.'/lib/jquery.themepunch.revolution.min.js',	//rs-plugin
			SITE_TEMPLATE_PATH.'/js/jquery.ba-bbq.min.js',
			SITE_TEMPLATE_PATH.'/js/jquery-ui-1.12.1.custom.min.js',
			SITE_TEMPLATE_PATH.'/js/jquery.ui.touch-punch.min.js',
			SITE_TEMPLATE_PATH.'/js/jquery.isotope.min.js',
			SITE_TEMPLATE_PATH.'/js/jquery.easing.1.3.min.js',
			SITE_TEMPLATE_PATH.'/js/jquery.carouFredSel-6.2.1-packed.js',
			SITE_TEMPLATE_PATH.'/js/jquery.touchSwipe.min.js',
			SITE_TEMPLATE_PATH.'/js/jquery.transit.min.js',
			SITE_TEMPLATE_PATH.'/js/jquery.timeago.js',
			SITE_TEMPLATE_PATH.'/js/jquery.hint.min.js',
			SITE_TEMPLATE_PATH.'/js/jquery.costCalculator.min.js',
			SITE_TEMPLATE_PATH.'/js/jquery.parallax.min.js',
			SITE_TEMPLATE_PATH.'/js/jquery.prettyPhoto.js',
			SITE_TEMPLATE_PATH.'/js/jquery.qtip.min.js',
			SITE_TEMPLATE_PATH.'/js/jquery.blockUI.min.js',
			SITE_TEMPLATE_PATH.'/js/main.js',

			// '//cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js',

			// <script type="text/javascript" src="js/odometer.min.js"></script>

			//<script type="text/javascript" src="//cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->

			//SITE_TEMPLATE_PATH.'/js/jquery.fancybox.min.js',
			//SITE_TEMPLATE_PATH.'/js/masonry.pkgd.min.js',
			//SITE_TEMPLATE_PATH.'/js/imagesloaded.pkgd.min.js',
    	);

		foreach ($arJs as $key => $js) {
			Asset::getInstance()->addJs($js);
		}
	?>
	</head>
	<div class="d-none d-lg-block">
		<?$APPLICATION->ShowPanel(); ?>
	</div>
	
	<?//$_COOKIE["cm_header_type"] = 'type_1';?>
	<body class="<?//php echo (isset($_COOKIE['cm_layout']) && $_COOKIE['cm_layout']=="boxed" ? (isset($_COOKIE['cm_layout_style']) && $_COOKIE['cm_layout_style']!="" ? $_COOKIE['cm_layout_style'] . ' ' . $_COOKIE['cm_image_overlay'] : 'image-1 overlay') : ''); echo (isset($_COOKIE['cm_header_top_bar']) && $_COOKIE['cm_header_top_bar']=="yes" ? ' with-topbar' : ''); ?>">
	
	<main class="site-container<?//php echo ($_COOKIE['cm_layout']=="boxed" ? ' boxed' : ''); ?>">
		<!--<div class="site-container boxed">-->
		<?// TopBar start
			?>
			<div class="header-top-bar-container clearfix">
				<div class="header-top-bar container">
					<ul class="contact-details clearfix">
						<li class="template-location">
							г. Ханты-Мансийск, ул.Боровая 9
						</li>
						<li class="template-mobile">
							<a href="tel:+73465648644">+7 (3465) 648 644</a>
						</li>
						<li class="template-clock">
							Рабочее время: пн -сат (с 8:00 до 18:00)
						</li>
					</ul>
					<ul class="social-icons">
						<li class="show-on-mobiles">
							<a class="template-search" href="#" title="Search"></a>
							<form class="search">
								<input type="text" name="s" placeholder="Type and hit enter..." value="Type and hit enter..." class="search-input hint">
								<fieldset class="search-submit-container">
									<span class="template-search"></span>
									<input type="submit" class="search-submit" value="">
								</fieldset>
								<input type="hidden" name="page" value="search">
							</form>
						</li>
					</ul>
				</div>
				<a href="#" class="header-toggle template-arrow-vertical-3"></a>
			</div>
			<?php
			// TopBar end
			?>
			<div class="header-container sticky<?//php echo (isset($_COOKIE['cm_menu_type']) && $_COOKIE['cm_menu_type']=="no_sticky" ? '' : ' sticky');?>">
			<!--<div class="header-container sticky">-->
				<div class="header container clearfix">
					<?php
					/*if(empty($_COOKIE["cm_header_type"]) || $_COOKIE["cm_header_type"]!="type_2")
					{
					?>
					<div class="menu-container first-menu clearfix">
						<?php
						//require_once('menu_1.php');
						?>
					</div>
					<?php
					}*/
					?>
					<div class="logo">
						<h1>
							<a href="/" title="">
								<img src="<?=SITE_TEMPLATE_PATH?>/images/logo.png" <?/*srcset="<?=SITE_TEMPLATE_PATH?>/images/logo_retina.png 2x"*/?> class="<?= (/*empty($_GET["page"]) || $_GET["page"]=="home" || $_GET["page"]=="about_2" || */$_GET["page"]=="service_calculator" || $_GET["page"]=="services_2" || $_GET["page"]=="cleaning_checklist" || $_GET["page"]=="contact_3" ? 'secondary-logo' : 'primary-logo'); ?>" alt="logo">
								<?/*<img src="<?=SITE_TEMPLATE_PATH?>/images/logo_transparent.png" srcset="<?=SITE_TEMPLATE_PATH?>/images/logo_transparent_retina.png 2x" class="<?php echo (empty($_GET["page"]) || $_GET["page"]=="home" || $_GET["page"]=="about_2" || $_GET["page"]=="service_calculator" || $_GET["page"]=="services_2" || $_GET["page"]=="cleaning_checklist" || $_GET["page"]=="contact_3" ? 'primary-logo' : 'secondary-logo'); ?>" alt="logo">*/?>
								<div>Муниципальное предприятие «ЖЭК-3»
									<span class="logo-text">Ханты-Мансийского района</span>
								</div>
							</a>
						</h1>
						<?php
						/*if(empty($_COOKIE["cm_header_type"]) || $_COOKIE["cm_header_type"]!="type_2")
						{
						?>
						<div class="logo-clone">
							<h1>
								<a href="?page=home" title="">
									<img src="<?=SITE_TEMPLATE_PATH?>/images/logo.png" class="<?= ($_GET["page"]=="service_calculator" || $_GET["page"]=="services_2" || $_GET["page"]=="cleaning_checklist" || $_GET["page"]=="contact_3" ? 'secondary-logo' : 'primary-logo'); ?>" alt="logo">
								
									<div>Муниципальное предприятие «ЖЭК-3»
									<span class="logo-text">Ханты-Мансийского района</span>
								</div>
								</a>
							</h1>
						</div>
						<?php
						}*/
						?>
					</div>
					<a href="#" class="mobile-menu-switch">
						<span class="line"></span>
						<span class="line"></span>
						<span class="line"></span>
						<span class="line"></span>
					</a>
					<div class="menu-container clearfix<?//php echo (empty($_COOKIE["cm_header_type"]) || $_COOKIE["cm_header_type"]!="type_2" ? ' second-menu' : ''); ?>">
						<?$APPLICATION->IncludeComponent(
							"bitrix:menu",
							"top",
							Array(
								"ROOT_MENU_TYPE" => "top",
								"MENU_CACHE_TYPE" => "N",
								"MENU_CACHE_TIME" => "36000",
								"MENU_CACHE_USE_GROUPS" => "N",
								"MENU_CACHE_GET_VARS" => array(),
								"MAX_LEVEL" => "2",
								"CHILD_MENU_TYPE" => "left",
								"USE_EXT" => "Y",
								"DELAY" => "N",
								"ALLOW_MULTI_SELECT" => "N",
								"CACHE_SELECTED_ITEMS" => "N",
								)
						);?>
						<?$APPLICATION->IncludeComponent(
							"bitrix:menu",
							"mobile",
							Array(
								"ROOT_MENU_TYPE" => "top",
								"MENU_CACHE_TYPE" => "N",
								"MENU_CACHE_TIME" => "36000",
								"MENU_CACHE_USE_GROUPS" => "N",
								"MENU_CACHE_GET_VARS" => array(),
								"MAX_LEVEL" => "2",
								"CHILD_MENU_TYPE" => "left",
								"USE_EXT" => "Y",
								"DELAY" => "N",
								"ALLOW_MULTI_SELECT" => "N",
								"CACHE_SELECTED_ITEMS" => "N",
								)
							);?>
						<?php
						/*if(empty($_COOKIE["cm_header_type"]) || $_COOKIE["cm_header_type"]!="type_2")
							require_once('menu_2.php');
						else
							require_once('menu.php');*/
						?>
					</div>
					<?/*<div class="header-icons-container hide-on-mobiles">
						<a href="#" class="template-cart"><span class="cart-items-number">2<span class="cart-items-number-arrow"></span></span></a>
						<a class="template-search" href="#" title="Search"></a>
						<form class="search">
							<input type="text" name="s" placeholder="Type and hit enter..." value="Type and hit enter..." class="search-input hint">
							<fieldset class="search-submit-container">
								<span class="template-search"></span>
								<input type="submit" class="search-submit" value="">
							</fieldset>
							<input type="hidden" name="page" value="search">
						</form>
					</div>*/?>
				</div>
			</div>