<?

use Bitrix\Main;
use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Config\Option;

Loader::includeModule("iblock");

class LKClass
{

    protected static $_HL_Contracts = "Contracts"; // HL организации

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
            ">=" . "UF_DATE"  => new DateTime(date('01.m.Y') . " 00:00:00"),    //Каждый месяц новое значение
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

    /*
    Список данных из HLblock c данными контрактов
    */
    public static function getContracts($orgID = [])
    {

        $classsDocs = \HLWrap::init(self::$_HL_Contracts);

        $yearList = self::getYears();

        $statusList = LKClass::getStatus();

        $filter = [];
        if ($orgID)
            $filter['UF_COMPANY'] = $orgID;

        $rsDocs = $classsDocs::getList(
            array(
                'order'    => ['UF_DATE' => 'desc'],
                'select' => array('*'),
                'filter' => $filter,
            )
        );

        while ($arDoc = $rsDocs->Fetch()) {

            $date = ConvertDateTime($arDoc['UF_DATE'], "DD.MM.Y", "ru");

            if ($arDoc['UF_YEAR'])
            $arDoc['YEAR'] = $yearList[$arDoc['UF_YEAR']];

            $arDoc['NUMBER'] = ($arDoc['UF_NUMBER'] < 10 ? '0' : '') . $arDoc['UF_NUMBER'];

            $arFields = [
                'ID' => $arDoc['ID'],
                'UF_NUMBER' => $arDoc['UF_NUMBER'],
                // 'NUMBER' => $arDoc['UF_NUMBER'],
                'COMPANY' => $arDoc['UF_COMPANY'],
                'UF_DATE' => $arDoc['UF_DATE'],
                'UF_STATUS' => $arDoc['UF_STATUS'],
                'STATUS' => $statusList[$arDoc['UF_STATUS']],
                'UF_SERVICE' => $arDoc['UF_SERVICE'],
                'UF_YEAR' => $arDoc['UF_YEAR'],
                'YEAR' => $arDoc['YEAR'],
                'DATE' => $date,
                'FORMAT_DATE' => FormatDate("j F Y", MakeTimeStamp($arDoc['UF_DATE'])),
                'NUMBER' => $arDoc['NUMBER'],
                'FULL_NUMBER' =>  $arDoc['NUMBER'] . '-' . $arDoc['YEAR'] . ' от ' . $date,   //'№ '.
                // 'DATE' => ConvertDateTime($arDoc['UF_DATE'], "DD.MM.Y GG:MI:SS", "ru")
                //'UF_STATUS' => $getStatusList[$arStatus['UF_STATUS']],
            ];
            $result[$arDoc['ID']] = $arFields;
        }
        // dump($result);
        return $result;
    }


    public static function saveContract($contractID, $data)
    {
        $classHL = \HLWrap::init(self::$_HL_Contracts);
        $classHL::update($contractID, $data);
    }

    public static function getYears()
    {
        HLWrap::init(self::$_HL_Contracts);
        $rsFields = HLWrap::getEnumProp('UF_YEAR');
        while ($field = $rsFields->Fetch()) {
            $result[$field['ID']] = $field['VALUE'];
        }

        return $result;
    }

    public static function getStatus()
    {
        HLWrap::init(self::$_HL_Contracts);
        $rsFields = HLWrap::getEnumProp('UF_STATUS');
        while ($field = $rsFields->Fetch()) {
            $result[$field['ID']] = [
                'VALUE' => $field['VALUE'],
                'CODE' => $field['XML_ID'],
            ];
        }

        return $result;
    }

    /* месяц по году */
    public static function setMonth($setDate = '', $begin, $end)
    {
        // $year = date("Y");
        // $months = array('Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь');

        // $dateStr = date('Y-m-d', strtotime('-5 months'));
        // $dateEnd = date('Y-m-d');
        // $dateEnd = date('Y-m-d', strtotime('+1 months'));

        // $end = (new DateTime($dateEnd));
        // $begin = (new DateTime($dateStr));
        $periods = new DatePeriod($begin, new \DateInterval('P1M'), $end);
        $result = '';
        foreach (array_reverse(iterator_to_array($periods)) as $period) {

            $num = $period->format("n");
            $year = $period->format('Y');
            $month = FormatDate("f", strtotime($year . "-" . $num . "-01"));
            //$select = ((date('Y-m', strtotime($year . "-" . $month . "-01")) == $setDate) ? 'selected="selected"' : '');
            //$month = $months[$num - 1];
            $result .= "<option value='{$num}-{$year}'>{$month} {$year}</option>";
        }

        /*$month = date("m", strtotime("+1 month"));
        // $month = date("m");

        $count = 6; //кол-во месяцев в списке
        $coll = 12;

        if ($allYears) {
            $year--;
            $coll = 24;
        }

        $result = '<optgroup label="' . $year . '">';
        $c = 1;
        for ($coll; $i = 0; $i++) {
            //for ($i = 0; $i <= $coll; $i++) {
            if (!$allYears && $c > $count) {
                break;
            }
            if ($month > 11) {
                $month = 1;
                $year--;
                // $year++;
                $result .= '</optgroup><optgroup label="' . $year . '">';
            }
            $value = date('Y-m', strtotime($year . "-" . $month . "-01"));
            //$select = ((date('Y-m', strtotime($year."-".$month."-01"))==$_REQUEST['PROPERTY']['DATA'])?'selected="selected"':'');
            $select = ((date('Y-m', strtotime($year . "-" . $month . "-01")) == $setDate) ? 'selected="selected"' : '');

            $mounth = FormatDate("f", strtotime($year . "-" . $month . "-01"));

            $result .= '<option value="' . $value . '" ' . $select . '>' . $mounth . '</option>';
            $month++;
            $c++;
        }
        $result .= '</optgroup>';*/

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
