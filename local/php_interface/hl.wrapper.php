<?

CModule::IncludeModule("highloadblock");
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
use Bitrix\Main\Type\DateTime;

class HLWrap{
    private static $_HLID;
    private static $_HLClass;
    private static $_HLEntity;
    private static $_HLFields;

    /**
     *
     * @param string $name - имя сущности
     * @throws Exception
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\SystemException
     * @return \Bitrix\Main\Entity\DataManager
     */

// Получаем значения полей по названию highloadblock
    static function init($name){
        $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getList(array('select'=>array('ID'), 'filter'=>array('=NAME' => $name)))->fetch();
        if(!$hlblock['ID']){
            throw new \Exception('Не верное имя сущности');

            return false;
        }

        self::$_HLID = $hlblock['ID'];
        self::$_HLFields = $GLOBALS['USER_FIELD_MANAGER']->getUserFields('HLBLOCK_'.self::$_HLID);

        $hldata = HL\HighloadBlockTable::getById(self::$_HLID)->fetch();
        $hlentity = HL\HighloadBlockTable::compileEntity($hldata);

        self::$_HLEntity = $hlentity;
        self::$_HLClass = $hldata['NAME'].'Table';

        return self::$_HLClass;
    }

// Получаем значения полей по ID highloadblock	
    static function initByID($ID){
        $hlblock = Bitrix\Highloadblock\HighloadBlockTable::getList(array('select'=>array('ID'), 'filter'=>array('=ID' => $ID)))->fetch();
        if(!$hlblock['ID']){
            throw new \Exception('Не верный ID сущности');

            return false;
        }

        self::$_HLID = $hlblock['ID'];
        self::$_HLFields = $GLOBALS['USER_FIELD_MANAGER']->getUserFields('HLBLOCK_'.self::$_HLID);

        $hldata = HL\HighloadBlockTable::getById(self::$_HLID)->fetch();
        $hlentity = HL\HighloadBlockTable::compileEntity($hldata);

        self::$_HLEntity = $hlentity;
        self::$_HLClass = $hldata['NAME'].'Table';

        return self::$_HLClass;
    }

// Получаем значения полей по названию таблицы highloadblock 
    public static function initByTable($tableName)
    {
        $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getList(array(
            "select" => array("ID"),
            "filter" => array(
                "TABLE_NAME" => $tableName
            )
        ))->fetch();

        return self::initByID($hlblock['ID']);
    }

    static function getID(){
        return self::$_HLID;
    }

    static function getClass(){
        return self::$_HLClass;
    }

    static function getEntity(){
        return self::$_HLEntity;
    }

    public static function getList($arParams){
        $hlDataClass = self::$_HLClass;

        return $hlDataClass::getList($arParams);
    }

    static function add($arFields){
        global $USER_FIELD_MANAGER;

        $hlDataClass = self::$_HLClass;

        $USER_FIELD_MANAGER->checkFields('HLBLOCK_'.self::$_HLID, null, $arFields);

        $result = $hlDataClass::add($arFields);
        if (!$result->isSuccess()){
            #Error

            return false;
        }

        return $result->getId();
    }

    static function update($recordID, $array){
        if(intval($recordID) < 1)
            return false;

        $hlDataClass = self::$_HLClass;

        return $hlDataClass::update($recordID, $array);
    }

    static function delete($recordID){
        if(intval($recordID) < 1)
            return false;

        $hlDataClass = self::$_HLClass;

        return $hlDataClass::delete($recordID);
    }

    static function getEnumProp($code){
        return CUserFieldEnum::GetList(array(), array("USER_FIELD_ID" => self::$_HLFields[ $code ]['ID']));
    }
}
?>