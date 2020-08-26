<script>
window.close();
</script>
<?php
require __DIR__ . '/vendor/autoload.php';
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

try {
 // Enter the share name for your USB printer here
 //$connector = null;
 $connector = new WindowsPrintConnector("POS-80C");

 $printer = new Printer($connector);

 $printer->pulse();

 $printer->close();

} catch (Exception $e) {
 echo "Couldn't print to this printer: " . $e->getMessage() . "\n";
}

?>