#!/bin/bash

# format MODS; add new XML declaration, root element, and namespace attributes

# NOTE: var=$'' syntax allows newline characters
xml=$'<?xml version="1.0" encoding="UTF-8"?>\n<mods xmlns="http://www.loc.gov/mods/v3" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.loc.gov/mods/v3 http://www.loc.gov/standards/mods/v3/mods-3-7.xsd">\n'

for file in *; do
  [ -f "${file}" ] || continue # if not a file, skip
  # remove all empty lines
  sed --in-place '/^$/d' "$file"
  # remove any '<?xml' lines
  sed --in-place '/<?xml/d' "$file"
  # remove any '<mods' lines
  sed --in-place '/<mods/d' "$file"
  # add new XML declaration, root element, and namespace attributes
  echo "${xml}$(cat "$file")" > "$file"
  # add newline to end of file
  echo '' >> "$file"
  echo "🤖 updated $file ..."
  cat "$file"
done
