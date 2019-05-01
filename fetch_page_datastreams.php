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

  $book_directory = $fileInfo->getFilename();
  $book_directory_path = $PWD . '/' . $book_directory;

  // see fetch_page_pids.php
  $page_pids_file = $book_directory_path . '/page_pids';

  // fetch RELS-EXT datastreams; this will save one file for every page PID in
  // the book directory; the RELS-EXT files are only needed to get page numbers
  // for creating page directories and should be removed after use
  $fetch_page_rels_ext = "drush idcrudfd --root=/var/www/html/drupal7 --user=1 --pid_file=$page_pids_file --dsid=RELS-EXT --datastreams_directory=$book_directory_path -y";
  echo "⬇️  fetching RELS-EXT datastreams for pages in {$book_directory_path}... \n";
  exec($fetch_page_rels_ext);

  // fetch OBJ datastream
  $fetch_page_obj = "drush idcrudfd --root=/var/www/html/drupal7 --user=1 --pid_file=$page_pids_file --dsid=OBJ --datastreams_directory=$book_directory_path -y";
  echo "⬇️  fetching OBJ datastreams for pages in {$book_directory_path}... \n";
  exec($fetch_page_obj);

  // fetch JP2 datastream
  $fetch_page_jp2 = "drush idcrudfd --root=/var/www/html/drupal7 --user=1 --pid_file=$page_pids_file --dsid=JP2 --datastreams_directory=$book_directory_path -y";
  echo "⬇️  fetching JP2 datastreams for pages in {$book_directory_path}... \n";
  exec($fetch_page_jp2);

  // fetch JPG datastream
  $fetch_page_jpg = "drush idcrudfd --root=/var/www/html/drupal7 --user=1 --pid_file=$page_pids_file --dsid=JPG --datastreams_directory=$book_directory_path -y";
  echo "⬇️  fetching JPG datastreams for pages in {$book_directory_path}... \n";
  exec($fetch_page_jpg);

  // fetch TN datastream
  $fetch_page_tn = "drush idcrudfd --root=/var/www/html/drupal7 --user=1 --pid_file=$page_pids_file --dsid=TN --datastreams_directory=$book_directory_path -y";
  echo "⬇️  fetching TN datastreams for pages in {$book_directory_path}... \n";
  exec($fetch_page_tn);

  // fetch TECHMD datastream
  $fetch_page_techmd = "drush idcrudfd --root=/var/www/html/drupal7 --user=1 --pid_file=$page_pids_file --dsid=TECHMD --datastreams_directory=$book_directory_path -y";
  echo "⬇️  fetching TECHMD datastreams for pages in {$book_directory_path}... \n";
  exec($fetch_page_techmd);

}
