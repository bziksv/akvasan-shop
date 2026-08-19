<?php
namespace Czebra\Base;

use Bitrix\Main\EventManager;

class Events
{
    public function create()
    {
        $eventManager = EventManager::getInstance();

        //Поиск по заголовку и артиклу
        $eventManager->addEventHandler("search", "BeforeIndex", Array("Czebra\\BFS\\Search", "BeforeIndexHandler"));
    }
}
