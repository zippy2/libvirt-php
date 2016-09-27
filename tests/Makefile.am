AM_CFLAGS = \
	-I$(top_builddir) -I$(top_srcdir) \
	-I$(top_builddir)/src -I$(top_srcdir)/src

linktest_SOURCES = linktest.c
linktest_CFLAGS =	\
	$(AM_CFLAGS)
linktest_LDADD = ../src/libvirt-php.la
linktest_LDFLAGS = $(NULL)

test_scripts = runtests.sh
test_helpers = linktest

noinst_PROGRAMS = $(test_helpers)

TESTS = $(test_scripts)
EXTRA_DIST = $(test_scripts) \
	runtests-report.sh \
	data \
	functions.phpt.in \
	php.ini \
	test-connect.phpt \
	test-conn-limit.phpt \
	test-domain-create-and-coredump.phpt \
	test-domain-create-and-get-xpath.phpt \
	test-domain-create-get-metadata.phpt \
	test-domain-create.phpt \
	test-domain-define-create-destroy.phpt \
	test-domain-define-undefine.phpt \
	test-domain-snapshot.phpt \
	test-get-emulator.phpt \
	test-get-models.phpt \
	test-get-models-unsupported-hw.phpt \
	test-install.phpt \
	test-logging.phpt \
	test-version-get.phpt \
	test-version-check.phpt

runtests.sh: functions.phpt

functions.phpt: functions.phpt.in
	$(AM_V_GEN)sed \
		-e 's|[@]abs_srcdir[@]|$(abs_srcdir)|g' \
	< $< > $@-t && \
	chmod a+x $@-t && \
	mv $@-t $@

CLEANFILES = functions.phpt
