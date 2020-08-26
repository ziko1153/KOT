<?php

class item {
 private $name;
 private $category;
 private $qty;

 public function __construct($name = '', $category = '', $qty = '') {
  $this->name = $name;
  $this->category = $category;
  $this->qty = $qty;
 }

 public function __toString() {
  $rightCols = 10;
  $leftCols = 30;
  $midcols = 7;

  $left = str_pad($this->name, $leftCols);
  $mid = str_pad($this->category, $midcols, ' ', STR_PAD_BOTH);
  $right = str_pad($this->qty, $rightCols, ' ', STR_PAD_LEFT);
  $nameProcessline = array();
  if (strlen($this->name) > 26) {
   $left = str_pad(substr($this->name, 0, 26), $leftCols);
   $i = 0;
   $start = 26;

   while (1) {
    $count = strlen(substr($this->name, $start, strlen($this->name)));
    if ($count > 26) {
     $subname = substr($this->name, $start, 26);
     $nameProcessline[$i] = str_pad($subname, $leftCols);
     $start = $start * 2;

    } else {
     $subname = substr($this->name, $start, strlen($this->name));
     $nameProcessline[$i] = str_pad($subname, $leftCols);

     break;
    }

    $i++;
   }

  }

  $catProcessLine = array();
  if (strlen($this->category) > 8) {
   $mid = str_pad(substr($this->category, 0, 8), $midcols, ' ', STR_PAD_BOTH);
   $i = 0;
   $start = 8;
   while (1) {
    $count = strlen(substr($this->category, $start, strlen($this->category)));
    if ($count > 8) {
     $subcategory = substr($this->category, $start, 8);
     $catProcessLine[$i] = str_pad($subcategory, $midcols, ' ', STR_PAD_BOTH);
     $start = $start * 2;

    } else {
     $subcategory = substr($this->category, $start, strlen($this->category));
     $catProcessLine[$i] = str_pad($subcategory, $midcols, ' ', STR_PAD_BOTH);

     break;
    }

    $i++;
   }
  }
  $nameProcessCount = count($nameProcessline);
  $catProcessCount = count($catProcessLine);
  $start = 0;
  if ($nameProcessCount > $catProcessCount) {

   $left = $nameProcessCount - $catProcessCount;

   for ($i = 0; $i < $left; $i++) {

    $catProcessLine[$catProcessCount + $i] = str_pad(' ', $midcols, ' ', STR_PAD_BOTH);
   }
   $start = $nameProcessCount;
  } else if ($nameProcessCount < $catProcessCount) {

   $left = $catProcessCount - $nameProcessCount;

   for ($i = 0; $i < $left; $i++) {
    $nameProcessline[$nameProcessCount + $i] = str_pad('', $leftCols);
   }
   $start = $catProcessCount;

  } else {
   $start = $catProcessCount;
  }

  $process = '';
  for ($i = 0; $i < $start; $i++) {

   $process .= $nameProcessline[$i] . $catProcessLine[$i] . "\n";
  }

  $process = '';
  echo "$left$mid$right\n" . $process;

 }

}

$items = array(
 new item("Classic Chicken", "Burgers", "1"),

);