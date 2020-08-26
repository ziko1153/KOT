<?php

date_default_timezone_set("Asia/Dhaka");
/* A wrapper to do organise item names & prices into columns */
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

   $left_count = $nameProcessCount - $catProcessCount;

   for ($i = 0; $i < $left_count; $i++) {

    $catProcessLine[$catProcessCount + $i] = str_pad(' ', $midcols, ' ', STR_PAD_BOTH);
   }
   $start = $nameProcessCount;
  } else if ($nameProcessCount < $catProcessCount) {

   $left_count = $catProcessCount - $nameProcessCount;

   for ($i = 0; $i < $left_count; $i++) {
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

  echo $val = "$left$mid$right\n" . $process;
  return $val;

 }
}
/* Change to the correct path if you copy this example! */
require __DIR__ . '/vendor/autoload.php';
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

/**
 * Install the printer using USB printing support, and the "Generic / Text Only" driver,
 * then share it (you can use a firewall so that it can only be seen locally).
 *
 * Use a WindowsPrintConnector with the share name to print.
 *
 * Troubleshooting: Fire up a command prompt, and ensure that (if your printer is shared as
 * "Receipt Printer), the following commands work:
 *
 *  echo "Hello World" > testfile
 *  copy testfile "\\%COMPUTERNAME%\Receipt Printer"
 *  del testfile
 */
