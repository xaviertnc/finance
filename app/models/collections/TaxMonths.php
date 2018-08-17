<?php

/*
 *
 * @author: C. Moller <xavier.tnc@gmail.com>
 * @date: 17 Augustus 2018
 *
 */

class TaxMonths
{
  const Y1M = [3,4,5,6,7,8,9,10,11,12];
  const Y2M = [1,2];

  protected $months = [];

  public function __construct($taxYear)
  {
    $prevYear = $taxYear - 1;
    foreach (TaxMonths::Y1M as $taxMonth)
    {
      $startTime = strtotime("$prevYear-$taxMonth-01");
      $this->months[date('Y-m-d', $startTime)] = date('M Y', $startTime);
    }
    foreach (TaxMonths::Y2M as $taxMonth)
    {
      $startTime = strtotime("$taxYear-$taxMonth-01");
      $this->months[date('Y-m-d', $startTime)] = date('M Y', $startTime);
    }
  }

  public function getAll()
  {
    return $this->months;
  }

}


$taxMonths = new TaxMonths($taxYear);