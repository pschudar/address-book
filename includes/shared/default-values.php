<?php

# defaulting initial values
$from_value = '';
$from_unit = '';
$to_unit = '';
$to_value = '';
$typeError = null;

# filter _GET['p'] as a stripped string - defaults to length
$getPage = filter_input(INPUT_GET, 'p', 513) ?? 'length';
# default expected pages - drop_class values to null
$drop_class = ['length' => null, 'area' => null, 'volume' => null, 'mass' => null, 'speed' => null, 'temperature' => null, 'storage' => null, 'fuel' => null, 'time' => null];
# default form_action
$form_action = PHP_SELF . '?p=' . $getPage;

switch ($getPage) {
    case 'length':
        $instance = new \conversion\Length();
        $page_title = 'Convert Length';
        $btn_title = 'Length and distance';
        $drop_class['length'] = 'active';
        $select_options = array_keys(\conversion\Length::CONVERSION_ARRAY);
        break;
    case 'area':
        $instance = new \conversion\Area();
        $page_title = 'Convert Area';
        $btn_title = 'Area';
        $drop_class['area'] = 'active';
        # uses same conversion array as length
        $select_options = $instance->selectOptions();
        break;
    case 'volume':
        $instance = new \conversion\Volume();
        $page_title = 'Convert Volume';
        $btn_title = 'Volume and capacity';
        $drop_class['volume'] = 'active';
        $select_options = array_keys(\conversion\Volume::CONVERSION_ARRAY);
        break;
    case 'mass':
        $instance = new \conversion\Mass();
        $page_title = 'Convert Mass';
        $btn_title = 'Mass and weight';
        $drop_class['mass'] = 'active';
        $select_options = array_keys(\conversion\Mass::CONVERSION_ARRAY);
        break;
    case 'speed':
        $instance = new \conversion\Speed();
        $page_title = 'Convert Speed';
        $btn_title = 'Speed';
        $drop_class['speed'] = 'active';
        $select_options = array_keys(\conversion\Speed::CONVERSION_ARRAY);
        break;
    case 'temperature':
        $instance = new \conversion\Temperature();
        $page_title = 'Convert Temperature';
        $btn_title = 'Temperature';
        $drop_class['temperature'] = 'active';
        $select_options = array_keys(\conversion\Temperature::CONVERSION_ARRAY);
        break;
    case 'digital':
        $instance = new \conversion\DigitalStorage();
        $page_title = 'Convert Digital Storage';
        $btn_title = 'Digital Storage';
        $drop_class['storage'] = 'active';
        $select_options = array_keys(\conversion\DigitalStorage::CONVERSION_ARRAY);
        break;
    case 'fuel':
        $instance = new \conversion\FuelEconomy();
        $page_title = 'Convert Fuel Economy';
        $btn_title = 'Fuel Economy';
        $drop_class['fuel'] = 'active';
        $select_options = array_keys(\conversion\FuelEconomy::CONVERSION_ARRAY);
        break;
    case 'time':
        $instance = new \conversion\Time();
        $page_title = 'Convert Time';
        $btn_title = 'Time';
        $drop_class['time'] = 'active';
        $select_options = array_keys(\conversion\Time::CONVERSION_ARRAY);
        break;
    default:
        $instance = new \conversion\Length();
        $page_title = 'Convert Length';
        $btn_title = 'Length and distance';
        $drop_class['length'] = 'active';
        $select_options = array_keys(\conversion\Length::CONVERSION_ARRAY);
        break;
}