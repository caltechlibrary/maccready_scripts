<?php

// ASSUMPTIONS
// 1. Book PIDs were fetched
// 2. Book MODS datastreams were fetched
// 3. Book MODS datastreams were moved into individual directories by PID
// 4. Page PIDs were fetched
//
// Our structure should look like this:
//
// /path/to/books
// ├── book_12345
// │   ├── MODS.xml
// │   └── page_pids
// ├── book_12346
// │   ├── MODS.xml
// │   └── page_pids
// ├── book_12347
// │   ├── MODS.xml
// │   └── page_pids
// │   ...

$PWD = getcwd();

echo "⚠️  The current working directory is: \e[1;91m$PWD\e[0m\n";
echo "Be sure it contains the book directories. Ctrl-c to quit.\n";
echo "...5"; sleep(1); echo "...4"; sleep(1); echo "...3"; sleep(1); echo "...2"; sleep(1); echo "...1"; sleep(1); echo "\n";

// loop over every individual book directory inside our working directory
// see: http://php.net/manual/en/class.directoryiterator.php
$dirItem = new DirectoryIterator($PWD);

foreach ($dirItem as $fileInfo) {

  if (!$fileInfo->isDir() || $fileInfo->isDot()) {
    continue;
  }

  $book_directory = $fileInfo->getFilename();
  $book_directory_path = $PWD . '/' . $book_directory;

  // see fetch_page_pids.php
  $page_pids_file = $book_directory_path . '/page_pids';

  // fetch TECHMD datastream
  $fetch_page_techmd = "drush idcrudfd --root=/var/www/html/drupal7 --user=1 --pid_file=$page_pids_file --dsid=TECHMD --datastreams_directory=$book_directory_path -y";
  echo "⬇️  fetching TECHMD datastreams for pages in {$book_directory_path}... \n";
  exec($fetch_page_techmd);

}
