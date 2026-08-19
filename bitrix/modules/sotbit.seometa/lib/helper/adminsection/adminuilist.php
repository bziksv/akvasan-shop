<?php

namespace Sotbit\Seometa\Helper\AdminSection;

use Bitrix\Main\UI\Extension;
use Bitrix\UI\Buttons\Button;
use Bitrix\UI\Buttons\Color;
use Bitrix\UI\Buttons\JsCode;
use Bitrix\UI\Toolbar\Facade\Toolbar;
use CAdminPopup;
use CAdminUiContextMenu;
use CAdminUiList;

class AdminUIList extends CAdminUiList
{
    protected function InitContextMenu(array $menu = [], array $additional = []): void
    {
        if (!empty($menu) || !empty($additional)) {
            $this->context = new CAdminNotificationContextMenu($menu, $additional);
        }
    }
}

class CAdminNotificationContextMenu extends CAdminUiContextMenu
{
    private $isShownFilterContext = false;

    public function Show()
    {
        foreach (GetModuleEvents("main", "OnAdminContextMenuShow", true) as $arEvent) {
            ExecuteModuleEventEx($arEvent, array(&$this->items, &$this->additional_items));
        }

        if (empty($this->items) && empty($this->additional_items)) {
            return;
        }

        Extension::load(["ui.buttons"]);

        $this->showBaseButton();
        if (!$this->isShownFilterContext)
        {
            global $APPLICATION;
            \Bitrix\UI\Toolbar\Facade\Toolbar::hideTitle();
            $APPLICATION->IncludeComponent('bitrix:ui.toolbar', 'admin');
        }
    }

    private function showActionButton()
    {
    }

    private function showBaseButton()
    {
        if (!empty($this->items)) {
            $items = $this->items;
            $firstItem = array_shift($items);

            if ($this->isPublicMode) {
                $menuUrl = 'BX.adminList.showPublicMenu(this.getContainer(), ' .
                    CAdminPopup::PhpToJavaScript($items) . ');';
            } else {
                $menuUrl = 'BX.adminList.ShowMenu(this.getContainer(), ' .
                    CAdminPopup::PhpToJavaScript($items) . ');';
            }

            $buttonParams = [
                'color' => Color::PRIMARY,
                'text' => $firstItem['TEXT'],
                'dropdown' => true,
                'click' => new JsCode($menuUrl),
            ];

            Toolbar::addButton(new Button($buttonParams));
        }
    }
}
