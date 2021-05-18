#!/usr/bin/env bash
#
# anax/content
#
# Setup content/ and related.
#

# Git file to ignore all cache files.
git_ignore_files="\
# Ignore everything in this directory
*
# Except this file
!.gitignore
"

# Do the setup
install --mode=0777 -d cache/anax
echo "$git_ignore_files" > cache/anax/.gitignore

# Get configuration for the flat file content.
rsync -a --exclude router vendor/anax/content/config ./
