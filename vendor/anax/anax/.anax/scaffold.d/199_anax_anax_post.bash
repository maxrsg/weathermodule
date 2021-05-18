#!/usr/bin/env bash
#
# anax/anax
#

# Remove unused routes
rm -f config/router/000_application.php

# Use a custom config/page.php
cp config/page.php config/page_default.php
cp config/page_anax.php config/page.php

# # Add default stylesheets
# rsync -a vendor/anax/anax/htdocs/css htdocs/

# # There are some stylesheets in htdocs/css
# # these are built from theme repo
# # and will be replace by the below actions
# [[ -d theme ]] \
#     || git clone https://github.com/desinax/theme-dbwebb.se theme
# (cd theme && make install)
# make theme
