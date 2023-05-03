test_scripts = \
	runtests.sh \
	check_parameters.sh \
	test-conn-limit.phpt \
	test-connect-get-capabilities.phpt \
	test-connect-get-emulator.phpt \
	test-connect-get-info.phpt \
	test-connect-get-machine-types.phpt \
	test-connect.phpt \
	test-domain-create-and-coredump.phpt \
	test-domain-create-and-get-xpath.phpt \
	test-domain-create-and-shutdown.phpt \
	test-domain-create-get-metadata.phpt \
	test-domain-create.phpt \
	test-domain-define-create-destroy.phpt \
	test-domain-define-undefine.phpt \
	test-domain-define.phpt \
	test-domain-disk.phpt \
	test-domain-get.phpt \
	test-domain-lookup-by-name.phpt \
	test-domain-nic.phpt \
	test-get-emulator.phpt \
	test-get-models-unsupported-hw.phpt \
	test-get-models.phpt \
	test-install.phpt \
	test-logfile.phpt \
	test-logging.phpt \
	test-version-check.phpt \
	test-version-get.phpt

TESTS = $(test_scripts)

EXTRA_DIST = \
	$(test_scripts) \
	check_parameters.php \
	data \
	functions.inc.in \
	php.ini \
	run-tests.php \
	runtests-report.sh \
	skipif.inc \
	skipif_qemu.inc \
	test-domain-snapshot.phpt

functions.inc: functions.inc.in
	$(AM_V_GEN) sed -e 's|[@]abs_srcdir[@]|$(abs_srcdir)|g' \
	< $< > $@-t && \
	mv $@-t $@

check_parameters.sh: functions.inc

TEST_EXTENSIONS = .phpt
PHPT_LOG_COMPILER = php
AM_PHPT_LOG_FLAGS = $(abs_srcdir)/run-tests.php -n -c $(abs_srcdir)/php.ini -v -d 'include_path=$(abs_srcdir)'

TESTS_ENVIRONMENT = \
	abs_builddir="$(abs_builddir)" \
	abs_srcdir="$(abs_srcdir)" \
	LC_ALL=C

CLEANFILES = functions.inc
