<?php
    require_once('functions.inc');

	$conn = @libvirt_connect(NULL, false);
	if (!is_resource($conn)) {
		echo "Unable to connect to libvirt";
		exit(0);
	}

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

	$res = libvirt_domain_create_xml($conn, $xml);
	if (!is_resource($res))
		die('Domain definition failed with error: '.libvirt_get_last_error());

	if (libvirt_domain_has_current_snapshot($res) !== FALSE)
		die('An error occurred while getting domain snapshot: '.libvirt_get_last_error());

	if (!is_resource($snapshot_res = libvirt_domain_snapshot_create($res)))
		die('Error on creating snapshot: '.libvirt_get_last_error());

	if (!($xml = libvirt_domain_snapshot_get_xml($snapshot_res)))
		die('Error on getting the snapshot XML description: '.libvirt_get_last_error());

	if (!$xml)
		die('Empty XML description string');

	if (!libvirt_domain_has_current_snapshot($res))
		die('Domain should be having current snapshot but it\'s not having it');

	if (!($snapshot_res2 = libvirt_domain_snapshot_current($res)) ||
		(libvirt_domain_snapshot_get_xml($snapshot_res) != libvirt_domain_snapshot_get_xml($snapshot_res2))) {
		die('Domain should have current snapshot but it returned nothing');
	}

	if (!libvirt_domain_snapshot_revert($snapshot_res))
		die('Cannot revert to the domain snapshot taken now: '.libvirt_get_last_error());

	if (!($snapshots=libvirt_list_domain_snapshots($res)))
		die('Domain snapshots listing query failed: '.libvirt_get_last_error());

	for ($i = 0; $i < sizeof($snapshots); $i++) {
		$cur = libvirt_domain_snapshot_lookup_by_name($res, $snapshots[$i]);
		libvirt_domain_snapshot_delete($cur);
		unset($cur);
	}

	unset($snapshot_res);

	$snapshot_res = libvirt_domain_snapshot_create_xml($res, "<domainsnapshot/>");
	if (!libvirt_domain_snapshot_delete($snapshot_res))
		die('Cannot delete snapshot with children: '.libvirt_get_last_error());

	if (!libvirt_domain_destroy($res))
		die('Domain destroy failed with error: '.libvirt_get_last_error());

	unset($res);
	unset($conn);
?>
