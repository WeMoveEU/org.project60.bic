<?php
/*-------------------------------------------------------+
| Project 60 - Little BIC extension                      |
| Copyright (C) 2014                                     |
| French bank information                                |
| Author: scardinius (scardinius -at- chords.pl)         |
| http://www.systopia.de/                                |
+--------------------------------------------------------+
| This program is released as free software under the    |
| Affero GPL license. You can redistribute it and/or     |
| modify it under the terms of this license which you    |
| can read by viewing the included agpl.txt or online    |
| at www.gnu.org/licenses/agpl.html. Removal of this     |
| copyright header is strictly prohibited without        |
| written permission from the original author(s).        |
+--------------------------------------------------------*/

require_once 'CRM/Bic/Parser/ParserCSV.php';

/**
 * Implementation of abstract class defining the basis for national bank info parsers, French banks
 */
class CRM_Bic_Parser_GB extends CRM_Bic_Parser_CSV {

  function __construct () {parent::__construct('GB');}

  /*
   * Extracts the National Bank Identifier from an IBAN.
   */
  public function extractNBIDfromIBAN($iban) {
    return array(
      substr($iban, 4, 4)
    );
  }
}
