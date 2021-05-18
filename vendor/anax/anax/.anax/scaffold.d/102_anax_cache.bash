#!/usr/bin/env bash
#
# anax/cache
#
# Setup cache/ and related.
#
gitignore = "
# Ignore everything in this directory
*
# Except this file
!.gitignore
"

# Create the cache directory.
install -d cache

# Get configuration for the cache.
rsync -a vendor/anax/cache/config ./
