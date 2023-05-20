<?php

$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

// Разбиваем строку на части
function getPartsFromFullname($fullname) {
    $parts = explode(' ',($fullname));
    return [
        'surname' => $parts[0],
        'name' => $parts[1],
        'patronomyc' => $parts[2],
    ];
}

//Собираем из часей
function getFullnameFromParts($surname, $name, $patronymic)
{
    return $surname . ' ' .$name . ' ' . $patronymic;
}

//Делаем сокращённое имя
function getShortName($fullname) {
    $parts = getPartsFromFullname($fullname);
    return $parts['name'] . ' ' . mb_substr($parts['surname'], 0, 1, 'UTF-8') . '.';
}

// Определение пола
function getGenderFromName($fullname)
{
    $parts = getPartsFromFullname($fullname);
    $sum = 0;

     
     // Проверяем отчество на окончание "ич" или "вна"
     if (mb_substr($parts['patronomyc'], -2, 2, 'UTF-8') === 'ич') {
        $sum += 1;
    } else if (mb_substr($parts['patronomyc'], -3, 3, 'UTF-8') === 'вна') {
        $sum -= 1;
    } 
    else{
     // Проверяем имя на окончание "а"
    if (mb_substr($parts['name'], -1, 1, 'UTF-8') === 'а') {
    $sum -= 1;
}   else if (
    mb_substr($parts['name'], -1, 1, 'UTF-8') === 'й' ||
    mb_substr($parts['name'], -1, 1, 'UTF-8') === 'н'
) {
    $sum += 1;
}
    // Проверяем фамилию на окончание "в" или "ва"
     if (mb_substr($parts['surname'], -1, 1, 'UTF-8') === 'в') {
        $sum += 1;
    } else if (mb_substr($parts['surname'], -2, 2, 'UTF-8') === 'ва') {
        $sum -= 1;
    }
}   
    if ($sum > 0) {
        return 1; // мужской
    } else if ($sum < 0) {
        return -1; // женский
    } else {
        return 0; // неопределенный
    }
}
//Считаем количество элементов в массиве
$count = count($example_persons_array);

//Подсчитываем сколько человек пренадлежат к определённому полу 
$male_count = 0;
$female_count = 0;
$undefined_count = 0;

foreach ($example_persons_array as $person) {
    $gender = getGenderFromName($person['fullname']);
    if ($gender === 1) {
        $male_count++;
    } elseif ($gender === -1) {
        $female_count++;
    } else {
        $undefined_count++;
    }
}

//Вычисляем проценты
$male_perc = round($male_count * 100 / $count,1);
$female_perc = round($female_count * 100 / $count,1);
$undefined_perc = round($undefined_count *100 / $count,1);

//Выводим процентное соотношение
echo "Гендерный состав аудитории\n";
echo "--------------------------\n";
echo "Мужчины: " . $male_perc . " %\n";
echo "Женщины: " . $female_perc . " %\n";
echo "Неопределенный пол: " . $undefined_perc . " %\n";
echo "--------------------------\n";

//подбираем идеальную пару
function getPerfectPartner($surname, $name, $patronymic, $persons_array)
{
    // Приводим ФИО к заданному формату и склеиваем их
    $fullname = getFullnameFromParts(mb_convert_case($surname, MB_CASE_TITLE), mb_convert_case($name, MB_CASE_TITLE), mb_convert_case($patronymic, MB_CASE_TITLE));
    
    // Определяем пол для переданного ФИО
    $gender = getGenderFromName($fullname);
    
    // Инициализируем переменную для хранения информации о найденной идеальной паре
    $perfect_partner_info = '';
    
    // Перебираем случайным образом людей из массива, пока не найдем человека противоположного пола
    while (true) {
        $random_key = array_rand($persons_array);
        $partner_fullname = $persons_array[$random_key]['fullname'];
        $partner_gender = getGenderFromName($partner_fullname);
        if ($partner_gender === -$gender) {
            break;
        }
    }
    
    // Вычисляем процент совместимости
    $compatibility_percent = round(mt_rand(5000, 10000)/100, 2);
    
    // Формируем строку с информацией о найденной идеальной паре
    $perfect_partner_info .= getShortName($fullname) . ' + ' . getShortName($partner_fullname) . ' = ';
    $perfect_partner_info .= "\n ❤️ Идеально на " . $compatibility_percent . '% ❤️';
    
    return $perfect_partner_info;
}
echo getPerfectPartner('ВоЛКов', 'оЛЕГ', 'Александрович', $example_persons_array);
?>