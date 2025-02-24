<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

include_once 'hl.wrapper.php';

include_once 'lk.class.php';

// require 'vendor/autoload.php';

function dump($var, $type = 0)
{
	global $USER;
	if ($USER->IsAdmin()) {
		if ($type == 0) {
			echo "<pre>";
			print_r($var);
			echo "</pre>";
		} else //if ($type == 1)
		{
			echo "<pre>";
			var_dump($var);
			echo "</pre>";
		}
	}
}

function gg($var)
{
	echo "<pre>";
	print_r($var);
	echo "</pre>";
}

function getFileArray($fileId, $onlyPath = false)
{

	if (is_string($fileId) && $onlyPath) {
		$filePath = $filename = $fileId;
	} else {
		if ($fileId && !is_array($fileId)) {
			$arFile = CFile::GetFileArray($fileId);

			$filePath = $arFile['SRC'];
			$filename = $arFile['FILE_NAME'];
			$original = $arFile["ORIGINAL_NAME"];
			$filesize = $arFile["FILE_SIZE"];
			$fileDescription = $arFile["DESCRIPTION"];
		} else {
			$filePath = $fileId['SRC'];
			$filename = $fileId['FILE_NAME'];
			$original = $fileId["ORIGINAL_NAME"];
			$filesize = $fileId["FILE_SIZE"];
			$fileDescription = $fileId["DESCRIPTION"];
		}
	}
	// dump($original);
	$res = file_exists($_SERVER["DOCUMENT_ROOT"] . $filePath);

	if (!$res)
		return ['ERROR' => 'Файл не найден'];

	$chars = ['+', '#']; // символы для удаления
	preg_match('/[а-яё]/iu', trim($original), $russimvol);
	if ($russimvol[0]) {
		$origName = substr($original, 0, strrpos($original, '.'));
		preg_match('/(.*)\.[A-z]{3,4}/i', trim($origName), $matches);
		$origName = $matches[0] ? $matches[1] : $origName;
	}
	$fileFormatName = $fileDescription ? str_replace($chars, ' ', $fileDescription) : ($origName ? $origName : '');

	$fileFormat = substr($filename, strrpos($filename, '.') + 1);

	$fileSize = CFile::FormatSize($filesize, 0);
	$formatArr = [
		'pdf' => 'pdf',
		'doc' => 'word',
		'docx' => 'word',
		'rtf' => 'word',
		'xls' => 'excel',
		'xlsx' => 'excel',
		'xlsb' => 'excel',
		'rar' => 'zipper',
		'zip' => 'zipper',
	];

	$uiIcon = [
		'pdf' => 'pdf',
		'doc' => 'doc',
		'docx' => 'doc',
		'rtf' => 'txt',
		'xls' => 'xls',
		'xlsx' => 'xls',
		'xlsb' => 'xls',
		'rar' => 'rar',
		'zip' => 'zip',
		'jpg' => 'img',
		'png' => 'img',
		'jpen' => 'img',
		'ppt' => 'ppt',

	];

	return [
		'SRC' => $filePath,
		'FILE_NAME'	=> $fileFormatName,
		'ORIGINAL_NAME'	=> $origName,
		'SIZE'	=> $fileSize,
		'FORMAT' => $fileFormat,
		'CLASS' => $formatArr[$fileFormat],
		'UI_ICON' => $uiIcon[$fileFormat],
	];
}

// Запрет на удаление элементов инфоблока SEO-тексты
AddEventHandler("iblock", "OnBeforeIBlockElementDelete", "OnBeforeIBlockElementDeleteHandler");
function OnBeforeIBlockElementDeleteHandler($ID)
{
	// Получаем данные об удаляемом элементе
	$rsElement = CIBlockElement::GetByID($ID);
	$arElement = $rsElement->Fetch();
	if ($arElement["IBLOCK_ID"] == 2 || $arElement["IBLOCK_ID"] == 4 || $arElement["IBLOCK_ID"] == 8) {
		global $APPLICATION;
		$APPLICATION->ThrowException("Вы не можете удалить этот элемент инфоблока");
		return false;
	}
}

/*AddEventHandler("main", "OnEndBufferContent", "deleteKernelCss");
function deleteKernelCss(&$content) {
	    global $USER, $APPLICATION;
	    if(strpos($APPLICATION->GetCurDir(), "/bitrix/")!==false) return;
	    if($APPLICATION->GetProperty("save_kernel") == "Y") return;
	    $arPatternsToRemove = Array(
	        '/<link.+?href=".+?bitrix\/css\/main\/bootstrap.css[^"]+"[^>]+>/',
	        '/<link.+?href=".+?bitrix\/css\/main\/bootstrap.min.css[^"]+"[^>]+>/',
	    );
	    $content = preg_replace($arPatternsToRemove, "", $content);
	    $content = preg_replace("/\n{2,}/", "\n\n", $content);
	}*/