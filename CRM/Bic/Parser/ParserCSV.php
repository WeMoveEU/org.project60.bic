<?php
/*-------------------------------------------------------+
| Project 60 - Little BIC extension                      |
| Copyright (C) 2014                                     |
| Author: Carlos Capote                                  |
+--------------------------------------------------------+
| This program is released as free software under the    |
| Affero GPL license. You can redistribute it and/or     |
| modify it under the terms of this license which you    |
| can read by viewing the included agpl.txt or online    |
| at www.gnu.org/licenses/agpl.html. Removal of this     |
| copyright header is strictly prohibited without        |
| written permission from the original author(s).        |
+--------------------------------------------------------*/

require_once 'CRM/Bic/Parser/Parser.php';
//require_once 'dependencies/PHPExcel.php';

/**
 * Implement a generic CSV CRM_Bic_Parser_Parser 
 */
class CRM_Bic_Parser_CSV extends CRM_Bic_Parser_Parser {

  static $country_code = 'XX';
  static $page_url = '../../../data/xx-bic-codes.csv';

  function __construct($iso,$file=null) {
    self::$country_code=strtoupper($iso);
    self::$page_url = '../../../data/'.strtolower($iso).'-bic-codes.csv';
  }

  public function update() {
    // first, download the page, it's a CSV file, so more convenient not to use built in method
    $lines = file(dirname(__FILE__).'/'.self::$page_url);
    if (empty($lines)) {
      return $this->createParserOutdatedError(ts("Couldn't read CSV file:"). self::$page_url);
    }

    $data = array();
    $count = 0;
    foreach ($lines as $line) {
      $data[] = str_getcsv($line, ",", '"');
      $count++;
    }
    unset($lines);

    if (empty($count)) {
      return $this->createParserOutdatedError(ts("Couldn't find any bank information in the data source"));
    }

    // process lines instead of first (header)
    for ($i = 1; $i < $count; $i++) {
      $key = $data[$i][1];
   // @param  $entries  a set of array('value'=>national_code, 'label'=>bank_name, 'name'=>BIC, 'description'=>optional data);
      $banks[$key] = array(
        'value' => $key,
        'name' => $data[$i][0],
        'label' => $data[$i][2],
        'description' => '',
      );
    }
    unset($data);

    // finally, update DB
    return $this->updateEntries(self::$country_code, $banks);
  }

  /*
   * Extracts the National Bank Identifier from an Spanish IBAN.
   */
  public function extractNBIDfromIBAN($iban) {
    return array(
      substr($iban, 4, 4),
      substr($iban, 4, 8)
    );
  }
}
