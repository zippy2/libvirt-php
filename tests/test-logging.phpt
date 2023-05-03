--TEST--
libvirt_logging
--SKIPIF--
<?php require_once('skipif.inc'); ?>
--FILE--
<?php
require_once('functions.inc');

$logfile = __DIR__.'/test.log';

@unlink($logfile);
echo "# libvirt_logfile_set\n";
var_dump($ret = libvirt_logfile_set($logfile, 1));
if (!$ret)
    die('Cannot enable debug logging to test.log file');

echo "# libvirt_connect\n";
var_dump($conn = libvirt_connect('test:///default'));
if (!is_resource($conn))
    die('Connection to default hypervisor failed');

echo "# libvirt_node_get_info\n";
var_dump($res = libvirt_node_get_info($conn));
if (!is_array($res))
    die('Node get info doesn\'t return an array');

if (!is_numeric($res['memory']))
    die('Invalid memory size');
if (!is_numeric($res['cpus']))
    die('Invalid CPU core count');
unset($res);

echo "# libvirt_connect_get_uri\n";
var_dump($ret = libvirt_connect_get_uri($conn));
if (!$ret)
    die('Invalid URI value');

echo "# libvirt_connect_get_hostname\n";
var_dump($ret = libvirt_connect_get_hostname($conn));
if (!$ret)
    die('Invalid hostname value');

echo "# libvirt_domain_get_counts\n";
var_dump($res = libvirt_domain_get_counts($conn));
if (!$res)
    die('Invalid domain count');

if ($res['active'] != count( libvirt_list_active_domains($conn)))
    die('Numbers of active domains mismatch');

if ($res['inactive'] != count( libvirt_list_inactive_domains($conn)))
    die('Numbers of inactive domains mismatch');

echo "# libvirt_connect_get_hypervisor\n";
var_dump($ret = libvirt_connect_get_hypervisor($conn));
if ($ret == false)
    echo "Warning: Getting hypervisor information failed!\n";

echo "# libvirt_connect_get_maxvcpus\n";
var_dump($ret = libvirt_connect_get_maxvcpus($conn));
if ($ret == false)
    echo "Warning: Cannot get the maximum number of VCPUs per VM!\n";

echo "# libvirt_connect_get_capabilities\n";
if (libvirt_connect_get_capabilities($conn) == false)
    die('Invalid capabilities on the hypervisor connection');

echo "# libvirt_connect_get_information\n";
if (@libvirt_connect_get_information($conn) == false)
    die('No information on the connection are available');

unset($res);
unset($conn);

if (!($fp = fopen($logfile, 'r')))
    die('Unable to open logfile: ' . $logfile);

if (!($log = fread($fp, filesize($logfile))))
    die('Unable to read logfile: ' . $logfile);

if (!fclose($fp))
    die('Unable to close logfile: ' . $logfile);

$ok = strpos($log, 'libvirt_connect: Connection') &&
    strpos($log, 'libvirt_connection_dtor: virConnectClose');

if (!$ok)
    die('Missing entries in the log file');
?>
Done
--CLEAN--
<?php
    @unlink(__DIR__.'/test.log');
?>
--EXPECTF--
# libvirt_logfile_set
bool(true)
# libvirt_connect
resource(6) of type (Libvirt connection)
# libvirt_node_get_info
array(8) {
  ["model"]=>
  string(4) "i686"
  ["memory"]=>
  int(3145728)
  ["cpus"]=>
  int(16)
  ["nodes"]=>
  int(2)
  ["sockets"]=>
  int(2)
  ["cores"]=>
  int(2)
  ["threads"]=>
  int(2)
  ["mhz"]=>
  int(1400)
}
# libvirt_connect_get_uri
string(15) "test:///default"
# libvirt_connect_get_hostname
string(%d) "%s"
# libvirt_domain_get_counts
array(3) {
  ["total"]=>
  int(1)
  ["active"]=>
  int(1)
  ["inactive"]=>
  int(0)
}
# libvirt_connect_get_hypervisor
array(5) {
  ["hypervisor"]=>
  string(4) "TEST"
  ["major"]=>
  int(0)
  ["minor"]=>
  int(0)
  ["release"]=>
  int(2)
  ["hypervisor_string"]=>
  string(10) "TEST 0.0.2"
}
# libvirt_connect_get_maxvcpus
int(32)
# libvirt_connect_get_capabilities
# libvirt_connect_get_information
Done