try {
 // Enter the share name for your USB printer here
 //$connector = null;
 $connector = new WindowsPrintConnector("POS-80C");

 /* Print a "Hello world" receipt" */
 $printer = new Printer($connector);
 //$printer->feed(100);
 //$printer->pulse();
 //$printer->cut();

 /* Close printer */

 $kotNo = "KOT NO: ";
 $saleType = "Sale Type: ";
 $table = "Table: ";
 $waiter = "Waiter: ";
 $dateTime = date('l jS \of F Y h:i:s A');
 if (isset($_POST['data']['kotNumber'])) {
  $kotNo .= $_POST['data']['kotNumber'];
 } else if (isset($_GET['data']['kotNumber'])) {
  $kotNo .= $_GET['data']['kotNumber'];
 }
 if (isset($_POST['data']['saleType'])) {
  if ($_POST['data']['saleType'] == '0') {
   $type = 'General';
  } else if ($_POST['data']['saleType'] == '1') {
   $type = 'Parcel/Take Away';
  }

  $saleType .= $type;
 } else if (isset($_GET['data']['saleType'])) {
  if ($_GET['data']['saleType'] == '0') {
   $type = 'General';
  } else if ($_GET['data']['saleType'] == '1') {
   $type = 'Parcel/Take Away';
  }

  $saleType .= $type;
 }
 if (isset($_POST['data']['tableName'])) {
  $table .= $_POST['data']['tableName'];
 } else if (isset($_GET['data']['tableName'])) {
  $table .= $_GET['data']['tableName'];
 }
 if (isset($_POST['data']['waiterName'])) {
  $waiter .= $_POST['data']['waiterName'];
 } else if (isset($_GET['data']['waiterName'])) {
  $waiter .= $_GET['data']['waiterName'];
 }
 $new_products = array();
 $old_products = array();
 $count_new = 0;
 $count_cancel = 0;
/* Information for the receipt */

/* Start the printer */
 $logo = EscposImage::load("companylogo.png", false);

/* Print top logo */
 $printer->setJustification(Printer::JUSTIFY_CENTER);
 $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
 $printer->bitImage($logo);

/* Details KOT */
 $printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
 $printer->setTextSize(2, 4);
 $printer->text("Kitchen Order Tickets\n");
 if ((isset($_POST['data']['tableStatus']) && $_POST['data']['tableStatus'] == 1) || (isset($_GET['data']['tableStatus']) && $_GET['data']['tableStatus'] == 1)) {
  $printer->selectPrintMode();
  $printer->setTextSize(2, 3);
  $printer->text("Duplicate "); /// Kot No Show
  $printer->feed();
 }
 $printer->selectPrintMode();
 $printer->text($kotNo); /// Kot No Show
 $printer->feed();
 $printer->selectPrintMode();
 $printer->text($saleType); /// Sale Type Show
 $printer->feed();
 $printer->selectPrintMode();
 $printer->text($table); /// Table No Show
 $printer->feed();
 $printer->selectPrintMode();
 $printer->text($waiter); /// Waiter No Show
 $printer->feed(1);
/* Title of receipt */
 if (isset($_POST['new__order_product_list'])) {
  for ($i = 0; $i < count($_POST['new__order_product_list']); $i++) {
   array_push($new_products, new item($_POST['new__order_product_list'][$i]['productName'], $_POST['new__order_product_list'][$i]['category'], $_POST['new__order_product_list'][$i]['qty']));
   $count_new = count($_POST['new__order_product_list']);
  }
  if ($count_new > 0) {

   $printer->setEmphasis(true);
   $printer->setTextSize(1, 1);
   $printer->text("New Food Order\n");
   $printer->setEmphasis(false);
   $printer->feed(1);

   /* New Order Items */
   $printer->setJustification(Printer::JUSTIFY_LEFT);
   $printer->setEmphasis(true);
   $printer->text(new item('Name', 'Category', 'Qty'));
   $printer->feed(1);
   $printer->setEmphasis(false);
   foreach ($new_products as $item) {
    $printer->setTextSize(1, 1);
    $printer->text($item);
    $printer->feed();
   }
  }
 } else if (isset($_GET['new__order_product_list'])) {
  for ($i = 0; $i < count($_GET['new__order_product_list']); $i++) {
   array_push($new_products, new item($_GET['new__order_product_list'][$i]['productName'], $_GET['new__order_product_list'][$i]['category'], $_GET['new__order_product_list'][$i]['qty']));
   $count_new = count($_GET['new__order_product_list']);
  }
  if ($count_new > 0) {

   $printer->setEmphasis(true);
   $printer->setTextSize(1, 1);
   $printer->text("New Food Order\n");
   $printer->setEmphasis(false);
   $printer->feed(1);

   /* New Order Items */
   $printer->setJustification(Printer::JUSTIFY_LEFT);
   $printer->setEmphasis(true);
   $printer->text(new item('Name', 'Category', 'Qty'));
   $printer->feed(1);
   $printer->setEmphasis(false);
   foreach ($new_products as $item) {
    $printer->setTextSize(1, 1);
    $printer->text($item);
    $printer->feed();
   }
  }
 }

/*  Old Order Items */

 if (isset($_POST['cancel_order_product_list'])) {

  for ($o = 0; $o < count($_POST['cancel_order_product_list']); $o++) {
   array_push($old_products, new item($_POST['cancel_order_product_list'][$o]['productName'], $_POST['cancel_order_product_list'][$o]['category'], $_POST['cancel_order_product_list'][$o]['qty']));
  }
  $count_cancel = count($_POST['cancel_order_product_list']);
  if ($count_cancel > 0) {

   /* Title of receipt */
   $printer->setEmphasis(true);
   $printer->setTextSize(1, 2);
   $printer->text("Cancel Food Order\n");
   $printer->setEmphasis(false);
   $printer->feed(1);

   /* New Order Items */
   $printer->setJustification(Printer::JUSTIFY_LEFT);
   $printer->setEmphasis(true);
   $printer->text(new item('Name', 'Category', 'Qty'));
   $printer->feed(1);
   $printer->setEmphasis(false);
   foreach ($old_products as $item) {
    $printer->text($item);
    $printer->feed();

   }

  }

 } else if (isset($_GET['cancel_order_product_list'])) {

  for ($o = 0; $o < count($_GET['cancel_order_product_list']); $o++) {
   array_push($old_products, new item($_GET['cancel_order_product_list'][$o]['productName'], $_GET['cancel_order_product_list'][$o]['category'], $_GET['cancel_order_product_list'][$o]['qty']));
  }
  $count_cancel = count($_GET['cancel_order_product_list']);
  if ($count_cancel > 0) {

   /* Title of receipt */
   $printer->setEmphasis(true);
   $printer->setTextSize(1, 2);
   $printer->text("Cancel Food Order\n");
   $printer->setEmphasis(false);
   $printer->feed(1);

   /* New Order Items */
   $printer->setJustification(Printer::JUSTIFY_LEFT);
   $printer->setEmphasis(true);
   $printer->text(new item('Name', 'Category', 'Qty'));
   $printer->feed(1);
   $printer->setEmphasis(false);
   foreach ($old_products as $item) {
    $printer->text($item);
    $printer->feed();

   }

  }

 }

/* Footer */

 $printer->setJustification(Printer::JUSTIFY_CENTER);
 $printer->feed(2);
 $printer->setDoubleStrike();
 $printer->text($dateTime . "\n");
 $printer->setDoubleStrike();
 $printer->feed(2);

 /* Cut the receipt and open the cash drawer */
 //$printer->cut();
 // $printer->pulse();
 if ($count_new > 0 || $count_cancel > 0) {
  $printer->cut();
  $printer->close();
 }

} catch (Exception $e) {
 echo "Couldn't print to this printer: " . $e->getMessage() . "\n";
}

?>

<script>
window.close();
</script>