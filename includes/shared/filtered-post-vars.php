<?php

/**
 * _POST['from_value'] filtered as stripped|string
 * 
 * @var string
 */
$from_value = filter_input(INPUT_POST, 'from_value', 513);
/**
 * _POST['from_unit'] filtered as stripped|string
 * 
 * @var string
 */
$from_unit = filter_input(INPUT_POST, 'from_unit', 513);
/**
 * _POST['to_unit'] filtered as stripped|string
 * 
 * @var string
 */
$to_unit = filter_input(INPUT_POST, 'to_unit', 513);
