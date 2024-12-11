<?

use Bitrix\Main;
use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Config\Option;

Loader::includeModule("iblock");

class LKClass
{

    protected static $_HL_Company = "Company"; // HL организации
    protected static $_HL_Objects = "Objects"; // HL Объекты

    protected static $_HL_Counters = "Counters"; // HL ПРиборы учёта
    protected static $_HL_Service = "Service"; // HL типы услуг

    protected static $_HL_Meter = "Meter"; // HL типы услуг

    public static function meters($objectID, $last = false)
    {
        $classHL = \HLWrap::init(self::$_HL_Meter);

        // $filter = [];
        $filter = ['UF_OBJECT' => $objectID];
        if ($last)
            $filter[">=" . "UF_DATE"] = new DateTime(date('01.m.Y') . " 00:00:00");
        else
            $filter['<=' . 'UF_DATE'] = new DateTime(date('01.m.Y') . " 00:00:00");

        // dump($filter);

        $rsHLoad = $classHL::getList([
            'select' => ['*'],
            'filter' => $filter,
            'order' => ['UF_DATE' => 'DESC']
        ]);

        $result = [];
        while ($value = $rsHLoad->fetch()) {
            $meter = [
                'ID' => $value['ID'],
                'DATE' => $value['UF_DATE'],
                'METER' => $value['UF_METER'],
                'COUNTER' => $value['UF_COUNTER'],
                'OBJECT' => $value['UF_OBJECT'],
                // 'SERVICE' => $value['UF_SERVICE'],
            ];
            $result[$value['ID']] = $meter;
        }
        return $result;
    }

    public static function saveMeter($objectID, $counter, $meter)
    {

        $classHL = \HLWrap::init(self::$_HL_Meter);

        $value = [
            'UF_METER' => $meter,
            'UF_DATE' => date('d.m.Y H:i:s'),
            'UF_OBJECT' => $objectID,
            'UF_COUNTER' => $counter,
        ];

        $filter = [
            'UF_OBJECT' => $objectID,
            ">=" . "UF_DATE"  => new DateTime(date('01.m.Y') . " 00:00:00"),
            'UF_COUNTER' => $counter,
        ];
        // dump($filter);

        $rsHLoad = $classHL::getList([
            'select' => ['*'],
            'filter' => $filter,
            'order' => ['UF_DATE' => 'DESC']
        ]);
        if ($arMeter = $rsHLoad->Fetch()) {
            $classHL::update($arMeter["ID"], $value);
        } else {
            $classHL::add($value);
        }
    }

    public static function myCompany()
    {
        $result = null;
        $curentUser = self::curentUserFields();


        if ($curentUser['UF_COMPANY'])
            $result = self::getCompany($curentUser['UF_COMPANY']);


        // print_r($result);
        return $result;
        // $userId = Bitrix\Main\Engine\CurrentUser::get()->getId();
    }

    public static function getCompany($orgID = [])
    {

        // $curentUser = self::curentUserFields();

        $classCompany = \HLWrap::init(self::$_HL_Company);


        if ($orgID)
            $filter = ['ID' => $orgID];
        else
            $filter = [];

        // dump($filter);

        $rsCompany = $classCompany::getList(
            array(
                'filter' => $filter,
                'select' => array('*'),
            )
        );

        while ($company = $rsCompany->Fetch()) {
            $arFields = [
                'ID' => $company['ID'],
                'NAME' => $company['UF_NAME'],
                'ADRES' => $company['UF_ADDRESS'],
                'INN' => $company['UF_INN']
            ];
            if ($orgID)
                $result = $arFields;
            else
                $result[$company['ID']] = $arFields;
        }

        return $result;
    }

    public static function getObjects($orgID = null)
    {

        // $curentUser = self::curentUserFields();

        $classHL = \HLWrap::init(self::$_HL_Objects);

        $filter = [];
        if ($orgID)
            $filter = ['UF_ORG' => $orgID];

        $rsHLoad = $classHL::getList([
            'select' => ['*'],
            'filter' => $filter,
            'order' => []
        ]);

        $result = [];
        while ($object = $rsHLoad->fetch()) {
            $value = [
                'ID' => $object['ID'],
                'NAME' => $object['UF_NAME'],
                'ADDRESS' => $object['UF_ADRES'],
                'DOGOVOR' => $object['UF_DOGOVOR'],
            ];
            $result[$object['ID']] = $value;
        }

        return $result;
        // $userId = Bitrix\Main\Engine\CurrentUser::get()->getId();
    }


    public static function getCounters($objectID)
    {

        $classHL = \HLWrap::init(self::$_HL_Counters);

        $filter = ['UF_OBJECT' => $objectID];

        $rsHLoad = $classHL::getList([
            'select' => ['*'],
            'filter' => $filter,
            'order' => []
        ]);

        $result = [];
        while ($counter = $rsHLoad->fetch()) {
            $value = [
                'ID' => $counter['ID'],
                'NAME' => $counter['UF_NAME'],
                'NUMBER' => $counter['UF_NUMBER'],
                'ORG' => $counter['UF_ORG'],
                'TYPE' => $counter['UF_TYPE'],
            ];
            $result[$counter['ID']] = $value;
        }

        return $result;
    }

    public static function getService()
    {

        $classHL = \HLWrap::init(self::$_HL_Service);

        // $filter = ['UF_OBJECT' => $objectID];

        $rsHLoad = $classHL::getList([
            'select' => ['*'],
            'filter' => [],
            'order' => []
        ]);

        $result = [];
        while ($service = $rsHLoad->fetch()) {

            $rsFile = CFile::GetByID($service['UF_ICON']);
            $arFile = $rsFile->Fetch();

            $value = [
                'ID' => $service['ID'],
                'NAME' => $service['UF_NAME'],
                'UNIT' => $service['UF_UNIT'],
                'ICON' => $arFile['SRC'],
            ];
            $result[$service['ID']] = $value;
        }

        return $result;
    }

    /**
     * getUserFields
     *
     * @param mixed
     * @return void
     */
    public static function curentUserFields()
    {

        $userId = \Bitrix\Main\Engine\CurrentUser::get()->getId();

        $result = \Bitrix\Main\UserTable::getList(array(
            'filter' => array('=ID' => $userId),
            'select' => array('ID', 'EMAIL', 'LOGIN', 'NAME', 'LAST_NAME', 'SECOND_NAME', 'UF_*'),
            'order' => array('ID' => 'ASC'),
        ));
        $arUser = $result->Fetch();

        return $arUser;
    }
}
