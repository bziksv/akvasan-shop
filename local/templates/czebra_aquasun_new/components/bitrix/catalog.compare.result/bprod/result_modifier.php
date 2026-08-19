<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

$arResult['GROUPS'] = [];

if (empty($arResult['ITEMS']) || !is_array($arResult['ITEMS'])) {
	return;
}

$iblockId = (int)$arParams['IBLOCK_ID'];
$sectionCache = [];
$stopProps = Czebra\Base\Consts::STOP_PROP_ELEMENT;

$resolveGroupSection = static function (int $sectionId) use ($iblockId, &$sectionCache): array {
	if ($sectionId <= 0) {
		return ['ID' => 0, 'NAME' => 'Прочее'];
	}

	if (isset($sectionCache[$sectionId])) {
		return $sectionCache[$sectionId];
	}

	$group = ['ID' => $sectionId, 'NAME' => ''];
	$chain = [];
	$rs = CIBlockSection::GetNavChain($iblockId, $sectionId, ['ID', 'NAME', 'DEPTH_LEVEL']);
	while ($row = $rs->GetNext()) {
		$chain[] = $row;
	}

	foreach ($chain as $row) {
		if ((int)$row['DEPTH_LEVEL'] === 1) {
			$group = ['ID' => (int)$row['ID'], 'NAME' => (string)$row['NAME']];
			break;
		}
	}

	if ($group['NAME'] === '' && !empty($chain)) {
		$last = end($chain);
		$group = ['ID' => (int)$last['ID'], 'NAME' => (string)$last['NAME']];
	}

	$sectionCache[$sectionId] = $group;

	return $group;
};

$groups = [];

foreach ($arResult['ITEMS'] as $item) {
	$group = $resolveGroupSection((int)($item['IBLOCK_SECTION_ID'] ?? 0));
	$groupId = $group['ID'];

	if (!isset($groups[$groupId])) {
		$groups[$groupId] = [
			'ID' => $groupId,
			'NAME' => $group['NAME'],
			'ITEMS' => [],
			'SHOW_PROPERTIES' => [],
		];
	}

	$groups[$groupId]['ITEMS'][] = $item;
}

foreach ($groups as &$group) {
	$showProperties = [];

	foreach ($arResult['SHOW_PROPERTIES'] as $code => $prop) {
		if (in_array($code, $stopProps, true)) {
			continue;
		}

		foreach ($group['ITEMS'] as $item) {
			$value = $item['DISPLAY_PROPERTIES'][$code]['VALUE'] ?? '';
			if (is_array($value)) {
				$value = implode(', ', $value);
			}

			if ((string)$value !== '') {
				$showProperties[$code] = $prop;
				break;
			}
		}
	}

	$group['SHOW_PROPERTIES'] = $showProperties;

	foreach ($group['ITEMS'] as &$item) {
		$filteredProperties = [];

		foreach ($showProperties as $code => $prop) {
			if (isset($item['DISPLAY_PROPERTIES'][$code])) {
				$filteredProperties[$code] = $item['DISPLAY_PROPERTIES'][$code];
			} else {
				$filteredProperties[$code] = ['VALUE' => ''];
			}
		}

		$item['DISPLAY_PROPERTIES'] = $filteredProperties;
	}
	unset($item);
}
unset($group);

$arResult['GROUPS'] = array_values($groups);
