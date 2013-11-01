#!/bin/bash

for XMLFILE in `find ./test -name "*.xml"`; do
    php ./sfdc.php $XMLFILE > /dev/null

    if [ $? != 0 ]; then
        echo ""
        echo "Test for "$XMLFILE" Failed"
        exit 1
    fi

done

echo "OK"
exit 0
