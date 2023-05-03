--TEST--
libvirt_get_models_unsupported
--SKIPIF--
<?php require_once('skipif.inc'); ?>
--FILE--
<?php
require_once('functions.inc');

echo "# libvirt_connect\n";
var_dump($conn = libvirt_connect('test:///default',  false));
if (!is_resource($conn))
    die('Connection to default hypervisor failed');

echo "# libvirt_connect_get_soundhw_models\n";
var_dump($soundhw = @libvirt_connect_get_soundhw_models($conn, NULL, VIR_CONNECT_FLAG_SOUNDHW_GET_NAMES));
if (is_bool($soundhw) && !libvirt_get_last_error())
    die("Was able to get soundhw models, but it shouldn't");

echo "# libvirt_connect_get_nic_models\n";
var_dump($nics = @libvirt_connect_get_nic_models($conn));
if (is_bool($nics) && !libvirt_get_last_error())
    die("Was able to get NIC modes, but it shouldn't");

unset($conn);
?>
Done
--EXPECTF--
# libvirt_connect
resource(5) of type (Libvirt connection)
# libvirt_connect_get_soundhw_models
bool(false)
# libvirt_connect_get_nic_models
bool(false)
Done
