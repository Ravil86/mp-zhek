<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

global $USER;
if ($USER->IsAdmin()) {

    $file = fopen($_SERVER['DOCUMENT_ROOT'] . '/upload/company.csv', 'r');

    /*if (($data = fgetcsv($file)) !== false) {

$columnsCount = count($data);
for ($column = 0; $column < $columnsCount; $column++) {
    $fields[$column]=array( "name"=> "Column " . $column,
    "example" => $data[$column]
    );
    }
    }*/


    while (($line = fgetcsv($file)) !== FALSE) {
        // print_r($line);
        if (is_numeric($line[0]) && !$line[4]) {
            $email = $line[5];
            $login = explode('@', trim($email))[0];

            $domain = substr($email, strpos($email, '@') + 1);
            $parse = explode('.', $domain)[0];
            if ($parse !== 'mail' && $parse !== 'yandex' && $parse !== 'list')
                $login = $login . '-' . $parse;
            // dump($login);

            $row[$line[0]] = [
                'orn_name' => $line[1],
                'user_id' => $line[4],
                'email' => $line[5],
                'org_id' => $line[0],
                'login' => $login,
                'pass' => $line[7],
            ];
        }
    }
    fclose($file);

    $fp = fopen($_SERVER['DOCUMENT_ROOT'] . '/upload/import.csv', 'w');

    foreach ($row as $id => $user) {
        //dump($user['orn_name']);
        //dump(extractText($user['orn_name']));

        $new_user = new CUser;

        $random = generateRandomString(8);

        $arFields = array(
            "NAME" => extractText($user['orn_name']),
            "EMAIL" => $user['email'],
            "LOGIN" => $user['login'],
            "ACTIVE" => "Y",
            "GROUP_ID" => array(3, 4, 8),
            "PASSWORD" => $random,
            "CONFIRM_PASSWORD" => $random,
            "UF_PASSWORD" => $random
        );
        if ($ID = $new_user->Add($arFields)) {

            dump($arFields);

            $data['UF_USER_ID'] = $ID;
            LKClass::saveCompany($user['org_id'], $data);

            //dump($user);
            $file = array(
                $user['org_id'],
                $user['orn_name'],
                $ID,
                $user['login'],
                $random,
                $user['email'],
            );

            fputcsv($fp, $file);
        }
    }

    fclose($fp);

    //dump($row);
}

function extractText($string)
{
    // Ищем текст между крайними кавычками "" (включая вложенные)
    if (preg_match('/["«](.*(?:".*"[^"]*)?)["»]/u', $string, $matches)) {
        return trim($matches[1]);
    }

    // Если кавычек нет, берем последнее слово
    $words = preg_split('/\s+/u', trim($string));
    return end($words);
}

function generateRandomString($length = 12)
{
    // Определяем группы символов
    $lowerCase = 'abcdefghijklmnopqrstuvwxyz';
    $upperCase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $numbers = '0123456789';
    $specialChars = '!@#$%^&*()_+-=[]{}|;<>?';

    // Объединяем все символы
    $allChars = $lowerCase . $upperCase . $numbers . $specialChars;

    // Гарантируем минимум по одному символу из каждой группы
    $password = [
        $lowerCase[random_int(0, strlen($lowerCase) - 1)],
        $upperCase[random_int(0, strlen($upperCase) - 1)],
        $numbers[random_int(0, strlen($numbers) - 1)],
        $specialChars[random_int(0, strlen($specialChars) - 1)]
    ];

    // Добавляем случайные символы до нужной длины
    for ($i = count($password); $i < $length; $i++) {
        $password[] = $allChars[random_int(0, strlen($allChars) - 1)];
    }

    // Перемешиваем массив и преобразуем в строку
    shuffle($password);
    return implode('', $password);
}
