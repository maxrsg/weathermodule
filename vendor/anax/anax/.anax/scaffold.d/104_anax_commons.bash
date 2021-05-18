#!/usr/bin/env bash
#
# anax/commons
#
git_ignore_files="\
# Ignore everything in this directory
*
# Except this file
!.gitignore
"

# Get a Makefile adapted for a site
rsync -a vendor/anax/commons/Makefile_site Makefile

# Install general development files from anax/commons.
#rsync -a vendor/anax/commons/{.gitignore,.circleci,.php*.xml} ./
#mv .circleci/config_default.yml .circleci/config.yml

#rsync -a vendor/anax/commons/.travis_default.yml .travis.yml
#rsync -a vendor/anax/commons/.codeclimate.yml ./
#rsync -a vendor/anax/commons/test/Example ./test/
#cp vendor/anax/commons/test/config_sample.php ./test/config.php

# Enable to run site in docker
rsync -a vendor/anax/commons/docker-compose_site.yml docker-compose.yml

# Get essentials to be able to run apache in docker
rsync -a vendor/anax/commons/config/apache config/
rsync -a vendor/anax/commons/config/docker config/

# Add log directory for apache (docker) logs
install --mode=0777 -d log/apache
echo "$git_ignore_files" > log/apache/.gitignore

# Get configuration for commons.
rsync -a vendor/anax/commons/config/commons.php config/

# Create directory structure for htdocs
install -d htdocs/img
rsync -a vendor/anax/commons/htdocs/ htdocs/
