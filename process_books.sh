#!/usr/bin/env bash

##
# ASSUMPTIONS
#
# 1. Islandora Book MODS files have been fetched.
# 2. This script is being run from the location of the MODS files.
# 3. The NAS is mounted.
#
# Example Prerequisites:
#
# drush idcrudfp \
#   --user=1 \
#   --root=/var/www/html/drupal7 \
#   --pid_file=/tmp/MacCreadyPB_07_02-bookCModel-PIDs.txt \
#   --solr_query="PID:pbm\:* AND RELS_EXT_hasModel_uri_s:info\:fedora\/islandora\:bookCModel AND mods_relatedItem_host_note_s:Part\ of\ Series\ 7\:\ Audio-Visual\ material\;\ Subseries\ 2*"
#
# drush idcrudfd \
#   --user=1 \
#   --root=/var/www/html/drupal7 \
#   --pid_file=/tmp/MacCreadyPB_07_02-bookCModel-PIDs.txt \
#   --dsid=MODS \
#   --datastreams_directory=/tmp/MacCreadyPB_07_02-books \
#   --yes
#
# mount -t cifs //131.215.225.60/Archives/Workspace -o username=tkeswick,domain=LIBRARY,users,sec=ntlmssp /mnt/Workspace
##

bash ./format_book_mods.sh

php ./edit_book_mods.php

php ./validate_mods.php

bash ./create_book_directories.sh

php ./fetch_page_pids.php

php ./fetch_page_datastreams.php

php ./create_page_mods.php

php ./move_page_datastreams.php

php ./create_obj_jp2.php

php ./move_preservation_files.php

# Next steps:
# - transfer datastreams to new server
# - ingest objects into new instance
