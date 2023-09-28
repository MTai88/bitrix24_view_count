<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Iblock;
use Bitrix\Iblock\Elements\ElementOfficialNewsTable;
use Bitrix\Main\SystemException;
use MTai\Tools\UserContentViewHelper;

class ElementDetail extends CBitrixComponent
{
    protected UserContentViewHelper $viewHelper;

    public function __construct($component = null)
    {
        parent::__construct($component);

        Loader::includeModule("iblock");

        $this->viewHelper = new UserContentViewHelper();
    }

    /**
     * @throws Exception
     */
    public function onPrepareComponentParams($arParams): array
    {
        if (empty($arParams['ID']))
            throw new Exception(Loc::getMessage('ELEMENT_DETAIL_ID_PARAMETER'));

        return $arParams;
    }

    /**
     * @throws SystemException
     */
    public function executeComponent()
    {
        global $APPLICATION;

        $row = ElementOfficialNewsTable::getRow([
            'filter' => [
                "=ACTIVE" => "Y",
                "=ID" => $this->arParams["ID"]
            ],
        ]);

        if ($row) {
            $row = $this->getImage($row);

            $row["CONTENT_ID"] = $this->viewHelper->getContentId($row["ID"]);

            $this->arResult = $row;

            $this->arResult["DATES"] = $this->getDates();

            $this->viewHelper->set($row["ID"]);
            $this->arResult['ContentViewData'] = $this->viewHelper->getViewData([$row["ID"]]);
        } else {
            Iblock\Component\Tools::process404(
                Loc::getMessage('ELEMENT_DETAIL_NOT_FOUND')
                , true
                , "Y"
                , "Y"
            );
        }

        $APPLICATION->SetTitle($this->arResult["NAME"]);

        $this->includeComponentTemplate();
    }

    private function getImage($item): array
    {
        if ($item['DETAIL_PICTURE']) {
            $imageFile = \CFile::GetFileArray($item['DETAIL_PICTURE']);
            if ($imageFile !== false) {
                $item["DETAIL_PICTURE"] = \CFile::GetFileSRC($imageFile);
            } else
                $item["DETAIL_PICTURE"] = false;
        }

        return $item;
    }

    private function getDates(): string
    {
        if (!empty($this->arResult["ACTIVE_TO"])) {
            return Loc::getMessage('ELEMENT_DETAIL_DATES_PERIOD', [
                "#DATE_FROM#" => FormatDate("j F", $this->arResult["ACTIVE_FROM"]->getTimestamp()),
                "#DATE_TO#" => FormatDate("j F Y", $this->arResult["ACTIVE_TO"]->getTimestamp()),
            ]);
        } else {
            return FormatDate("j F Y", $this->arResult["ACTIVE_FROM"]->getTimestamp());
        }
    }
}
