<?php

/*
 *
 * @author: C. Moller <xavier.tnc@gmail.com>
 * @date: 17 Augustus 2018
 *
 */

class DropdownSelect {

  public $name = '';
  public $items = [];
  public $selectedValue = null;


  public function __construct($name, Array $list, $selectedValue, $useKeyAsValue = true, $showNullOption = false, $nullOptionLabel = '- Select -')
  {
    $this->name = $name;
    $this->selectedValue = $selectedValue;

    $index = 0; // Keep track globally because of possible NULL option affecting the indexes.

    if ($showNullOption)
    {
      $this->items[$index] = new SelectListItem($index, null, $nullOptionLabel, $selectedValue);
      $index++;
    }

    foreach($list as $key => $value)
    {
      $this->items[$index] = new SelectListItem($index, $useKeyAsValue ? $key : $value, $value, $selectedValue);
      $index++;
    }

  }

  
  public function __toString()
  {
    $html = '<select name="' . $this->name . '">' . PHP_EOL;
    foreach($this->items as $index => $item) { $html .= $item; }
    $html .= '</select>' . PHP_EOL;
    return $html;
  }

}
