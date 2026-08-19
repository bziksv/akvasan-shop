<?php
// test_final.php
echo "Testing final configuration...\n";

// 1. Проверяем автозагрузку
if (file_exists($_SERVER["DOCUMENT_ROOT"]."/local/vendor/autoload.php")) {
    require_once($_SERVER['DOCUMENT_ROOT'] . '/local/vendor/autoload.php');
    echo "✓ Composer autoload loaded\n";
}

// 2. Проверяем класс Events
if (class_exists('Czebra\Base\Events')) {
    echo "✓ Czebra\Base\Events exists\n";
    
    // 3. Проверяем BFS класс
    if (class_exists('Czebra\BFS\Search')) {
        echo "✓ Czebra\BFS\Search exists\n";
    } else {
        echo "✗ Czebra\BFS\Search NOT found (this may cause issues)\n";
    }
} else {
    echo "✗ Czebra\Base\Events NOT found\n";
}

echo "Test completed\n";