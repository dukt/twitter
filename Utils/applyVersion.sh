#!/bin/bash

for VERSION in "$@"

do

# Create Info.php with plugin version constant

cat > Source/twitter/Info.php << EOF
<?php

namespace Craft;

define('TWITTER_VERSION', '$VERSION');

EOF

done
