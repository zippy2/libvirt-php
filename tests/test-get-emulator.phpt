--TEST--
libvirt_get_emulator
--SKIPIF--
<?php require_once('skipif.inc'); ?>
--FILE--
<?php
require_once('functions.inc');

echo "# libvirt_connect\n";
var_dump($conn = libvirt_connect('test:///default',  false));
if (!is_resource($conn))
    die('Connection to default hypervisor failed');

echo "# libvirt_connect_get_emulator\n";
var_dump($tmp = libvirt_connect_get_emulator($conn));
if (!is_string($tmp))
    die('Cannot get default emulator');

echo "# libvirt_connect_get_emulator(i686)\n";
var_dump($tmp = libvirt_connect_get_emulator($conn, 'i686'));
if (!is_string($tmp))
    die('Cannot get emulator for i686 architecture');


unset($tmp);
unset($conn);
?>
Done
--EXPECTF--
# libvirt_connect
resource(5) of type (Libvirt connection)
# libvirt_connect_get_emulator
string(16) "/usr/bin/test-hv"
# libvirt_connect_get_emulator(i686)
string(16) "/usr/bin/test-hv"
Done
