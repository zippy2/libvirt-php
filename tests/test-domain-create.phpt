--TEST--
libvirt_domain_create
--SKIPIF--
<?php require_once('skipif.inc'); ?>
--FILE--
<?php
require_once('functions.inc');

echo "# libvirt_connect\n";
var_dump($conn = libvirt_connect('test:///default',  false));
if (!is_resource($conn))
    die('Connection to default hypervisor failed');

$xml = file_get_contents($abs_srcdir.'/data/example-no-disk-and-media.xml');

echo "# libvirt_domain_create_xml\n";
var_dump($res = libvirt_domain_create_xml($conn, $xml));
if (!is_resource($res))
    die('Domain definition failed with error: '.libvirt_get_last_error());

echo "# libvirt_domain_get_info\n";
var_dump($info = libvirt_domain_get_info($res));
if (!$info)
    die('Getting domain information failed with error: '.libvirt_get_last_error());

echo "# libvirt_domain_destroy\n";
var_dump($ret = libvirt_domain_destroy($res));
if (!$ret) {
    unlink($name);
    die('Domain destroy failed with error: '.libvirt_get_last_error());
}

unset($res);
unset($conn);
?>
Done
--EXPECTF--
# libvirt_connect
resource(5) of type (Libvirt connection)
# libvirt_domain_create_xml
resource(8) of type (Libvirt domain)
# libvirt_domain_get_info
array(5) {
  ["maxMem"]=>
  int(65535)
  ["memory"]=>
  int(65535)
  ["state"]=>
  int(1)
  ["nrVirtCpu"]=>
  int(1)
  ["cpuUsed"]=>
  float(%f)
}
# libvirt_domain_destroy
bool(true)
Done
