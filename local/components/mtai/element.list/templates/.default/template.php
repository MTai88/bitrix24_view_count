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
<div class="container">
    <h2>Elements</h2>

    <?php foreach ($arResult['ITEMS'] as $item) { ?>

        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title"><?= $item["NAME"] ?></h5>
                <p class="card-text"><?= $item["PREVIEW_TEXT"] ?></p>
                <p class="card-text"><small class="text-muted"><?= $item["DATE_CREATE"] ?></small></p>

                <span class="feed-inform-item feed-post-time-wrap feed-inform-contentview"><?php
                    $APPLICATION->IncludeComponent(
                        "bitrix:socialnetwork.contentview.count", "",
                        [
                            "CONTENT_ID" => $item["CONTENT_ID"],
                            "CONTENT_VIEW_CNT" => ($arResult["ContentViewData"][$item["CONTENT_ID"]]["CNT"] ?? 0),
                            "PATH_TO_USER_PROFILE" => "/company/personal/user/#user_id#/",
                            'IS_SET' => 'N'
                        ],
                        $component,
                        [ "HIDE_ICONS" => "Y" ]
                    );
                ?></span>

            </div>
        </div>

    <?php } ?>

    <?php if (empty($arResult['ITEMS'])): ?>
        <div>No items added</div>
    <?php endif ?>

    <?php
    $APPLICATION->IncludeComponent(
        'bitrix:main.pagenavigation',
        '',
        [
            'NAV_OBJECT' => $arResult['NAV_OBJECT'],
            'SEF_MODE' => 'N',
            'AJAX_PARAMS' => [],
        ],
        false,
        ['HIDE_ICONS' => 'Y']
    );
    ?>
</div>