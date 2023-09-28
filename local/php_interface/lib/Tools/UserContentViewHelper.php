<?php

namespace MTai\Tools;


use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\SystemException;
use Bitrix\Socialnetwork\Item\UserContentView;
use Bitrix\Socialnetwork\UserContentViewTable;
use Bitrix\Main\Engine\CurrentUser;

class UserContentViewHelper
{
    protected string $contentTypeId = "IBLOCK_ELEMENT";
    protected int $userId;

    /**
     * @throws LoaderException
     */
    public function __construct()
    {
        Loader::includeModule("socialnetwork");

        $this->userId = CurrentUser::get()->getId();
    }

    public function setContentTypeId(string $contentTypeId): void
    {
        $this->contentTypeId = $contentTypeId;
    }

    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    public function getViewData(array $ids): array
    {
        $contentIdList = [];
        foreach ($ids as $id)
        {
            $contentIdList[] = $this->getContentId($id);
        }

        return (
        !empty($contentIdList)
            ? UserContentView::getViewData([
            'contentId' => $contentIdList
        ])
            : []
        );
    }

    public function getContentId(int $id): string
    {
        return $this->contentTypeId . "-". $id;
    }

    /**
     * @throws SystemException
     */
    public function set(int $contentEntityId): array
    {
        $viewParams = [
            'userId' => $this->userId,
            'typeId' => $this->contentTypeId,
            'entityId' => $contentEntityId,
            'save' => true
        ];

        return UserContentViewTable::set($viewParams);
    }
}
