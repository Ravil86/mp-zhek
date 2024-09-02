<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if($GLOBALS["PAGE"]):?>
<?if(!defined('NOT_MENU')):?>
</div>
</div>
<?endif?>
</div>
<?endif?>
<div class="container-fluid row! bg-dark footer-row full-width! padding-top-30">
    <div class="container">

        <div class="row padding-bottom-25">
            <div class="col-12 col-md-3 column">
                <ul class="contact-details-list">
                    <li class="features-phone">
                        <label>Телефон приемной</label>
                        <?
				$APPLICATION->IncludeFile(SITE_DIR."includes/inc_phone_1.php", Array(), Array(
					"MODE"      => "html",                                           // будет редактировать в веб-редакторе
					"NAME"      => '«Телефон приемной»',      // текст всплывающей подсказки на иконке
				));
				?>
                    </li>
                </ul>
            </div>
            <div class="col-12 col-md-5 column">
                <ul class="contact-details-list">
                    <li class="features-map">
                        <label>628011, Тюменская область, ХМАО-Югра</label>
                        <div class="p">
                            <?$APPLICATION->IncludeFile(SITE_DIR."includes/inc_adress.php", Array(), Array("MODE" => "html","NAME" => '«Адрес»'));?>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-12 col-md-3 column">
                <ul class="contact-details-list">
                    <li class="features-phone">
                        <!-- <li class="features-wallet"> -->
                        <label>Телефон диспетчера</label>
                        <?$APPLICATION->IncludeFile(SITE_DIR."includes/inc_phone_2.php", Array(), Array(
								"MODE"      => "html",                                           // будет редактировать в веб-редакторе
								"NAME"      => '«Телефон приемной»',      // текст всплывающей подсказки на иконке
							));
							?>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>
<div class="row! container-fluid bg-dark-gray footer-row full-width! padding-top-61! padding-bottom-25">
    <!-- <div class="row row-4-4">
					<div class="column column-1-4">
						<h6>ABOUT US</h6>
						<p class="margin-top-23">Founded in 1995 Cleanmate quickly built a reputation as one of the leading providers of residential and commercial cleaning solutions.</p>
						<p>Our focus is to listen to our clients, understand their needs and provide the exceptional level of cleaning service.</p>
						<div class="margin-top-37 padding-bottom-16">
							<a class="more gray" href="?page=about" title="Learn more">Learn more</a>
						</div>
					</div>
					<div class="column column-1-4">
						<h6>OUR SERVICES</h6>
						<ul class="list margin-top-31">
							<li class="template-arrow-horizontal-2"><a href="?page=service_commercial_cleaning" title="Commercial Cleaning">Commercial Cleaning</a></li>
							<li class="template-arrow-horizontal-2"><a href="?page=service_house_cleaning" title="House Cleaning">House Cleaning</a></li>
							<li class="template-arrow-horizontal-2"><a href="?page=service_move_in_out" title="Move In Out Service">Move In Out Service</a></li>
							<li class="template-arrow-horizontal-2"><a href="?page=service_post_renovation" title="Post Renovation">Post Renovation</a></li>
							<li class="template-arrow-horizontal-2"><a href="?page=service_window_cleaning" title="Window Cleaning">Window Cleaning</a></li>
							<li class="template-arrow-horizontal-2"><a href="?page=service_green_spaces_maintenance" title="Green Spaces Maintenance">Green Spaces Maintenance</a></li>
							<li class="template-arrow-horizontal-2">Novum Elementum</li>
							<li class="template-arrow-horizontal-2">Sicilium Polon</li>
						</ul>
					</div>
					<div class="column column-1-4">
						<h6>LATEST POSTS</h6>
						<ul class="latest-post margin-top-42">
							<li>
								<a href="?page=post" title="Best pro tips for home cleaning">Best pro tips for home cleaning</a>
								<abbr title="August 25, 2017">August 25, 2017</abbr>
							</li>
							<li>
								<a href="?page=post" title="Best pro tips for home cleaning">Best pro tips for home cleaning</a>
								<abbr title="August 24, 2017">August 24, 2017</abbr>
							</li>
							<li>
								<a href="?page=post" title="Best pro tips for home cleaning">Best pro tips for home cleaning</a>
								<abbr title="August 23, 2017">August 23, 2017</abbr>
							</li>
						</ul>
					</div>
					<div class="column column-1-4">
						<h6>CONTACT INFO</h6>
						<ul class="contact-data margin-top-20">
							<li class="template-location"><div class="value">745 Adelaide St., Ottawa, Ontario</div></li>
							<li class="template-mobile"><div class="value"><a href="tel:2507257052">250 725 7052</a></div></li>
							<li class="template-email"><div class="value"><a href="mailto:contact@cleanmate.com">contact@cleanmate.com</a></div></li>
							<li class="template-clock"><div class="value">Mon-Fri: 08.00 am - 05.00 pm</div></li>
							<li class="template-clock"><div class="value">Saturday, Sunday: closed</div></li>
						</ul>
					</div>
				</div> -->
    <div class="row! page-padding-top!">
        <!-- <ul class="social-icons align-center">
						<li>
							<a target="_blank" href="https://twitter.com/QuanticaLabs" class="social-twitter" title="twitter"></a>
						</li>
						<li>
							<a href="https://pinterest.com/quanticalabs/" class="social-pinterest" title="pinterest"></a>
						</li>
						<li>
							<a target="_blank" href="https://facebook.com/QuanticaLabs" class="social-facebook" title="facebook"></a>
						</li>
					</ul> -->
    </div>
    <div class="row! align-center pt-3 padding-top-30!">
        <span class="copyright">© Все права защищены. 2024
            <?//<a href="https://themeforest.net/item/cleanmate-cleaning-company-maid-gardening-template/20493947?ref=QuanticaLabs" title="Cleanmate Template" target="_blank">Cleanmate Template</a> by <a href="http://quanticalabs.com" title="QuanticaLabs" target="_blank">QuanticaLabs</a>?>
        </span>
    </div>
</div>
</main>
<a href="#top" class="scroll-top animated-element template-arrow-vertical-3" title="Scroll to top"></a>
<div class="background-overlay"></div>
<?/*php if($_GET["page"]=="contact" || $_GET["page"]=="contact_2" || $_GET["page"]=="contact_3"):?>
<script type="text/javascript" src="//maps.google.com/maps/api/js?key=YOUR_API_KEY"></script>
<?php endif; */?>
<?php
		require($_SERVER['DOCUMENT_ROOT'].'/includes/modal_feedback.php');

		//require_once("style_selector/style_selector.php");
		?>
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function(m, e, t, r, i, k, a) {
    m[i] = m[i] || function() {
        (m[i].a = m[i].a || []).push(arguments)
    };
    m[i].l = 1 * new Date();
    for (var j = 0; j < document.scripts.length; j++) {
        if (document.scripts[j].src === r) {
            return;
        }
    }
    k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k,
        a)
})(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
ym(97609520, "init", {
    clickmap: true,
    trackLinks: true,
    accurateTrackBounce: true,
    webvisor: true
});
</script> <noscript>
    <div><img src="https://mc.yandex.ru/watch/97609520" style="position:absolute; left:-9999px;" alt="" /></div>
</noscript> <!-- /Yandex.Metrika counter -->
<script>
Fancybox.bind("[data-fancybox]", {
    // Your custom options
});
</script>
</body>

</html>