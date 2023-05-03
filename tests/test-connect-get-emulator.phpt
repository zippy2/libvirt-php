--TEST--
libvirt_connect_get_emulator
--SKIPIF--
<?php require_once('skipif.inc'); ?>
--FILE--
<?php
require_once('functions.inc');

$conn = libvirt_connect('test:///default');
var_dump(libvirt_connect_get_emulator($conn));
unset($conn);
?>
Done
--EXPECTF--
string(%d) "%s"
Done
