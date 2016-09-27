/*
 * Copyright (C) 2016 Red Hat, Inc.
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library.  If not, see
 * <http://www.gnu.org/licenses/>.
 *
 * Author: Michal Privoznik <mprivozn@redhat.com>
 */

#include <config.h>

#include <stdlib.h>

#include "util.h"

void *get_module(void);

int main(int argc, char *argv[])
{
    void *module;

    /* The only aim of this test is to catch link errors as those
     * are hard to trace for resulting .so library. Therefore,
     * the fact this test has been built successfully means
     * there's no linkage problem and therefore success is
     * returned. */
    module = get_module();

    return EXIT_SUCCESS;
}

