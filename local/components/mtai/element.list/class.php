<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Iblock\Elements\ElementOfficialNewsTable;
use Bitrix\Main\Loader;
use Bitrix\Main\UI\PageNavigation;
use MTai\Tools\UserContentViewHelper;

class ElementList extends CBitrixComponent
{
    protected UserContentViewHelper $viewHelper;

    public function __construct($component = null)
    {
        parent::__construct($component);

        Loader::includeModule("iblock");

        $this->viewHelper = new UserContentViewHelper();
    }

    public function onPrepareComponentParams($arParams)
    {
        $arParams['PAGE_SIZE'] = $arParams['PAGE_SIZE'] > 0 ? (int)$arParams['PAGE_SIZE'] : 20;

        return $arParams;
    }

    public function executeComponent()
    {
        $pageNavigation = $this->getPageNavigation();

        $query = $this->getQuery($pageNavigation);

        $this->arResult['ITEMS'] = array_map(function ($item) {
            $item["DATE_CREATE"] = $item["DATE_CREATE"]->format("d.m.Y H:i");
            $item["CONTENT_ID"] = $this->viewHelper->getContentId($item["ID"]);

            return $item;
        }, $query->fetchAll());

        $totalCount = $query->queryCountTotal();
        $pageNavigation->setRecordCount($totalCount);
        $this->arResult['NAV_OBJECT'] = $pageNavigation;

        $this->arResult['ContentViewData'] = !empty($this->arResult['ITEMS'])
            ? $this->viewHelper->getViewData(array_column($this->arResult['ITEMS'], "ID"))
            : [];

        $this->includeComponentTemplate();
    }

    protected function getQuery(PageNavigation $pageNavigation)
    {
        $order = ['ID' => 'desc'];
        $query = ElementOfficialNewsTable::query()
            ->setSelect([
                'ID',
                'NAME',
                'DATE_CREATE',
                'PREVIEW_TEXT',
                'CREATED_BY',
            ])
            ->where('ACTIVE', 'Y')
            ->setOrder($order)
            ->setLimit($pageNavigation->getLimit())
            ->setOffset($pageNavigation->getOffset());

        return $query;
    }

    protected function getPageNavigation(): PageNavigation
    {
        $pageNavigation = new PageNavigation('nav');
        $pageNavigation->setPageSize($this->arParams['PAGE_SIZE'])->initFromUri();

        return $pageNavigation;
    }
}
