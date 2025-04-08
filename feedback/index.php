<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Обращения граждан");
?><p style="color: #333333; text-align: center;">
 <span style="font-weight: 700;">Уважаемые граждане!</span>
</p>
<p align="justify" style="color: #333333;">
	 Информируем Вас о том, что с 30 марта 2025 года в соответствии с Федеральным законом от 2 мая 2006 года № 59-ФЗ «О порядке рассмотрения обращений граждан Российской Федерации» вводится обязательная процедура идентификации и (или) аутентификации при направлении Вами обращений с использованием информационной системы государственного органа или органа местного самоуправления либо официального сайта государственного органа или органа местного самоуправления в информационно-телекоммуникационной сети «Интернет».
</p>
<br><br>
<script src='https://pos.gosuslugi.ru/bin/script.min.js'></script> 
<style>
#js-show-iframe-wrapper{position:relative;display:flex;align-items:center;justify-content:center;width:100%;min-width:293px;max-width:100%;background:linear-gradient(138.4deg,#38bafe 26.49%,#2d73bc 79.45%);color:#fff;cursor:pointer}#js-show-iframe-wrapper .pos-banner-fluid *{box-sizing:border-box}#js-show-iframe-wrapper .pos-banner-fluid .pos-banner-btn_2{display:block;width:240px;min-height:56px;font-size:18px;line-height:24px;cursor:pointer;background:#0d4cd3;color:#fff;border:none;border-radius:8px;outline:0}#js-show-iframe-wrapper .pos-banner-fluid .pos-banner-btn_2:hover{background:#1d5deb}#js-show-iframe-wrapper .pos-banner-fluid .pos-banner-btn_2:focus{background:#2a63ad}#js-show-iframe-wrapper .pos-banner-fluid .pos-banner-btn_2:active{background:#2a63ad}@-webkit-keyframes fadeInFromNone{0%{display:none;opacity:0}1%{display:block;opacity:0}100%{display:block;opacity:1}}@keyframes fadeInFromNone{0%{display:none;opacity:0}1%{display:block;opacity:0}100%{display:block;opacity:1}}@font-face{font-family:LatoWebLight;src:url(https://pos.gosuslugi.ru/bin/fonts/Lato/fonts/Lato-Light.woff2) format("woff2"),url(https://pos.gosuslugi.ru/bin/fonts/Lato/fonts/Lato-Light.woff) format("woff"),url(https://pos.gosuslugi.ru/bin/fonts/Lato/fonts/Lato-Light.ttf) format("truetype");font-style:normal;font-weight:400}@font-face{font-family:LatoWeb;src:url(https://pos.gosuslugi.ru/bin/fonts/Lato/fonts/Lato-Regular.woff2) format("woff2"),url(https://pos.gosuslugi.ru/bin/fonts/Lato/fonts/Lato-Regular.woff) format("woff"),url(https://pos.gosuslugi.ru/bin/fonts/Lato/fonts/Lato-Regular.ttf) format("truetype");font-style:normal;font-weight:400}@font-face{font-family:LatoWebBold;src:url(https://pos.gosuslugi.ru/bin/fonts/Lato/fonts/Lato-Bold.woff2) format("woff2"),url(https://pos.gosuslugi.ru/bin/fonts/Lato/fonts/Lato-Bold.woff) format("woff"),url(https://pos.gosuslugi.ru/bin/fonts/Lato/fonts/Lato-Bold.ttf) format("truetype");font-style:normal;font-weight:400}@font-face{font-family:RobotoWebLight;src:url(https://pos.gosuslugi.ru/bin/fonts/Roboto/Roboto-Light.woff2) format("woff2"),url(https://pos.gosuslugi.ru/bin/fonts/Roboto/Roboto-Light.woff) format("woff"),url(https://pos.gosuslugi.ru/bin/fonts/Roboto/Roboto-Light.ttf) format("truetype");font-style:normal;font-weight:400}@font-face{font-family:RobotoWebRegular;src:url(https://pos.gosuslugi.ru/bin/fonts/Roboto/Roboto-Regular.woff2) format("woff2"),url(https://pos.gosuslugi.ru/bin/fonts/Roboto/Roboto-Regular.woff) format("woff"),url(https://pos.gosuslugi.ru/bin/fonts/Roboto/Roboto-Regular.ttf) format("truetype");font-style:normal;font-weight:400}@font-face{font-family:RobotoWebBold;src:url(https://pos.gosuslugi.ru/bin/fonts/Roboto/Roboto-Bold.woff2) format("woff2"),url(https://pos.gosuslugi.ru/bin/fonts/Roboto/Roboto-Bold.woff) format("woff"),url(https://pos.gosuslugi.ru/bin/fonts/Roboto/Roboto-Bold.ttf) format("truetype");font-style:normal;font-weight:400}@font-face{font-family:ScadaWebRegular;src:url(https://pos.gosuslugi.ru/bin/fonts/Scada/Scada-Regular.woff2) format("woff2"),url(https://pos.gosuslugi.ru/bin/fonts/Scada/Scada-Regular.woff) format("woff"),url(https://pos.gosuslugi.ru/bin/fonts/Scada/Scada-Regular.ttf) format("truetype");font-style:normal;font-weight:400}@font-face{font-family:ScadaWebBold;src:url(https://pos.gosuslugi.ru/bin/fonts/Scada/Scada-Bold.woff2) format("woff2"),url(https://pos.gosuslugi.ru/bin/fonts/Scada/Scada-Bold.woff) format("woff"),url(https://pos.gosuslugi.ru/bin/fonts/Scada/Scada-Bold.ttf) format("truetype");font-style:normal;font-weight:400}@font-face{font-family:Geometria;src:url(https://pos.gosuslugi.ru/bin/fonts/Geometria/Geometria.eot);src:url(https://pos.gosuslugi.ru/bin/fonts/Geometria/Geometria.eot?#iefix) format("embedded-opentype"),url(https://pos.gosuslugi.ru/bin/fonts/Geometria/Geometria.woff) format("woff"),url(https://pos.gosuslugi.ru/bin/fonts/Geometria/Geometria.ttf) format("truetype");font-weight:400;font-style:normal}@font-face{font-family:Geometria-ExtraBold;src:url(https://pos.gosuslugi.ru/bin/fonts/Geometria/Geometria-ExtraBold.eot);src:url(https://pos.gosuslugi.ru/bin/fonts/Geometria/Geometria-ExtraBold.eot?#iefix) format("embedded-opentype"),url(https://pos.gosuslugi.ru/bin/fonts/Geometria/Geometria-ExtraBold.woff) format("woff"),url(https://pos.gosuslugi.ru/bin/fonts/Geometria/Geometria-ExtraBold.ttf) format("truetype");font-weight:900;font-style:normal}
</style>

