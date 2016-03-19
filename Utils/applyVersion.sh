#!/bin/bash

cd ./Source/twitter/

# Create Info.php with plugin version constant

cat > Info.php << EOF
<?php
namespace Craft;

define('TWITTER_VERSION', '${PLUGIN_VERSION}');

EOF
