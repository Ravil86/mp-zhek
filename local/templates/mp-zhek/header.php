<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
$GLOBALS["IS_HOME"] = $APPLICATION->GetCurPage(true) === SITE_DIR . "index.php";
$GLOBALS["PAGE"] = $APPLICATION->GetCurDir(true) != SITE_DIR;

use Bitrix\Main\Page\Asset;

\Bitrix\Main\UI\Extension::load("ui.buttons");
\Bitrix\Main\UI\Extension::load("ui.icons.b24");

$rsSites = CSite::GetByID(SITE_ID);
$arSite = $rsSites->Fetch();
?>
<!DOCTYPE html>
<html>

<head>
    <title>
        <? $APPLICATION->ShowTitle() ?><?= ($GLOBALS["PAGE"] ? ' / ' : '') ?><?= $arSite['SITE_NAME'] ?>
    </title>
    <!-- <title><?= $arSite['SITE_NAME'] ?></title> -->
    <!--meta-->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.2" />
    <!-- <meta name="format-detection" content="telephone=no" /> -->
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <? $APPLICATION->ShowHead(); ?>
    <!--slider revolution-->
    <!--link rel="stylesheet" type="text/css" href="rs-plugin/css/settings.css"-->
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
    */ ?>
    <link rel="icon" type="image/png" sizes="32x32" href="<?= SITE_TEMPLATE_PATH ?>/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= SITE_TEMPLATE_PATH ?>/images/favicon/favicon-16x16.png">
    <link rel="apple-touch-icon" href="<?= SITE_TEMPLATE_PATH ?>/images/favicon/apple-touch-icon.png" />

    <!--link rel="shortcut icon" href="<? //=SITE_TEMPLATE_PATH
                                        ?>/images/favicon.ico"-->
    <?
    $arCss = array(
        //cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
        SITE_TEMPLATE_PATH . '/lib/bootstrap/css/bootstrap.min.css',
        SITE_TEMPLATE_PATH . '/css/reset.css',
        SITE_TEMPLATE_PATH . '/css/superfish.css',

        SITE_TEMPLATE_PATH . '/css/prettyPhoto.css',
        SITE_TEMPLATE_PATH . '/css/jquery.qtip.css',
        SITE_TEMPLATE_PATH . '/css/main.css',
        SITE_TEMPLATE_PATH . '/css/animations.css',
        SITE_TEMPLATE_PATH . '/css/revicons.css',

        SITE_TEMPLATE_PATH . '/lib/owl.carousel/css/owl.carousel.min.css',
        SITE_TEMPLATE_PATH . '/lib/owl.carousel/css/owl.theme.default.min.css',

        //fonts
        SITE_TEMPLATE_PATH . '/fonts/fonts.css',
        SITE_TEMPLATE_PATH . '/fonts/template/style.css',
        SITE_TEMPLATE_PATH . '/fonts/social/style.css',
        SITE_TEMPLATE_PATH . '/fonts/features/style.css',

        SITE_TEMPLATE_PATH . '/lib/fancybox/fancybox.css',

        //SITE_TEMPLATE_PATH . '/css/special.css',
        // SITE_TEMPLATE_PATH.'/css/special.min.css',

        '//cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css',

        // '//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/2.0.0-beta1/css/bootstrap-select.min.css',
        SITE_TEMPLATE_PATH . '/lib/bootstrap-select/css/bootstrap-select.min.css',

        SITE_TEMPLATE_PATH . '/css/cabinet.css',

        //select2
        // '//cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css',
        // '//cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css'
    );

    foreach ($arCss as $key => $css) {
        Asset::getInstance()->addCss($css);
    }

    $arJs = array(
        SITE_TEMPLATE_PATH . '/js/jquery-3.7.1.min.js',
        // SITE_TEMPLATE_PATH . '/js/jquery-1.12.4.min.js',

        SITE_TEMPLATE_PATH . '/js/jquery-migrate-3.5.2.min.js',
        // SITE_TEMPLATE_PATH . '/js/jquery-migrate-1.4.1.min.js',

        // //--slider revolution
        SITE_TEMPLATE_PATH . '/lib/jquery.themepunch.tools.min.js',    //rs-plugin
        SITE_TEMPLATE_PATH . '/lib/jquery.themepunch.revolution.min.js',    //rs-plugin
        // SITE_TEMPLATE_PATH . '/js/jquery.ba-bbq.min.js',
        SITE_TEMPLATE_PATH . '/js/jquery-ui-1.12.1.custom.min.js',
        SITE_TEMPLATE_PATH . '/js/jquery.ui.touch-punch.min.js',
        SITE_TEMPLATE_PATH . '/js/jquery.isotope.min.js',
        SITE_TEMPLATE_PATH . '/js/jquery.easing.1.3.min.js',

        SITE_TEMPLATE_PATH . '/js/jquery.carouFredSel-6.2.1-packed.js',        //Старый

        SITE_TEMPLATE_PATH . '/lib/owl.carousel/js/owl.carousel.min.js',

        SITE_TEMPLATE_PATH . '/js/jquery.touchSwipe.min.js',
        SITE_TEMPLATE_PATH . '/js/jquery.transit.min.js',
        SITE_TEMPLATE_PATH . '/js/jquery.timeago.js',
        SITE_TEMPLATE_PATH . '/js/jquery.hint.min.js',
        SITE_TEMPLATE_PATH . '/js/jquery.costCalculator.min.js',
        SITE_TEMPLATE_PATH . '/js/jquery.parallax.min.js',
        SITE_TEMPLATE_PATH . '/js/jquery.prettyPhoto.js',
        SITE_TEMPLATE_PATH . '/js/jquery.qtip.min.js',
        SITE_TEMPLATE_PATH . '/js/jquery.blockUI.min.js',
        SITE_TEMPLATE_PATH . '/js/main.js',

        SITE_TEMPLATE_PATH . '/lib/bootstrap/js/bootstrap.bundle.min.js',
        // SITE_TEMPLATE_PATH . '/lib/bootstrap/js/bootstrap.min.js',

        SITE_TEMPLATE_PATH . '/lib/fancybox/fancybox.umd.js',
        //SITE_TEMPLATE_PATH.'/js/masonry.pkgd.min.js',
        //SITE_TEMPLATE_PATH.'/js/imagesloaded.pkgd.min.js',

        SITE_TEMPLATE_PATH . '/lib/maskedinput/jquery.maskedinput.min.js',
        // SITE_TEMPLATE_PATH.'/lib/mask/jquery.mask.min.js',

        // SITE_TEMPLATE_PATH . '/js/special.js',
        // SITE_TEMPLATE_PATH . '/js/special.min.js',

        // '//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/2.0.0-beta1/js/bootstrap-select.min.js',
        // '//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/2.0.0-beta1/js/i18n/defaults-ru_RU.min.js'
        SITE_TEMPLATE_PATH . '/lib/bootstrap-select/js/bootstrap-select.min.js',
        SITE_TEMPLATE_PATH . '/lib/bootstrap-select/js/i18n/defaults-ru_RU.min.js'
    );

    foreach ($arJs as $key => $js) {
        Asset::getInstance()->addJs($js);
    }
    ?>
    <?/*
    <script src="https://lidrekon.ru/slep/js/jquery.js"></script>
	<script src="https://lidrekon.ru/slep/js/uhpv-full.min.js"></script>
    */ ?>
