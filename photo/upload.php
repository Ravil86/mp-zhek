<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Загрузить");
?>Text here....<?$APPLICATION->IncludeComponent(
	"zhek:photogallery.upload",
	"",
	Array(
		"ALBUM_PHOTO_THUMBS_WIDTH" => "120",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"IBLOCK_ID" => "16",
		"IBLOCK_TYPE" => "info",
		"INDEX_URL" => "index.php",
		"JPEG_QUALITY" => "100",
		"JPEG_QUALITY1" => "100",
		"MODERATION" => "N",
		"ORIGINAL_SIZE" => "1280",
		"PATH_TO_FONT" => "default.ttf",
		"SECTION_ID" => $_REQUEST["SECTION_ID"],
		"SECTION_URL" => "section.php?SECTION_ID=#SECTION_ID#",
		"SET_TITLE" => "Y",
		"THUMBNAIL_SIZE" => "90",
		"UPLOAD_MAX_FILE_SIZE" => "7",
		"USE_WATERMARK" => "N",
		"WATERMARK_MIN_PICTURE_SIZE" => "800",
		"WATERMARK_RULES" => "USER",
		"WATERMARK_TYPE" => "BOTH"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>