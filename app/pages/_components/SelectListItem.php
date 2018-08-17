<?php

/*
 *
 * @author: C. Moller <xavier.tnc@gmail.com>
 * @date: 17 Augustus 2018
 *
 */

class SelectListItem {

  protected $value = null;
  protected $label = 'Option';
  protected $selected = false;

  
  public function __construct($index, $value, $label = null, $selectedValue = null)
  {
    $this->index = $index;
    $this->value = $value;
    $this->label = $label ?: $value;
    $this->selected = $value == $selectedValue;
  }

  
  public function draw()
  {
    return '<option value="' . $this->value . ($this->selected ? '" selected>' : '">') . ($this->label ?: $this->value) . '</option>' . PHP_EOL;
  }

}