</head>
<div class="d-none d-md-block">
    <? $APPLICATION->ShowPanel(); ?>
</div>

<body>
    <main class="site-container<? //php echo ($_COOKIE['cm_layout']==" boxed" ? ' boxed' : '' );
                                ?>">
        <!--<div class="site-container boxed">-->
        <? // TopBar start
        ?>
        <div class="header-top-bar-container clearfix">
            <div class="header-top-bar container">


                <ul class="contact-details clearfix">
                    <li class="template-location">
                        <? $APPLICATION->IncludeFile(SITE_DIR . "includes/inc_adress.php", array(), array("MODE" => "html", "NAME" => '«Адрес»')); ?>
                    </li>
                    <li class="template-mobile">
                        <? $APPLICATION->IncludeFile(SITE_DIR . "includes/inc_phone_head.php", array(), array("MODE" => "html", "NAME" => '«Телефон»')); ?>
                    </li>
                    <li class="template-clock">
                        <? $APPLICATION->IncludeFile(SITE_DIR . "includes/inc_work_time.php", array(), array("MODE" => "html", "NAME" => '«Рабочее время»')); ?>
                    </li>
                </ul>

                <ul class="contact-details float-end d-none d-lg-block">
                    <?/*
                    <li id="specialButton" class="lh-1"><i class="template-display me-2 fs-6"></i><button class="btn p-0" title="Версия для слабовидщих">Версия для слабовидщих</button></li>*/ ?>
                    <!-- <li class="d-none d-xl-block"><a href="https://old.mp-zhek-3.ru" target="_blank">Прежняя версия сайта</a></li> -->
                    <li id="lkButton" class="lh-1"><i class="features-person me-2 fs-6"></i><a href="/cabinet/">Личный кабинет</a></li>
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
            <!-- <img id="specialButton" style="cursor:pointer;" src="https://lidrekon.ru/images/special.png" alt="ВЕРСИЯ ДЛЯ СЛАБОВИДЯЩИХ" title="ВЕРСИЯ ДЛЯ СЛАБОВИДЯЩИХ" /> -->
        </div>
        <?php
        // TopBar end
        ?>

        <header>
            <div class="header-container<?= !defined('LK') ? ' sticky' : '' ?> navbar">
                <div class="header container clearfix">
                    <div class="logo d-flex justify-content-around justify-content-lg-start col col-md-12 col-lg pe-3">
                        <h1>
                            <a href="/" title="">
                                <img src="<?= SITE_TEMPLATE_PATH ?>/images/logo.svg" <?/*srcset="<?=SITE_TEMPLATE_PATH?>/images/logo_retina.png 2x" */ ?>
                                    class="<?= (/*empty($_GET["page"]) || $_GET["page"]=="home" || $_GET["page"]=="about_2" || */$_GET["page"] == "service_calculator" || $_GET["page"] == "services_2" || $_GET["page"] == "cleaning_checklist" || $_GET["page"] == "contact_3" ? 'secondary-logo' : 'primary-logo'); ?>"
                                    alt="logo">
                                <?/*<img src="<?=SITE_TEMPLATE_PATH?>/images/logo_transparent.png"
                                srcset="<?=SITE_TEMPLATE_PATH?>/images/logo_transparent_retina.png 2x"
                                class="<?php echo (empty($_GET["page"]) || $_GET["page"]=="home" || $_GET["page"]=="about_2" || $_GET["page"]=="service_calculator" || $_GET["page"]=="services_2" || $_GET["page"]=="cleaning_checklist" || $_GET["page"]=="contact_3" ? 'primary-logo' : 'secondary-logo'); ?>"
                                alt="logo">*/ ?>
                                <div>Муниципальное предприятие «ЖЭК-3»
                                    <span class="logo-text d-none d-xl-block">Ханты-Мансийского района</span>
                                </div>
                            </a>
                        </h1>
                    </div>
                    <!--logo-->
                    <?/*
					<a href="#" class="mobile-menu-switch align-items-center">
						<div>
							<!--<span class="line"></span>
							<span class="line"></span>
							<span class="line"></span> -->
							<!-- <span class="line"></span> -->
						</div>
					</a>
					*/ ?>
                    <button class="navbar-toggler border-0 mobile-menu-switch" type="button">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="menu-container d-flex justify-content-center justify-content-lg-end col col-md-12 col-lg-auto clearfix">
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:menu",
                            "top",
                            array(
                                "ROOT_MENU_TYPE" => "top",
                                "MENU_CACHE_TYPE" => "N",
                                "MENU_CACHE_TIME" => "36000",
                                "MENU_CACHE_USE_GROUPS" => "N",
                                "MENU_CACHE_GET_VARS" => array(),
                                "MAX_LEVEL" => "2",
                                "CHILD_MENU_TYPE" => "left",
                                "USE_EXT" => "Y",
                                "DELAY" => "N",
                                "ALLOW_MULTI_SELECT" => "Y",
                                "CACHE_SELECTED_ITEMS" => "N",
                            )
                        ); ?>
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:menu",
                            "mobile",
                            array(
                                "ROOT_MENU_TYPE" => "top",
                                "MENU_CACHE_TYPE" => "N",
                                "MENU_CACHE_TIME" => "36000",
                                "MENU_CACHE_USE_GROUPS" => "N",
                                "MENU_CACHE_GET_VARS" => array(),
                                "MAX_LEVEL" => "2",
                                "CHILD_MENU_TYPE" => "left",
                                "USE_EXT" => "Y",
                                "DELAY" => "N",
                                "ALLOW_MULTI_SELECT" => "Y",
                                "CACHE_SELECTED_ITEMS" => "N",
                            )
                        ); ?>
                    </div>
                    <!--menu-container-->
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
					</div>*/ ?>
                </div>
                <!--.header.container-->
            </div>
            <!--.header-container-->
        </header>


        <? if ($GLOBALS["PAGE"]): ?>
            <div class="container-fluid! bg-gray full-width! page-header vertical-align-table!">
                <div class="container">
                    <div class="row">
                        <div class="page-header-left col-12 col-md-6">
                            <h1>
                                <? $APPLICATION->ShowTitle() ?>
                            </h1>
                        </div>
                        <div class="page-header-right col-12 col-md-6">
                            <div class="bread-crumb-container">
                                <? $APPLICATION->IncludeComponent(
                                    "bitrix:breadcrumb",
                                    "",
                                    array(),
                                    false
                                ); ?>
                            </div>


                            <?/*?>
                        <div class="bread-crumb-container">
                            <ul class="bread-crumb">
                                <li>
                                    <a title="Home" href="?page=home">
                                        Home
                                    </a>
                                </li>
                                <li class="separator">
                                    /
                                </li>
                                <li>
                                    About Us
                                </li>
                            </ul>
                        </div>
                        <?*/ ?>
                        </div>
                    </div>
                </div>
            </div>
            <? if (!defined('NOT_CONTAINER')): ?>
                <div class="container<?= defined('WIDE') ? ' container-xxl' : '' ?> page-content page-margin-top-section padding-bottom-100">
                    <? if (!defined('NOT_MENU') && !defined('ERROR_404')): ?>
                        <div class="row gx-3">
                            <div class="col-12 col-md-3<?= defined('LK') ? ' col-xxl-2 col-xxl-auto!' : '' ?> mb-4">
                                <? if (defined('LK')): ?>

                                    <? if ($USER->IsAuthorized()): ?>
                                        <div class="px-2 py-3 d-flex align-items-center">
                                            <div class="ui-icon ui-icon-sm ui-icon-common-user me-2"><i></i></div>
                                            <div class="div">
                                                <div><?= $USER->GetLogin() ?></div>
                                                <div><?= $USER->GetFullName() ?></div>
                                            </div>
                                        </div>
                                    <? endif; ?>
                                    <? $APPLICATION->IncludeComponent(
                                        "bitrix:menu",
                                        "ui-button",
                                        array(
                                            "ALLOW_MULTI_SELECT" => "N",
                                            "CHILD_MENU_TYPE" => "left",
                                            "COMPOSITE_FRAME_MODE" => "A",
                                            "COMPOSITE_FRAME_TYPE" => "AUTO",
                                            "DELAY" => "N",
                                            "MAX_LEVEL" => "1",
                                            "MENU_CACHE_GET_VARS" => array(),
                                            "MENU_CACHE_TIME" => "36000",
                                            "MENU_CACHE_TYPE" => "N",
                                            "MENU_CACHE_USE_GROUPS" => "N",
                                            "ROOT_MENU_TYPE" => "left",
                                            "USE_EXT" => "N",
                                            "COMPONENT_TEMPLATE" => "left"
                                        ),
                                        false
                                    ); ?>
                                    <? if ($USER->IsAuthorized()): ?>
                                        <a class="d-flex my-1 ui-btn ui-btn-light-border" href="?logout=yes&<?= bitrix_sessid_get() ?>">Выход</a>
                                    <? endif; ?>

                                <? else: ?>
                                    <? $APPLICATION->IncludeComponent(
                                        "bitrix:menu",
                                        "left",
                                        array(
                                            "ALLOW_MULTI_SELECT" => "N",
                                            "CHILD_MENU_TYPE" => "left",
                                            "COMPOSITE_FRAME_MODE" => "A",
                                            "COMPOSITE_FRAME_TYPE" => "AUTO",
                                            "DELAY" => "N",
                                            "MAX_LEVEL" => "1",
                                            "MENU_CACHE_GET_VARS" => array(),
                                            "MENU_CACHE_TIME" => "36000",
                                            "MENU_CACHE_TYPE" => "N",
                                            "MENU_CACHE_USE_GROUPS" => "N",
                                            "ROOT_MENU_TYPE" => "left",
                                            "USE_EXT" => "N",
                                            "COMPONENT_TEMPLATE" => "left"
                                        ),
                                        false
                                    ); ?>
                                <? endif; ?>
                            </div>
                            <div class="col-12 col-md-9 <?= defined('LK') ? ' col-xxl' : '' ?>">
                            <? endif ?>
                        <? endif ?>
                    <? endif ?>