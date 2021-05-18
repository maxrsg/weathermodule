#!/usr/bin/env bash
#
# Setup a base theme for anax in the 'theme/' directory
#

# There are some stylesheets in htdocs/css
# these are built from theme repo
# and will be replaced by the below actions
git clone https://github.com/desinax/theme-dbwebb.se theme
(cd theme && make install)
rm -rf theme/.git
make theme
