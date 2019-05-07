<?php

// ASSUMPTIONS
// 1. Book PIDs were fetched
// 2. Book MODS datastreams were fetched
// 3. Book MODS datastreams were moved into individual directories by PID
//
// Our structure should look like this:
//
// /path/to/books
// ├── book_12345
// │   └── MODS.xml
// ├── book_12346
// │   └── MODS.xml
// ├── book_12347
// │   └── MODS.xml
// │   ...

$PWD = getcwd();

echo "⚠️ The current working directory is: \e[1;91m$PWD\e[0m\n";
echo "Be sure it contains the book directories. Ctrl-c to quit.\n";
echo "...5"; sleep(1); echo "...4"; sleep(1); echo "...3"; sleep(1); echo "...2"; sleep(1); echo "...1"; sleep(1); echo "\n";

// loop over every individual book directory inside our working directory
// see: http://php.net/manual/en/class.directoryiterator.php
$dirItem = new DirectoryIterator($PWD);

foreach ($dirItem as $fileInfo) {

  if (!$fileInfo->isDir() || $fileInfo->isDot()) {
    continue;
  }

  // echo "filename: " . $fileInfo->getFilename() . "\n";

  $book_directory = $fileInfo->getFilename();
  $book_directory_path = $PWD . '/' . $book_directory;
  $book_pid = str_replace('_', ':', $book_directory);
  // echo "book pid: " . $book_pid . "\n";

  // fetch page PIDs
  echo "⬇️  saving page PIDs for book $book_pid \n";
  $page_pids_file = $book_directory_path . '/page_pids';
  $fetch_page_pids = "drush idcrudfp --root=/var/www/html/drupal7 --user=1 --pid_file=$page_pids_file --is_member_of=$book_pid";
  exec($fetch_page_pids);

}
