#!/usr/bin/env bash
#
# anax/view
#

# Create dir for own views
install -d view

# Copy default config
rsync -a vendor/anax/view/config/ config/
