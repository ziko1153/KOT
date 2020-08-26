<?php
/* Change to the correct path if you copy this example! */
$printerName = 'POS-80C';
$command = 1; /// here 1 for cut paper and 2 for open cash drawer ;
if ($command == 1) {
 $commandText = "cutcommand.txt";

} else if ($commnad == 2) {
 $commandText = 'opnedrawercommand.txt';
} else if ($command == 3) {
 $commandText = 'feed.txt';
}
$from = dirname(__FILE__) . '\\' . $commandText;
$to = "\\\\" . gethostname() . "\\" . $printerName;

copy($from, $to);

?>