<style>
#js-show-iframe-wrapper{background:0 0}#js-show-iframe-wrapper .pos-banner-fluid .pos-banner-btn_2{width:100%;font-size:16px;min-height:52px}#js-show-iframe-wrapper .bf-160{position:relative;width:230px;height:90px;box-sizing:border-box;background:#fff;border:1px solid #0d4cd3;border-radius:4px}#js-show-iframe-wrapper .bf-160__content{width:206px;height:75px;margin-top:12px;margin-left:12px;display:flex;flex-direction:column;justify-content:space-between}#js-show-iframe-wrapper .bf-160__logo-wrap{display:inline-flex;align-items:center;width:100%}#js-show-iframe-wrapper .bf-160__logo{width:61.01px;height:16.28px}#js-show-iframe-wrapper .bf-160__logo2{width:41px;height:40px}#js-show-iframe-wrapper .bf-160__text{color:#0b40b3;box-sizing:border-box;height:40px;align-content:start;font-family:LatoWeb,sans-serif;font-weight:700;font-size:13.08px;line-height:16.6px;letter-spacing:0;vertical-align:bottom}#js-show-iframe-wrapper .bf-160__description{display:flex;justify-content:space-around}
</style>

<div id='js-show-iframe-wrapper'>
    <div class='pos-banner-fluid bf-160'>
        <div class='bf-160__content'>
            <div class='bf-160__logo-wrap'>
                <img
                        class='bf-160__logo'
                        src='https://pos.gosuslugi.ru/bin/banner-fluid/gosuslugi-logo-with-slogan-blue.svg'
                        alt='Госуслуги'
                />
            </div>

            <div class='bf-160__description'>
                <div class='bf-160__text'>
                    Направить обращение через Госуслуги
                </div>
                <div>
                    <img
                            class='bf-160__logo2'
                            src='https://pos.gosuslugi.ru/bin/icons/finger-up-logo.svg'
                            alt='Госуслуги'
                    />
                </div>
            </div>
        </div>
    </div>
</div>
 <script>Widget("https://pos.gosuslugi.ru/form", 809)</script><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>