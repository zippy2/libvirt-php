--TEST--
libvirt_domain_snapshot
--SKIPIF--
<?php require_once('skipif_qemu.inc'); ?>
--CONFLICTS--
all
--FILE--
<?php
require_once('functions.inc');

echo "# libvirt_connect\n";
var_dump($conn = @libvirt_connect(NULL, false));
if (!is_resource($conn))
    die("Unable to connect to libvirt");

//cleaning
if ($res = @libvirt_domain_lookup_by_name($conn, "test-guest-qcow")) {
    @libvirt_domain_destroy($res);
    @libvirt_domain_undefine($res);
}

$xml = file_get_contents($abs_srcdir.'/data/example-qcow2-disk.xml');

/* This is applicable only for QEMU/KVM so check whether we're on QEMU/KVM */
$e = @libvirt_connect_get_emulator($conn);
if (!$e) {
    echo "Skipping test ...\n";
    exit(0);
}

$fp = popen($e.' --version', 'r');
$data = trim(fgets($fp, 1024));
fclose($fp);

if (substr($data, 0, 4) != 'QEMU') {
    echo "Not running on KVM hypervisor. Skipping ...\n";
    exit(0);
}

exec("qemu-img create -f qcow2 /tmp/example-test.qcow2 1M 2>&1", $output, $retval);
if ($retval != 0) {
    die('Unable to create qcow2 image: ' . $output);
}

echo "# libvirt_domain_create_xml\n";
var_dump($res = libvirt_domain_create_xml($conn, $xml));
if (!is_resource($res))
    die('Domain definition failed with error: '.libvirt_get_last_error());

echo "# libvirt_domain_has_current_snapshot\n";
var_dump($ret = libvirt_domain_has_current_snapshot($res));
if ($ret !== FALSE)
    die('An error occurred while getting domain snapshot: '.libvirt_get_last_error());

echo "# libvirt_domain_snapshot_create\n";
var_dump($snapshot_res = libvirt_domain_snapshot_create($res));
if (!is_resource($snapshot_res))
    die('Error on creating snapshot: '.libvirt_get_last_error());

echo "# libvirt_domain_snapshot_get_xml\n";
var_dump($xml = libvirt_domain_snapshot_get_xml($snapshot_res));
if (!$xml)
    die('Error on getting the snapshot XML description: '.libvirt_get_last_error());

echo "# libvirt_domain_has_current_snapshot\n";
var_dump($ret = libvirt_domain_has_current_snapshot($res));
if (!$ret)
    die('Domain should be having current snapshot but it\'s not having it');

echo "# libvirt_domain_snapshot_current\n";
var_dump($snapshot_res2 = libvirt_domain_snapshot_current($res));
if (!$snapshot_res2 ||
    libvirt_domain_snapshot_get_xml($snapshot_res) != libvirt_domain_snapshot_get_xml($snapshot_res2)) {
    die('Domain should have current snapshot but it returned nothing');
}

echo "# libvirt_domain_snapshot_revert\n";
var_dump($ret = libvirt_domain_snapshot_revert($snapshot_res));
if (!$ret)
    die('Cannot revert to the domain snapshot taken now: '.libvirt_get_last_error());

echo "# libvirt_list_domain_snapshots\n";
var_dump($snapshots=libvirt_list_domain_snapshots($res));
if (!$snapshots)
    die('Domain snapshots listing query failed: '.libvirt_get_last_error());

for ($i = 0; $i < sizeof($snapshots); $i++) {
    $cur = libvirt_domain_snapshot_lookup_by_name($res, $snapshots[$i]);
    libvirt_domain_snapshot_delete($cur);
    unset($cur);
}

unset($snapshot_res);

echo "# libvirt_domain_snapshot_create\n";
var_dump($snapshot_res = libvirt_domain_snapshot_create_xml($res, "<domainsnapshot/>"));

echo "# libvirt_domain_snapshot_delete\n";
var_dump($ret = libvirt_domain_snapshot_delete($snapshot_res));
if (!$ret)
    die('Cannot delete snapshot with children: '.libvirt_get_last_error());

echo "# libvirt_domain_destroy\n";
var_dump($ret = libvirt_domain_destroy($res));
if (!$ret)
    die('Domain destroy failed with error: '.libvirt_get_last_error());

unset($res);
unset($conn);
?>
Done
--CLEAN--
<?php
    @unlink('/tmp/example-test.qcow2');
?>
--EXPECTF--
# libvirt_connect
resource(5) of type (Libvirt connection)
# libvirt_domain_create_xml
resource(10) of type (Libvirt domain)
# libvirt_domain_has_current_snapshot
bool(false)
# libvirt_domain_snapshot_create
resource(11) of type (Libvirt domain snapshot)
# libvirt_domain_snapshot_get_xml
string(%d) "%a"
# libvirt_domain_has_current_snapshot
bool(true)
# libvirt_domain_snapshot_current
resource(12) of type (Libvirt domain snapshot)
# libvirt_domain_snapshot_revert
bool(true)
# libvirt_list_domain_snapshots
array(1) {
  [0]=>
  string(%d) "%s"
}
# libvirt_domain_snapshot_create
resource(14) of type (Libvirt domain snapshot)
# libvirt_domain_snapshot_delete
bool(true)
# libvirt_domain_destroy
bool(true)
Done
