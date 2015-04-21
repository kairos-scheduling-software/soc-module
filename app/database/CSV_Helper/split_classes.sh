#/bin/bash

# Nate Crandall
# 2015/04/20
#
# Splits single CSV file into multiple CSV files based on value in first column
#
# This assumes that your CSV file uses quoted fields and you have GNU sed and
# cut installed in your path
#
for i in `cut -d, -f1 classes_extract.csv|sort -u`; do
    # Length of file_name string
    _FILE_NAME_LENGTH=$(expr ${#i} + 2);

    # remove quotes from name
    _FILE=$(echo $i | tr -d '"');

    # Overwrite file, add header
    echo '"Room","Capacity","UUID","Professor","Class","Title","Type","Time","Length"' > ${_FILE}.csv;

    # Add lines to file
    sed -n "/^$i/p" classes_extract.csv | cut -c${_FILE_NAME_LENGTH}- >> ${_FILE}.csv;
done

# Clean up, remove file created by file header
rm -f "FILE_NAME.csv";

exit 0;
