--TEST--
libvirt_get_models
--SKIPIF--
<?php require_once('skipif_qemu.inc'); ?>
--CONFLICTS--
all
--FILE--
<?php
require_once('functions.inc');

echo "# libvirt_connect\n";
var_dump($conn = libvirt_connect(NULL,  false));
if (!is_resource($conn))
    die('Connection to default hypervisor failed');

echo "# libvirt_connect_get_soundhw_models\n";
$soundhw = libvirt_connect_get_soundhw_models($conn, NULL, VIR_CONNECT_FLAG_SOUNDHW_GET_NAMES);
if (is_bool($soundhw))
    die('Unable to get soundhw models');
var_dump($soundhw[0]);

echo "# libvirt_connect_get_nic_models\n";
$nics = libvirt_connect_get_nic_models($conn);
if (is_bool($nics))
    die('Unable to get NICs models');
var_dump($nics[0]);

unset($conn);
?>
Done
--EXPECTF--
# libvirt_connect
resource(5) of type (Libvirt connection)
# libvirt_connect_get_soundhw_models
array(2) {
  ["name"]=>
  string(%d) "%s"
  ["description"]=>
  string(%d) "%s"
}
# libvirt_connect_get_nic_models
string(%d) "%s"
Done
