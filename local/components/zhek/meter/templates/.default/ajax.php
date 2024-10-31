<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); ?>

<?
//var_dump($_POST,1);

if($_POST['ID'] && $_POST['USER'] && $_POST['STATUS'])
	{
		if($_POST['STATUS']==1)
			$COMMENT = 'Документ принят';
		else
			$COMMENT = $_POST['COMENTS'];

		
		//$res = CProfileDocs::setStatusDocs((int)$_POST['ID'], (int)$_POST['USER'], (int)$_POST['STATUS'], $_POST['COMENTS']);
		$res = CProfileDocs::setStatusDocs((int)$_POST['ID'], (int)$_POST['USER'], (int)$_POST['STATUS'], $COMMENT);

		//var_dump($res);
		//CIBlockElement::SetPropertyValues($_POST['ID'], $_POST['idIblock'], $_POST['COMENTS'], 'COMENTS');
	}
?>