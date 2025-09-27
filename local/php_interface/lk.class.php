<?

use Bitrix\Main;
use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Config\Option;

use Bitrix\Main\Error;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Result;
use Bitrix\Main\Engine\Response\AjaxJson;


Loader::includeModule("iblock");

class LKClass
{

    protected static $_HL_Contracts = "Contracts"; // HL организации

    protected static $_HL_Company = "Company"; // HL организации
    protected static $_HL_Objects = "Objects"; // HL Объекты
    protected static $_HL_Counters = "Counters"; // HL ПРиборы учёта
    protected static $_HL_Service = "Service"; // HL типы услуг
    protected static $_HL_Meter = "Meter"; // HL типы услуг
    protected static $_HL_Losses = "Losses"; // HL типы услуг
    protected static $_HL_Month = "Month"; // HL месяцы
    protected static $_HL_Related = "RelatedCounters"; // HL месяцы

    protected static $MASTER = "MASTER"; // код группы Мастер участка
    protected static $ORG = "ORG"; // код группы Организации

    protected ErrorCollection $errorCollection;

    public function getErrors(): array
    {
        return $this->errorCollection->toArray();
    }
    public function getErrorByCode($code): Error
    {
        return $this->errorCollection->getErrorByCode($code);
    }

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

    public static function meters($objectID, $last = false, $month = '', $year = '')
    {
        $classHL = \HLWrap::init(self::$_HL_Meter);

        // $filter = [];
        $filter = ['UF_OBJECT' => $objectID];
        if ($month) {       //c фильтром по кокретному месяцу
            $filterYear = $year ?: 'Y';
            if ($last) {      //только выбранный
                //$filter[">=" . "UF_DATE"] = new DateTime(date('01.01.' . $filterYear) . " 00:00:00");
                $filter["UF_MONTH"] = self::getMonth($month);
                // $filter[">=" . "UF_DATE"] = new DateTime(date('01.' . $month . '.' . $filterYear) . " 00:00:00");
                // $filter["<=" . "UF_DATE"] = new DateTime(date('29.' . $month . '.' . $filterYear) . " 00:00:00");        // для февраля чтоб не захватить март
            } else {
                $filter['<' . "UF_MONTH"] = self::getMonth($month);
                // $filter["<=" . "UF_DATE"] = new DateTime(date('01.' . $month . '.' . $filterYear) . " 00:00:00");
            }
        } else {
            if ($last)
                $filter[">=" . "UF_DATE"] = new DateTime(date('01.m.Y') . " 00:00:00");
            else
                $filter['<=' . 'UF_DATE'] = new DateTime(date('01.m.Y') . " 00:00:00");
        }

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
                'MONTH' => $value['UF_MONTH'],
                'NOTE' => $value['UF_NOTE'],
                // 'SERVICE' => $value['UF_SERVICE'],
            ];
            $result[$value['ID']] = $meter;
        }
        return $result;
    }

    public static function saveMeter($objectID, $month, $counter, $meter, $note = '')
    {

        $classHL = \HLWrap::init(self::$_HL_Meter);

        if ($note) {
            $note = htmlspecialchars($note);
            $note = preg_replace("/\d{1,2}\.\d{1,2}\.\d{2}\s+\d{1,2}:\d{2}/", "", $note);
            $note = preg_replace("/(\n|\r)/", "", $note);
            $note = date('d.m.y H:i') . '&#10;' . $note;
        }

        $value = [
            'UF_METER' => $meter,
            // 'UF_DATE' => date('d.m.Y H:i:s'),
            'UF_MONTH' => $month,
            'UF_OBJECT' => $objectID,
            'UF_COUNTER' => $counter,
            'UF_NOTE' => $note,
        ];

        $filter = [
            'UF_OBJECT' => $objectID,
            'UF_MONTH' => $month,
            //">=" . "UF_DATE"  => new DateTime(date('01.m.Y') . " 00:00:00"),    //Каждый месяц новое значение
            'UF_COUNTER' => $counter,
        ];
        // dump($filter);

        $rsHLoad = $classHL::getList([
            'select' => ['*'],
            'filter' => $filter,
            'order' => ['UF_DATE' => 'DESC']
        ]);
        if ($arMeter = $rsHLoad->Fetch()) {
            $result = $classHL::update($arMeter["ID"], $value);
        } else {
            $result = $classHL::add($value);
        }
        if (!$result->isSuccess()) {
            $errorCollection = new ErrorCollection($result->getErrors());
            return AjaxJson::createError($errorCollection);
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
    }

    public static function deleteCounter($data)
    {

        $classHL = \HLWrap::init(self::$_HL_Counters);
        $classHL::delete($data);
    }

    public static function updateCounter($counterID, $data)
    {

        $classHL = \HLWrap::init(self::$_HL_Counters);

        // $data['UF_DATE'] = new DateTime(date('d.m.Y'));

        $result = $classHL::update($counterID, $data);

        if (!$result->isSuccess()) {
            $errorCollection = new ErrorCollection($result->getErrors());
            return AjaxJson::createError($errorCollection);
        }
    }

    public static function updateObject($objectID, $data)
    {

        // $result = new \Bitrix\Main\Result();
        $classHL = \HLWrap::init(self::$_HL_Objects);
        $data['UF_DATE'] = new DateTime(date('d.m.Y'));

        $result = $classHL::update($objectID, $data);
        if (!$result->isSuccess()) {
            // return $errors = $result->getErrorMessages();
            $errorCollection = new ErrorCollection($result->getErrors());
            return AjaxJson::createError($errorCollection);
        }
    }

    public static function saveCompany($companyID, $data)
    {
        $classHL = \HLWrap::init(self::$_HL_Company);
        $result = $classHL::update($companyID, $data);
        if (!$result->isSuccess()) {
            // return $errors = $result->getErrorMessages();
            $errorCollection = new ErrorCollection($result->getErrors());
            return AjaxJson::createError($errorCollection);
        }
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

    public static function getCompany($userID = null, $filter = [], $nav = [], $order = [])
    {

        $classCompany = \HLWrap::init(self::$_HL_Company);

        if ($userID) {
            $arFilter = ['UF_USER_ID' => $userID];
        } elseif (!empty($filter)) {
            if ($filter["FIND"])
                $arFilter['UF_NAME'] = '%' . $filter['FIND'] . '%';
            else
                $arFilter['UF_ACTIVE'] = $filter['UF_ACTIVE'];
        } else
            $arFilter = [];

        // gg($arFilter);
        if (!$order)
            $order = ['UF_ACTIVE' => 'desc', 'ID' => 'asc'];

        $params =  [
            'filter' => $arFilter,
            'select' => array('*'),
            'order' => $order
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
                'ACTIVE' => $object['UF_ACTIVE'],
            ];
            $result[$object['ID']] = $value;
        }

        return $result;
        // $userId = Bitrix\Main\Engine\CurrentUser::get()->getId();
    }


    public static function getCounters($objectID = null)
    {

        $classHL = \HLWrap::init(self::$_HL_Counters);
        $filter = [];
        if ($objectID)
            $filter['UF_OBJECT'] = $objectID;

        $rsHLoad = $classHL::getList([
            'select' => ['*'],
            'filter' => $filter,
            'order' => []
        ]);

        $result = [];
        while ($counter = $rsHLoad->fetch()) {
            if (is_array($objectID))
                $result[$counter['UF_OBJECT']][$counter['ID']] = $counter;
            else
                $result[$counter['ID']] = $counter;
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
    public static function getContracts($orgID = [], $filter = [], $order = [], $nav = [])
    {

        $classsDocs = \HLWrap::init(self::$_HL_Contracts);

        $yearList = self::getYears();

        $statusList = LKClass::getStatus();

        if ($orgID)
            $filter['UF_COMPANY'] = $orgID;

        if (!$order)
            $order = ['UF_DATE' => 'desc'];

        $params = [
            'order'    => $order,
            'select' => array('*'),
            'filter' => $filter
        ];

        if ($nav) {
            $params['limit'] = $nav['limit'];
            $params['offset'] = $nav['offset'];
        }

        // $params['runtime'] = array(new \Bitrix\Main\Entity\ExpressionField('CNT', 'COUNT(*)'));

        $rsDocs = $classsDocs::getList($params);

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

    public static function getLosses($norma = false)
    {

        $classHL = \HLWrap::init(self::$_HL_Losses);

        $filter = [];

        if ($norma)
            $filter['UF_NORM'] = true;
        else
            $filter['UF_NORM'] = false;

        $rsHLoad = $classHL::getList([
            'select' => ['*'],
            'filter' => $filter,
            'order' => []
        ]);

        $result = [];
        while ($losses = $rsHLoad->fetch()) {
            // gg($losses);
            $value = [
                'ID' => $losses['ID'],
                'NAME' => $losses['UF_NAME'],
                'OBJECT' => $losses['UF_OBJECT'],
                'VALUE' => $losses['UF_VALUE'],
                'MONTH' => $losses['UF_MONTH'],
            ];
            $result[$losses['ID']] = $value;
        }

        return $result;
    }

    public static function saveContract($contractID, $data)
    {
        $classHL = \HLWrap::init(self::$_HL_Contracts);
        $result = $classHL::update($contractID, $data);
        if (!$result->isSuccess()) {
            // return $errors = $result->getErrorMessages();
            $errorCollection = new ErrorCollection($result->getErrors());
            return AjaxJson::createError($errorCollection);
        }
    }

    public static function addContract($data)
    {

        //$result = new \Bitrix\Main\Result();
        $classHL = \HLWrap::init(self::$_HL_Contracts);

        $rsHLoad = $classHL::getList([
            'select' => ['ID', 'UF_DATE', 'UF_NUMBER'],
            'filter' => [
                'UF_NUMBER' => $data['UF_NUMBER'],
                'UF_DATE' => $data['UF_DATE'],
                // 'UF_YEAR'   => $data['UF_YEAR'],
            ],
        ]);
        if ($contract = $rsHLoad->fetch()) {

            foreach ($contract as $pid => $value) {
                //$err[] = $pid . ': ' . $value;
            }
            $err = [
                'ID'   => 'ИД: #' . $contract['ID'],
                'UF_NUMBER' => '№ ' . $contract['UF_NUMBER'] . ' от ' . $contract['UF_DATE'],
            ];
            $error = new \Bitrix\Main\Error('Контракт уже имеется:<br> ' . implode('<br>', $err));
            $errorCollection = new ErrorCollection([$error]);
            return AjaxJson::createError($errorCollection);
        } else {
            $addResult = $classHL::add($data);

            if ($addResult->isSuccess()) {
                // $newId = $addResult->getId();
                return 'Контракт добавлен';
                //return $result->setData($addResult->getData());
            } else {
                // return $addResult->getErrorMessages();
                $errorCollection = new ErrorCollection($addResult->getErrors());
                return AjaxJson::createError($errorCollection);
            }
        }
    }

    public static function saveLosses($objectID, $data, $norm = false)
    {
        $classHL = \HLWrap::init(self::$_HL_Losses);

        foreach ($data as $month => $value) {

            if ($value) {

                $rsHLoad = $classHL::getList(
                    [
                        'select' => ['ID'],
                        'filter' => [
                            'UF_OBJECT' => $objectID,
                            'UF_MONTH' => $month,
                            'UF_NORM' => $norm
                        ]
                    ]
                );
                $field = [
                    'UF_OBJECT' => $objectID,
                    'UF_MONTH' => $month,
                    'UF_VALUE' => $value,
                    'UF_NORM' => $norm
                ];
                // return $field;
                if ($id = $rsHLoad->fetch()) {
                    $result = $classHL::update($id['ID'], $field);
                } else {
                    $result = $classHL::add($field);
                }

                if (!$result->isSuccess()) {
                    $errorCollection = new ErrorCollection($result->getErrors());
                    return AjaxJson::createError($errorCollection);
                }
            }
        }
        return true;
    }

    public static function deleteContract($data)
    {
        $classHL = \HLWrap::init(self::$_HL_Contracts);
        $classHL::delete($data);
    }

    public static function getYears($full = false)
    {
        HLWrap::init(self::$_HL_Contracts);
        $rsFields = HLWrap::getEnumProp('UF_YEAR');
        while ($field = $rsFields->Fetch()) {
            if ($full)
                $result[$field['ID']] = [
                    'VALUE' => $field['VALUE'],
                    'CODE' => $field['XML_ID'],
                ];
            else
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

    public static function getMonthEnum()
    {
        HLWrap::init(self::$_HL_Losses);
        $rsFields = HLWrap::getEnumProp('UF_MONTH');
        while ($field = $rsFields->Fetch()) {
            $result[$field['ID']] = [
                'VALUE' => $field['VALUE'],
                'CODE' => $field['XML_ID'],
            ];
        }
        return $result;
    }

    public static function getMonth($code = '')
    {

        $classHL = \HLWrap::init(self::$_HL_Month);
        $rsHLoad = $classHL::getList();
        while ($month = $rsHLoad->fetch()) {
            // gg($month);
            $result[$month['UF_XML_ID']] = [
                'ID' => $month['ID'],
                'NAME' => $month['UF_NAME'],
            ];
        }
        if ($code)
            return $result[$code]['ID'];
        else
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

            $num = $period->format("m");
            // $num = $period->format("n");
            $year = $period->format('Y');

            $month = FormatDate("m", strtotime($year . "-" . $num . "-01"));
            $monthText = FormatDate("f", strtotime($year . "-" . $num . "-01"));

            $select = ((date('m-Y', strtotime("01-" . $month . "-" . $year)) == $setDate) ? 'selected="selected"' : '');
            // $select = ((date('Y-m', strtotime($year . "-" . $month . "-01")) == $setDate) ? 'selected="selected"' : '');
            //$month = $months[$num - 1];

            $result .= "<option value='{$num}-{$year}' {$select}>{$monthText} {$year}</option>";
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
    public static function curentUserFields($userId = null)
    {
        if (!$userId)
            $userId = \Bitrix\Main\Engine\CurrentUser::get()->getId();

        $result = \Bitrix\Main\UserTable::getList(array(
            'filter' => array('=ID' => $userId),
            'select' => array('ID', 'EMAIL', 'LOGIN', 'NAME', 'LAST_NAME', 'SECOND_NAME', 'WORK_POSITION', 'UF_*'),
            'order' => array('ID' => 'ASC'),
        ));
        $arUser = $result->Fetch();

        $arUser['FULL_NAME'] = ($arUser["LAST_NAME"] ? $arUser["LAST_NAME"] . ' ' : '') . $arUser["NAME"] . ($arUser['SECOND_NAME'] ? ' ' . $arUser['SECOND_NAME'] : '');

        return $arUser;
    }

    public static function getRelated($norma = false)
    {

        $classHL = \HLWrap::init(self::$_HL_Related);

        $filter = [];

        // if ($norma)
        //     $filter['UF_NORM'] = true;
        // else
        //     $filter['UF_NORM'] = false;

        $rsHLoad = $classHL::getList([
            'select' => ['*'],
            'filter' => $filter,
            'order' => []
        ]);

        $result = [];
        while ($fields = $rsHLoad->fetch()) {

            // $value = [
            //     'ID' => $losses['ID'],
            //     'NAME' => $losses['UF_NAME'],
            //     'OBJECT' => $losses['UF_OBJECT'],
            //     'VALUE' => $losses['UF_VALUE'],
            //     'MONTH' => $losses['UF_MONTH'],
            // ];
            $result[$fields['UF_OBJECT']] = $fields;
        }
        // gg($result);
        return $result;
    }
}
