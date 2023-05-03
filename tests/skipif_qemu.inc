<?php
  if (!extension_loaded('libvirt')) die('skip libvirt extension not available');

  $conn = @libvirt_connect("qemu:///session", false);
  if (!is_resource($conn))
    die("skipped: unable to connect to libvirt");
  unset($conn);
?>
