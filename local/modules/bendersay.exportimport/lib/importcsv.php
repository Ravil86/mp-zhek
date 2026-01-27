<?php

namespace Bendersay\Exportimport;

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SystemException;

/**
 * Description of importcsv
 *
 * @author Asayants
 */
class ImportCSV extends Import
{
    protected $handler = null;
    protected $firststrung = [];

    public function ImportDataCSV(array $param)
    {

        $hldata = HL\HighloadBlockTable::getById($param['hl_id'])->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hldata);
        $ob_hldata = $entity->getDataClass();
        $res = [];

        $this->handler = $this->OpenFileCSV($param['url_data_file']);
        $this->FirstString($this->handler, $param);
        $this->SetOffset($param['arr_step']['export_step_id']);

        // \Bitrix\Main\Diag\Debug::dumpToFile(var_export($prep_arr, 1), 'ImportCSV::prep_arr', 'test.log');

        $final = $param['arr_step']['export_step_id'] + $param['export_count_row'];
        $res['fields_count'] = $final;
        for ($c = $param['arr_step']['export_step_id']; $c < $final; $c++) {

            if (feof($this->handler)) {
                break;
            }    // если конец файла прерываемся

            if ($data = fgetcsv($this->handler, 0, $param['CSV']['delimiter'], $param['CSV']['enclosure'])) {

                $prep_arr = $this->DataPreparation($data, $param);

                // Есть ошибки, запишим
                if (!empty($prep_arr['error'])) {
                    $res['error'][$c]['text_error'] = str_replace(
                        ['#key#', '#prop#'],
                        [$c, implode(', ', $prep_arr['error'])],
                        Loc::getMessage('BENDERSAY_EXPORTIMPORT_ERROR_IMPORT_FILE_FIELD')
                    );
                    $res['error'][$c]['item'] = $prep_arr['item'];
                    \Bitrix\Main\Diag\Debug::dumpToFile(var_export($res['error'], 1), 'ImportCSV::prep_arr error', 'test.log');
                }

                \Bitrix\Main\Diag\Debug::dumpToFile(var_export($prep_arr['item'], 1), 'ImportCSV::item', 'test.log');

                // Если нет ключа добавляем запись, иначе обновим
                if (empty($param['import_key'])) {
                    $result = $ob_hldata::add($prep_arr['item']);
                } else {

                    $importKeyRow = isset($prep_arr['item'][$param['import_key']])
                        ? $prep_arr['item'][$param['import_key']]
                        : null;

                    \Bitrix\Main\Diag\Debug::dumpToFile($importKeyRow, 'ImportCSV::importKeyRow', 'test.log');

                    if ($importKeyRow) {

                        // Пытаемся найти запись
                        $row = $ob_hldata::getRow([
                            'select' => ['ID'],
                            'filter' => ['=' . $param['import_key'] => (int)$importKeyRow],
                        ]);

                        if ($row) {
                            $result = $ob_hldata::update($row['ID'], $prep_arr['item']);
                            \Bitrix\Main\Diag\Debug::dumpToFile(var_export($row, 1), 'ImportCSV::row update', 'test.log');
                        }
                    } else {
                        unset($prep_arr['item'][$param['import_key']]); //удаляем PRIMARY_KEY из данных, ошибка 
                        $result = $ob_hldata::add($prep_arr['item']);
                    }
                }

                // Запись результатов
                if (!$result->isSuccess()) {
                    \Bitrix\Main\Diag\Debug::dumpToFile(var_export($result->getErrorMessages(), 1), 'ImportCSV::result error', 'test.log');
                    $res['error'][$c]['text_error'] = $result->getErrorMessages();
                    $res['error'][$c]['item'] = $prep_arr['item'];
                }
            }

            // +1 ибо заголовки считали мимо цикла
            $res['step_id'] = $c + 1;
        }

        fclose($this->handler);

