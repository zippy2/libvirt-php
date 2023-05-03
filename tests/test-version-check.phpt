--TEST--
libvirt_version_check
--SKIPIF--
<?php require_once('skipif.inc'); ?>
--FILE--
<?php
require_once('functions.inc');

echo "# libvirt_version\n";
var_dump($res = libvirt_version());
$virt_major = $res['libvirt.major'];
$virt_minor = $res['libvirt.minor'];
$virt_micro = $res['libvirt.release'];

$bind_major = $res['connector.major'];
$bind_minor = $res['connector.minor'];
$bind_micro = $res['connector.release'];
unset($res);

if (libvirt_check_version($virt_major, $virt_minor, $virt_micro + 1, VIR_VERSION_LIBVIRT))
    die("Checking against release version currently installed libvirt version + 1 failed");

if (!libvirt_check_version($virt_major, $virt_minor, $virt_micro, VIR_VERSION_LIBVIRT))
    die("Checking against currently installed libvirt version failed");

if (libvirt_check_version($bind_major, $bind_minor, $bind_micro + 1, VIR_VERSION_BINDING))
    die("Checking against release version currently installed libvirt-php version + 1 failed");

if (!libvirt_check_version($bind_major, $bind_minor, $bind_micro, VIR_VERSION_BINDING))
    die("Checking against currently installed libvirt-php version failed");

if (!libvirt_check_version($bind_major, $bind_minor, $bind_micro, VIR_VERSION_BINDING))
    die("Bad parameters analyse");
?>
Done
--EXPECTF--
# libvirt_version
array(7) {
  ["libvirt.release"]=>
  int(%d)
  ["libvirt.minor"]=>
  int(%d)
  ["libvirt.major"]=>
  int(%d)
  ["connector.version"]=>
  string(%d) "%s"
  ["connector.major"]=>
  int(%d)
  ["connector.minor"]=>
  int(%d)
  ["connector.release"]=>
  int(%d)
}
Done
