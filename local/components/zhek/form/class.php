<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main;
use Bitrix\Main\SystemException;
use Bitrix\Main\Loader;
use Bitrix\Main\Context as Context;
use Bitrix\Main\Application as Application;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
use Bitrix\Main\Web\Uri;

use Bitrix\Main\Mail\Event;

use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;

class Modal extends \CBitrixComponent implements Controllerable
{
    function executeComponent()
    {
        try {
            $this->checkModules();
            //$this->getResult($isExpert);
            $this->includeComponentTemplate();
        } catch (SystemException $e) {
            ShowError($e->getMessage());
        }
    }

    protected function checkModules()
    {
        Loader::includeModule("highloadblock");
        if (!Loader::includeModule('iblock')) {
            throw new SystemException(Loc::getMessage('CPS_MODULE_NOT_INSTALLED', array('#NAME#' => 'iblock')));
        }
    }

    protected function getResult($arResult)
    {
        $arParams = $this->arParams;
    }

    public function configureActions()
    {
        return [
            'vacancies' => [
                'prefilters' => [],
            ],
            'internship' => [
                'prefilters' => [],
            ],
            'service' => [
                'prefilters' => [],
            ],
            'request' => [
                'prefilters' => [],
            ],
        ];
    }


    public function requestAction()
    {
        $request = Application::getInstance()->getContext()->getRequest();
        $data = $request['DATA'];

        $params = $request['PARAMS'];
        // return $params;

        if (CModule::IncludeModule("iblock")) {

            $el = new CIBlockElement;

            $PROP = [
                'EMAIL' => $data['EMAIL'],
                'PHONE' => $data['PHONE'],
                'DOG' => $data['DOG']
            ];
            $arLoadProductArray = [
                "IBLOCK_ID" => $params['IBLOCK_ID'],
                "PROPERTY_VALUES" => $PROP,
                "PREVIEW_TEXT" => $data['MESSAGE'],
                "NAME" => $data['NAME'],
                "ACTIVE" => "Y",
            ];
            if ($id = $el->Add($arLoadProductArray)){

                // $arEventFields = [
                //     'NAME' => $data['NAME'],
                //     'EMAIL' => $data['EMAIL'],
                //     'PHONE' => $data['PHONE'],
                //     'MESSAGE' => $data['MESSAGE']
                // ];

                $res = CIBlockElement::GetByID($PROP["DOG"]);
                if($ar_res = $res->GetNext())
                    $data['DOG'] = $ar_res['NAME'];
                
                Event::send(array(
                    "EVENT_NAME" => $params['EVENT_NAME'],
                    "LID" => SITE_ID,
                    "C_FIELDS" =>  $data,
                ));

                return $id;
            }
            else{
                return $el->LAST_ERROR;
            }
            // return $request;
        }
    }

    private function sendEmailTo($field)
    {
    /*
     * 
     * name: Отправка сообщения администратору при создании нового элемента
     * 
     */

        $client = $field['CLIENT'];

        if($field['MANAGER']){
            $subject = 'Заявка по услуге '.$field['SERVICE'];
            
            $body = "Заявка по услуге:\r\n";
            $body .= $field['SERVICE']."\r\n\r\n";
        
            $body .= "ФИО:\r\n";
            $body .=  $client['FIO']."\r\n\r\n";
    
            $body .= "E-mail:\r\n";
            $body .=  $client['EMAIL']."\r\n\r\n";

            $body .= "Телефон:\r\n";
            $body .=  $client['PHONE']."\r\n\r\n";
        
            if($client['ORG']){
                $body .= "Организация:\r\n";
                $body .=  $client['ORG']."\r\n\r\n";
            }
            if($client['CITY']){
                $body .= "Город:\r\n";
                $body .=  $client['CITY']."\r\n\r\n";
            }
            if($client['COMMENT']){
                $body .= "Комментарий:\r\n";
                $body .=  $client['COMMENT']."\r\n\r\n";
            }
            
            $mailAdmin = array(
                'MAIL_TO'     => $field['EMAIL_TO'],
                'SUBJECT'     => $subject,
                "MESSAGE"     => $body,
            );
            
            $sendAdmin = Event::send(array(
                "EVENT_NAME" => 'REQUEST_SERVICE',
                "LID" => SITE_ID,
                "C_FIELDS" =>  $mailAdmin,
            ));
        }

        $mailClient = $field['CLIENT'];
        $mailClient['SERVICE'] = $field['SERVICE'];

        Event::send(array(
            "EVENT_NAME" => 'REQUEST_USER_CONFIRM',
            "LID" => SITE_ID,
            "C_FIELDS" =>  $mailClient,
        ));
        
    }

}