        return $res;
    }

    /**
     * Открывает CSV
     *
     * @param string $filename
     *
     * @return type
     *
     * @throws SystemException Если нет файла для ипорта
     */
    public function OpenFileCSV($filename)
    {

        if (!file_exists(\Bitrix\Main\Application::getDocumentRoot() . $filename)) {
            throw new SystemException(Loc::getMessage('BENDERSAY_EXPORTIMPORT_ERROR_GETUSERENTITYIMPORT'));
        } else {
            if (($handle = fopen(\Bitrix\Main\Application::getDocumentRoot() . $filename, 'r')) !== false) {
                return $handle;
            }
        }
    }

    /**
     * Пропускает нужное кол-во строк
     *
     * @param int $line
     *
     * @throws SystemException
     */
    public function SetOffset($line)
    {
        if (!$this->handler) {
            throw new SystemException('Invalid file pointer');
        }

        while (!feof($this->handler) && $line--) {
            fgets($this->handler);
        }
    }

    /**
     * Считает кол-во строк в CSV
     *
     * @param string $filename
     *
     * @return int
     */
    public function GetAllItemsCount($filename)
    {
        $handle = $this->OpenFileCSV($filename);
        $i = -1;    // первая строка заголовки
        while (!feof($handle)) {
            fgets($handle);
            $i++;
        }

        return $i;
    }

    /**
     * Получаем ключи(колонки) из 1 строки CSV
     *
     * @param array $handle
     * @param array $param
     *
     * @throws SystemException
     */
    protected function FirstString($handle, array $param)
    {
        if ($data = fgetcsv($handle, 0, $param['CSV']['delimiter'], $param['CSV']['enclosure'])) {
            foreach ($data as $key => $value) {
                $this->firststrung[$value] = $key;
            }
        } else {
            throw new SystemException('Invalid file pointer FirstString');
        }
    }

    /**
     * Готовит строку для записи в Битрикс
     *
     * @param array $item
     * @param array $param
     *
     * @return type
     */
    protected function DataPreparation(array $item, array $param)
    {

        $error = [];
        foreach ($item as $k_item => $v_item) {
            // Обработка файла
            if (!empty($item[$k_item]) && $param['arr_step']['FIELDS_TYPE'][$k_item]['USER_TYPE_ID'] == 'file') {
                if (is_array($item[$k_item])) {
                    $vr_arr = [];
                    foreach ($item[$k_item] as $v_file) {
                        $vr_mak = \CFile::MakeFileArray($v_file);
                        if ($vr_mak == null) {
                            $error[] = $v_file;
                        } else {
                            $vr_arr[] = $vr_mak;
                        }
                    }
                    $item[$k_item] = $vr_arr;
                } else {
                    $item[$k_item] = \CFile::MakeFileArray($v_item);
                    if ($item[$k_item] == null) {
                        $error[] = $v_item;
                    }
                }
            }
        }
        // добавляем ID, если есть ключ для обновления
        if (!empty($param['import_key'])) {
            $param['arr_step']['FIELDS']['ID'] = $param['import_key'];
        }

        // Возвращаем нужные поля
        foreach ($param['arr_step']['FIELDS'] as $key => $field) {
            $value = $item[$this->firststrung[$field]];
            if (is_string($value)) {
                $value = $this->SetCoding($value, $param);
                if (strpos($value, $param['CSV']['delimiter_m']) !== false) {
                    $value = $this->StringToArray($value, $param);
                }
            }
            // Если у поля тип множественное, нужен массив
            if (is_string($value) && $param['arr_step']['FIELDS_TYPE'][$field]['MULTIPLE'] == 'Y') {
                $new_item[$key][] = $value;
            } else {
                $new_item[$key] = $value;
            }
        }

        return ['item' => $new_item, 'error' => $error];
    }

    /**
     * Ставит кодировку
     *
     * @param type $string
     * @param array $param
     *
     * @return string
     *
     * @throws SystemException
     */
    protected function SetCoding($string, array $param)
    {
        $str = iconv($param['export_coding'], LANG_CHARSET . '//IGNORE', $string);
        if ($str === false) {
            throw new SystemException('Error iconv to ' . $string);
        } else {
            return $str;
        }
    }

    protected function StringToArray($string, array $param)
    {
        return explode($param['CSV']['delimiter_m'], $string);
    }
}
