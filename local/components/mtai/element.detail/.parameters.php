<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentParameters = array(
	'GROUPS' => array(),
	'PARAMETERS' => array(
        'ID' => array(
            'TYPE' => 'STRING',
            'MULTIPLE' => 'N',
            'DEFAULT' => '',
            'PARENT' => 'BASE',
            'NAME' => GetMessage('ELEMENT_DETAIL_ID'),
        ),
	),
);

?>