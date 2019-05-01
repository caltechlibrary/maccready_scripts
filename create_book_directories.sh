#!/bin/bash

# Use the current directory and then loop through every file in it to create a
# new directory with the prefix of the file. Then move every file with the same
# prefix into the new directory and strip off the prefix.
#
# Example of result: abc_12345_MODS.xml to abc_12345/MODS.xml

printf "Be sure the current working directory contains the datastream files. Ctrl-c to quit.\n"
printf "...5"; sleep 1; printf "...4"; sleep 1; printf "...3"; sleep 1; printf "...2"; sleep 1; printf "...1"; sleep 1; printf "\n"

for file in *; do
  [[ -f "${file}" ]] || continue # if not a file, skip
  namespace=${file%%_*} # strip anything after and including the first underscore from the left
  interstitial=${file#"$namespace"_} # strip the namespace and following underscore
  increment=${interstitial%%_*} # strip anything after and including the first underscore from the left
  pid="${namespace}_${increment}" # concatenate the namespace and increment
  datastream=${file#"$pid"_} # strip the pid and the following underscore
  mkdir -p "$pid" # make whole directory path if it does not exist
  mv "$file" "$pid"/"$datastream" # move and rename file
  echo "🤖 moved $file to $pid as $datastream"
done
