<?

// конфиг JS и CSS модуля
$arJsConfig = array(
	'bendersay_exportimport' => array(
		'js' => '/bitrix/js/bendersay.exportimport/script.js',
		'css' => '/bitrix/panel/bendersay.exportimport/main_style.css',
		'rel' => array('jquery2'),
	)
);
// Регистрация JS и CSS модуля
foreach ($arJsConfig as $ext => $arExt) {
	\CJSCore::RegisterExt($ext, $arExt);
}
// подключение JS и CSS модуля
CUtil::InitJSCore(array('bendersay_exportimport'));
