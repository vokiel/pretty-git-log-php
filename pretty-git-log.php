<!DOCTYPE html>
<head>
  <meta charset="utf-8">
  <title>Pretty Git log</title>
  <style>
    body { font-family: Courier, monospace; font-size: 0.9em; }
    a, a:hover, a:active, a:visited { text-decoration: none; }
    a:hover { text-decoration: underline; }
    ul { list-style: none }
    li { overflow: hidden; height: 1.1em; }
    .rev { color: darkred; }
    .date { color: green; }
    .author, .author a { color: darkviolet; }
    .tags { color: goldenrod; }
  </style>
</head>
<body>
  <ul>
  <?php
  if ( !empty($_GET['debug']) ){
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
  } else {
    error_reporting(0);
    ini_set('display_errors', 0);
  }
  ini_set('memory_limit','256M');

  $limit = '--max-count=50';
  if ( !empty($_GET['limit']) ){
    if ( strtolower($_GET['limit']) == 'all' ){
      $limit = '--max';
    }
    else {
      $limit = '--max-count='.intval($_GET['limit']);
    }
  }
  $msgs = array();
  exec( "/usr/bin/env git log --pretty=tformat:%s $limit", $msgs );
  $lines = array();
  exec( "/usr/bin/env git log --pretty=tformat:'</span><a href=\"#%h\">%h</a> - <span class=\"date\">[%cr]</span> <span class=\"tags\">%d</span> __COMMENT__ <span class=\"author\">&lt;<a href=\"mailto:%ae\">%an</a>&gt;</span></li>' --graph --abbrev-commit $limit", $lines );

  if ( !empty($msgs) && !empty($lines)){
    echo '<ul>';
    for ($i=0; $i<count($lines); $i++ ){
      $msg = htmlentities($msgs[$i],ENT_QUOTES);
      $message = str_replace('__COMMENT__',$msg,$lines[$i]);
      echo '<li><span class="graph">'.$message."\n";
    }
    echo '</ul>';
  }
  ?>
  </body>
</html>