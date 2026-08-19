<?php
define('DEBUG_MODE', false);
/**
 * Файл инициализации /local/php_interface/init.php
 */

// Устанавливаем повышенные лимиты для предотвращения таймаутов
@set_time_limit(180);
ini_set('max_execution_time', 180);
ini_set('memory_limit', '256M');

// Включаем подробное логирование ошибок для отладки
ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', $_SERVER['DOCUMENT_ROOT'] . '/php_errors.log');

function php_log($message) {
    if (DEBUG_MODE) {
        error_log($message);
    }
}

// Автозагрузка composer
if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/local/vendor/autoload.php")) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/local/vendor/autoload.php');
    php_log("Composer autoload loaded successfully");
}

// ====================================================================
// ОСНОВНАЯ ИНИЦИАЛИЗАЦИЯ ПОСЛЕ ЗАГРУЗКИ BITRIX
// ====================================================================

/**
 * Функция инициализации, которая будет вызвана после загрузки Bitrix
 */
function initializeAfterBitrix() {
    php_log("Starting initializeAfterBitrix()");
    
    // 1. Инициализация событий Czebra
    try {
        if (class_exists('Czebra\Base\Events') && class_exists('Bitrix\Main\EventManager')) {
            $events = new Czebra\Base\Events();
            $events->create();
            php_log("✓ Czebra\Base\Events::create() executed");
        } else {
            php_log("✗ Required classes for Czebra events not available");
        }
    } catch (Exception $e) {
        php_log("Error in Czebra events: " . $e->getMessage());
    } catch (Throwable $t) {
        php_log("Throwable in Czebra events: " . $t->getMessage());
    }
    
    // 2. Инициализация SMTP
    try {
        if (class_exists('Bitrix\Main\Loader')) {
            // Проверяем файл модуля
            $moduleFile = $_SERVER['DOCUMENT_ROOT'] . '/local/modules/czebra.smtp/include.php';
            if (file_exists($moduleFile)) {
                $content = file_get_contents($moduleFile);
                if (strpos($content, '<?php') === 0) {
                    if (Bitrix\Main\Loader::includeModule("czebra.smtp")) {
                        if (Bitrix\Main\Config\Option::get("czebra.smtp", "active", "") == "Y") {
                            // Переопределяем стандартную функцию mail
                            if (!function_exists('custom_mail')) {
                                function custom_mail($to, $subject, $message, $additional_headers = '', $additional_parameters = '') {
                                    $mail = new Czebra\Smtp\Mail();
                                    return $mail->Send($to, $subject, $message, $additional_headers, $additional_parameters);
                                }
                            }
                            php_log("✓ czebra.smtp module loaded and active");
                        }
                    }
                }
            }
        }
    } catch (Exception $e) {
        php_log("Error loading czebra.smtp: " . $e->getMessage());
    }

    php_log("Finished initializeAfterBitrix()");
}

// ====================================================================
// РЕГИСТРАЦИЯ ОБРАБОТЧИКОВ СОБЫТИЙ
// ====================================================================

/**
 * Обработчик для импорта - предотвращает обновление текстов
 */
function DoNotUpdate(&$arFields) {
    if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'import') {
        unset($arFields['PREVIEW_TEXT']);
        unset($arFields['DETAIL_TEXT']);
    }
}

function DoNotAdd(&$arFields) {
    if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'import') {
        unset($arFields['PREVIEW_TEXT']);
        unset($arFields['DETAIL_TEXT']);
    }
}

function akvasanRegistrationPhone(&$arFields) {
    if (($_POST['TYPE'] ?? '') !== 'REGISTRATION') {
        return true;
    }

    $phone = trim((string)($_POST['PERSONAL_PHONE'] ?? ''));
    if ($phone === '' || !preg_match('/^\+7-\d{3}-\d{3}-\d{2}-\d{2}$/', $phone)) {
        global $APPLICATION;
        $APPLICATION->ThrowException('Укажите телефон в формате +7-___-___-__-__');
        return false;
    }

    $arFields['PERSONAL_PHONE'] = $phone;
    return true;
}

// ====================================================================
// КЛАСС SectionsInit
// ====================================================================

class SectionsInit {
    
    protected $select = [
        'ID',
        'NAME',
        'UF_*'
    ];
    
    protected $filter = [
        'IBLOCK_ID' => 5,
        'ACTIVE' => 'Y'
    ];
    
    protected $section;

    public function __construct(array $filter = [], array $select = []) {
        $this->filter = array_merge($this->filter, $filter);
        
        if (!empty($select)) {
            $this->select = array_merge($this->select, $select);
        }
    }
    
    public function run() {
        try {
            return $this->getSections();
        } catch (Exception $e) {
            php_log("SectionsInit error: " . $e->getMessage());
            return null;
        }
    }
    
    private function getSections() {
        if (class_exists('CModule') && CModule::IncludeModule("iblock")) {
            $section = CIBlockSection::GetList(
                ['NAME' => 'asc'], 
                $this->filter, 
                true, 
                $this->select
            );
            return $this->collection($section);
        }
        return null;
    }
    
    private function collection($section) {
        if (isset($this->filter['ID']) && is_array($this->filter['ID'])) {
            $result = [];
            while ($item = $section->GetNext()) {
                $result[] = $item;
            }
            return $result;
        } else {
            return $section->GetNext() ?? null;
        }
    }
}

// ====================================================================
// ОСНОВНОЙ КОД ИНИЦИАЛИЗАЦИИ
// ====================================================================

// Регистрируем обработчики событий
if (function_exists('AddEventHandler')) {
    AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "DoNotUpdate");
    AddEventHandler("iblock", "OnBeforeIBlockElementAdd", "DoNotAdd");
    AddEventHandler("main", "OnBeforeUserRegister", "akvasanRegistrationPhone");
    php_log("✓ Import event handlers registered");
}

// Подключаем дополнительные функции
$functionsFile = $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/functions.php';
if (file_exists($functionsFile)) {
    try {
        require_once($functionsFile);
        php_log("✓ functions.php loaded");
    } catch (Exception $e) {
        php_log("Error loading functions.php: " . $e->getMessage());
    }
}

// Регистрируем инициализацию на момент, когда Bitrix уже загружен
if (defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED === true) {
    // Bitrix уже загружен, инициализируем сразу
    initializeAfterBitrix();
    php_log("✓ Direct initialization (Bitrix already loaded)");
} else {
    // Bitrix еще не загружен, регистрируем отложенную инициализацию
    // Используем два подхода для надежности
    register_shutdown_function('initializeAfterBitrix');
    
    // Также пытаемся инициализировать при первом удобном случае
    if (function_exists('AddEventHandler')) {
        // Регистрируем событие на загрузку модулей
        AddEventHandler("main", "OnAfterEpilog", function() {
            static $initialized = false;
            if (!$initialized) {
                initializeAfterBitrix();
                $initialized = true;
            }
        });
    }
    php_log("✓ Delayed initialization registered");
}

// Обработка фатальных ошибок
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        $errorMsg = sprintf(
            "Fatal error: %s in %s on line %d",
            $error['message'],
            $error['file'],
            $error['line']
        );
        php_log($errorMsg);
    }
});

php_log("init.php loaded successfully");