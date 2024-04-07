<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

function dump($var, $type = 0)
{
	global $USER;
	if ($USER->IsAdmin())
	{
		if ($type == 0)
		{
			echo "<pre>";
			print_r ($var);
			echo "</pre>";
		}
		else //if ($type == 1)
		{
			echo "<pre>";
			var_dump ($var);
			echo "</pre>";
		}
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