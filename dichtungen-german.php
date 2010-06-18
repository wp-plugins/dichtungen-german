<?php
/*
Plugin Name: Dichtungen (german)
Plugin URI: http://wordpress.org/extend/plugins/dichtungen-german/
Description: Das Dichtungen-Plugin stellt eine Erweiterung bereit, welche verschiedene Informationen zu Dichtungen im Blog einblenden l&auml;sst.
Version: 1.0
Author: Manfred Kuhlemann
Author URI: http://www.dichtungshersteller.info/
License: GPL3
*/

function dichtungenfeed()
{
  $options = get_option("widget_dichtungenfeed");
  if (!is_array($options)){
    $options = array(
      'title' => 'Dichtungen Feed',
      'news' => '5',
      'chars' => '30'
    );
  }

  // RSS Objekt erzeugen 
  $rss = simplexml_load_file( 
  'http://www.dichtungshersteller.info/news.feed?type=rss'); 
  ?> 
  
  <ul> 
  
  <?php 
  // maximale Anzahl an News, wobei 0 (Null) alle anzeigt
  $max_news = $options['news'];
  // maximale Länge, auf die ein Titel, falls notwendig, gekürzt wird
  $max_length = $options['chars'];
  
  // RSS Elemente durchlaufen 
  $cnt = 0;
  foreach($rss->channel->item as $i) { 
    if($max_news > 0 AND $cnt >= $max_news){
        break;
    }
    ?> 
    
    <li>
    <?php
    // Titel in Zwischenvariable speichern
    $title = $i->title;
    // Länge des Titels ermitteln
    $length = strlen($title);
    // wenn der Titel länger als die vorher definierte Maximallänge ist,
    // wird er gekürzt und mit "..." bereichert, sonst wird er normal ausgegeben
    if($length > $max_length){
      $title = substr($title, 0, $max_length)."...";
    }
    ?>
    <a href="<?=$i->link?>"><?=$title?></a> 
    </li> 
    
    <?php 
    $cnt++;
  } 
  ?> 
  
  </ul>
<?php  
}

function widget_dichtungenfeed($args)
{
  extract($args);
  
  $options = get_option("widget_dichtungenfeed");
  if (!is_array($options)){
    $options = array(
      'title' => 'Dichtungen Feed',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  echo $before_widget;
  echo $before_title;
  echo $options['title'];
  echo $after_title;
  dichtungenfeed();
  echo $after_widget;
}

function dichtungenfeed_control()
{
  $options = get_option("widget_dichtungenfeed");
  if (!is_array($options)){
    $options = array(
      'title' => 'Dichtungen Feed',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  if($_POST['dichtungenfeed-Submit'])
  {
    $options['title'] = htmlspecialchars($_POST['dichtungenfeed-WidgetTitle']);
    $options['news'] = htmlspecialchars($_POST['dichtungenfeed-NewsCount']);
    $options['chars'] = htmlspecialchars($_POST['dichtungenfeed-CharCount']);
    update_option("widget_dichtungenfeed", $options);
  }
?> 
  <p>
    <label for="dichtungenfeed-WidgetTitle">Widget Title: </label>
    <input type="text" id="dichtungenfeed-WidgetTitle" name="dichtungenfeed-WidgetTitle" value="<?php echo $options['title'];?>" />
    <br /><br />
    <label for="dichtungenfeed-NewsCount">Max. News: </label>
    <input type="text" id="dichtungenfeed-NewsCount" name="dichtungenfeed-NewsCount" value="<?php echo $options['news'];?>" />
    <br /><br />
    <label for="dichtungenfeed-CharCount">Max. Characters: </label>
    <input type="text" id="dichtungenfeed-CharCount" name="dichtungenfeed-CharCount" value="<?php echo $options['chars'];?>" />
    <br /><br />
    <input type="hidden" id="dichtungenfeed-Submit"  name="dichtungenfeed-Submit" value="1" />
  </p>
  
<?php
}

function dichtungenfeed_init()
{
  register_sidebar_widget(__('Dichtungen Feed'), 'widget_dichtungenfeed');    
  register_widget_control('Dichtungen Feed', 'dichtungenfeed_control', 300, 200);
}
add_action("plugins_loaded", "dichtungenfeed_init");
?>