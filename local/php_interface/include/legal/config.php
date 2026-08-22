<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

return [
    'operator_name' => 'ИП Попов Евгений Игоревич',
    'operator_short' => 'ИП Попов Е. И.',
    'operator_legal_form' => 'ИП',
    'inn' => '366410187215',
    'site' => 'https://akvasan-shop.ru/',
    'site_host' => 'akvasan-shop.ru',
    'email' => 'x77n@mail.ru',
    'phone' => '+7 (473) 229-96-21',
    'address_legal' => '394070, Россия, Воронежская обл., г. Воронеж, ул. Семёновская, д. 54',
    'address_store' => 'г. Воронеж, ул. Холмистая 1г, павильон 113',
    'urls' => [
        'cookie' => '/legal/cookie/',
        'recommendation' => '/legal/recommendation/',
        'personal_data' => '/legal/personal-data/',
        'consent' => '/legal/consent/',
    ],
    'third_parties' => include __DIR__ . '/third_parties_data.php',
];
