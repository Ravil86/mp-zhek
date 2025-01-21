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

    protected static $MASTER = "MASTER"; // код группы Мастер участка
    protected static $ORG = "ORG"; // код группы Организации

    public static function isMaster()
    {
        global $USER;
        if ($USER->IsAuthorized()) {
            $rsGroups = \CUser::GetUserGroupEx($USER->GetID());

            while ($arGroup = $rsGroups->GetNext()) {
                if ($USER->IsAdmin() || $arGroup['STRING_ID'] === self::$MASTER) {
                    return true;
                }
            }
        } else
            return false;
    }

    public static function isOperator()
    {
        global $USER;
        if ($USER->IsAuthorized()) {
            $rsGroups = \CUser::GetUserGroupEx($USER->GetID());

            while ($arGroup = $rsGroups->GetNext()) {
                if ($USER->IsAdmin() || $arGroup['STRING_ID'] === self::$ORG) {
                    return true;
                }
            }
        } else
            return false;
    }

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

    public static function addCompany($data)
    {

        $classHL = \HLWrap::init(self::$_HL_Company);
        $classHL::add($data);
    }

    public static function addObject($data)
    {

        $classHL = \HLWrap::init(self::$_HL_Objects);
        $classHL::add($data);
    }

    public static function addCounter($data)
    {

        $classHL = \HLWrap::init(self::$_HL_Counters);
        $classHL::add($data);
        // $classHL::update($counterID, $data);
        // $filter = ['ID' => $objectID];

        // $params = array(
        //     "order" => array(
        //         "ID" => "asc"
        //     ),
        //     "filter" => $filter,
        // );
        // $rsVoice = $classHL::getList($params);

        // if ($arVoice = $rsVoice->Fetch()) {

        //     $classHL::update($arVoice["ID"], $value);
        // } else {
        //     $classHL::add($value);
        // }
    }

    public static function saveCounter($counterID, $data)
    {

        $classHL = \HLWrap::init(self::$_HL_Counters);

        $classHL::update($counterID, $data);

        // $filter = ['ID' => $objectID];

        // $value = [
        //     'ID' => $data['ID'],
        //     'NAME' => $data['UF_NAME'],
        //     'NUMBER' => $data['UF_NUMBER'],
        //     'ORG' => $data['UF_ORG'],
        //     'TYPE' => $data['UF_TYPE'],
        // ];

        // $params = array(
        //     "order" => array(
        //         "ID" => "asc"
        //     ),
        //     "filter" => $filter,
        // );
        // $rsVoice = $classHL::getList($params);

        // if ($arVoice = $rsVoice->Fetch()) {

        //     $classHL::update($arVoice["ID"], $value);
        // } else {
        //     $classHL::add($value);
        // }
    }

    public static function saveCompany($companyID, $data)
    {
        $classHL = \HLWrap::init(self::$_HL_Company);
        $classHL::update($companyID, $data);
    }


    public static function myCompany()
    {
        $result = null;

        $userId = Bitrix\Main\Engine\CurrentUser::get()->getId();
        if ($userId)
            $result = self::getCompany($userId);

        //$curentUser = self::curentUserFields();
        //связка организации через св-во у пользователя
        // if ($curentUser['UF_COMPANY'])
        //     $result = self::getCompany($curentUser['UF_COMPANY']);

        // return current($result);
        return $result;
    }

    public static function getCompany($userID = null, $search = [], $nav = [])
    // public static function getCompany($orgID = [], $filter = [], $nav = [])
    {

        $classCompany = \HLWrap::init(self::$_HL_Company);

        if ($userID)
            $arFilter = ['UF_USER_ID' => $userID];
        // if ($orgID)
        //     $arFilter = ['ID' => $orgID];
        elseif ($search)
            $arFilter = ['UF_NAME' => '%' . $search['FIND'] . '%'];
        else
            $arFilter = [];

        $params =  [
            'filter' => $arFilter,
            'select' => array('*'),
        ];

        if ($nav) {
            $params['limit'] = $nav['limit'];
            $params['offset'] = $nav['offset'];
        }
        $rsCompany = $classCompany::getList($params);

        while ($company = $rsCompany->Fetch()) {
            // $arFields = [
            //     'ID' => $company['ID'],
            //     'UF_NAME' => $company['UF_NAME'],
            //     'UF_ADDRESS' => $company['UF_ADDRESS'],
            //     'UF_INN' => $company['UF_INN'],
            //     'UF_USER_ID' => $company['UF_USER_ID']
            // ];
            if ($userID)
                // if ($orgID)
                $result = $company;
            else
                $result[$company['ID']] = $company;
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
                'ORG' => $object['UF_ORG'],
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
            // $value = [
            //     'ID' => $counter['ID'],
            //     'NAME' => $counter['UF_NAME'],
            //     'NUMBER' => $counter['UF_NUMBER'],
            //     'ORG' => $counter['UF_ORG'],
            //     'TYPE' => $counter['UF_TYPE'],
            // ];
            $result[$counter['ID']] = $counter;
            // $result[$counter['ID']] = $value;
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
                'LITERA' => $service['UF_LITERA'],
                'COLOR' => $service['UF_COLOR'],
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
