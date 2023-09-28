<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @var string $templateFolder */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */
/** @var CBitrixComponent $component */

\Bitrix\Main\UI\Extension::load("ui.bootstrap4");

?>
    <div class="media">
        <div class="media-body">
            <?php if (!empty($arResult['DETAIL_PICTURE'])): ?>
                <div class="img-post">
                    <img class="d-block img-fluid" src="<?= $arResult['DETAIL_PICTURE'] ?>"
                         alt="<?= $arResult['NAME'] ?>">
                </div>
            <?php endif; ?>
            <h3 class="mt-3"><?= $arResult['NAME'] ?></h3>
            <span class="badge bg-primary text-white"><?= $arResult['DATES'] ?></span>
            <p><?= $arResult['DETAIL_TEXT'] ?></p>

            <span class="feed-inform-item feed-post-time-wrap feed-inform-contentview"><?php
            $APPLICATION->IncludeComponent(
                "bitrix:socialnetwork.contentview.count", "",
                [
                    "CONTENT_ID" => $arResult["CONTENT_ID"],
                    "CONTENT_VIEW_CNT" => ($arResult["ContentViewData"][$arResult["CONTENT_ID"]]["CNT"] ?? 0),
                    "PATH_TO_USER_PROFILE" => "/company/personal/user/#user_id#/",
                    'IS_SET' => 'N'
                ],
                $component,
                [ "HIDE_ICONS" => "Y" ]
            );
            ?></span>

        </div>
    </div>
