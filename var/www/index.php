<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title>Acceptance Live Instances</title>
<link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico" />
<link href="/style.css" media="screen" rel="stylesheet" type="text/css"/>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-1292368-28']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>
<body>
<div class="UIForgePages">
  <div class="Header ClearFix"> <a href="/" class="Logo"></a><span class="AddressWeb"><?=$_SERVER['SERVER_NAME'] ?></span> </div>
  <div class="MainContent">
    <div class="TitleForgePages">Acceptance Live Instances</div>
    <div>
      <div>
        <p>&nbsp;</p>
        <p>Welcome on Acceptance Live Instances !</p>
        <p>These instances are deployed to be used for acceptance tests.</p>
        <p> Terms of usage and others documentations about this service are detailed in our <a href="https://wiki-int.exoplatform.org/x/loONAg">internal wiki</a>.</p>
        <p><br/>
        </p>
        <table class="center">
          <thead>
            <tr>
              <th colspan="2">Product</th>
              <th colspan="8">Current deployment</th>
            </tr>
            <tr>
              <th>Name</th>
              <th>Version</th>
              <th>Artifact</th>
              <th>Built</th>
              <th>Deployed</th>
              <th>URL</th>
              <th>Logs</th>
              <th>JMX</th>
              <th>Stats</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php
          function getDirectoryList ($directory) {
            // create an array to hold directory list
            $results = array();
            // create a handler for the directory
            $handler = opendir($directory);
            // open directory and walk through the filenames
            while ($file = readdir($handler)) {
              // if file isn't this directory or its parent, add it to the results
              if ($file != "." && $file != "..") {
                $results[] = $file;
              }
            }
            // tidy up: close the handler
            closedir($handler);
            // done!
            return $results;
          }
          function processIsRunning ($pid) {
            // create an array to hold the result
            $output = array();
            // execute a ps for the given pid
            exec("ps -p ".$pid, $output);
            // The process is running if there is a row N#1 (N#0 is the header)
            return isset($output[1]);
          }
          //print each file name
          $vhosts = getDirectoryList($_SERVER['ADT_DATA']."/conf/adt/");
          sort($vhosts);
          $now = new DateTime();
          foreach( $vhosts as $vhost) {
            // Parse deployment descriptor
            $descriptor_array = parse_ini_file($_SERVER['ADT_DATA']."/conf/adt/".$vhost);
            if($descriptor_array['ARTIFACT_DATE']){
              $artifact_age = DateTime::createFromFormat('Ymd.His',$descriptor_array['ARTIFACT_DATE'])->diff($now,true);
              if($artifact_age->days)
                $artifact_age_string = $artifact_age->format('%a day(s) ago');
              else if($artifact_age->h > 0)
                $artifact_age_string = $artifact_age->format('%h hour(s) ago');
              else
                $artifact_age_string = $artifact_age->format('%i minute(s) ago');
              if($artifact_age->days > 5 )
                $artifact_age_class = "red";
              else if($artifact_age->days > 2 )
                $artifact_age_class = "orange";
              else
                $artifact_age_class = "green";      
            } else {
              $artifact_age_string = "Unknown";
              $artifact_age_class = "black";
            }
            $deployment_age = DateTime::createFromFormat('Ymd.His',$descriptor_array['DEPLOYMENT_DATE'])->diff($now,true);
            if($deployment_age->days)
              $deployment_age_string = $deployment_age->format('%a day(s) ago');
            else if($deployment_age->h > 0)
              $deployment_age_string = $deployment_age->format('%h hour(s) ago');
            else
              $deployment_age_string = $deployment_age->format('%i minute(s) ago');
            ?>
            <tr onmouseover="this.className='normalActive'" onmouseout="this.className='normal'" class="normal">
              <td><?=strtoupper($descriptor_array['PRODUCT_NAME'])?></td>
              <td><?=$descriptor_array['PRODUCT_VERSION']?></td>
              <td><a href="<?=$descriptor_array['ARTIFACT_DL_URL']?>" class="TxtBlue" title="Download <?=$descriptor_array['ARTIFACT_GROUPID']?>:<?=$descriptor_array['ARTIFACT_ARTIFACTID']?>:<?=$descriptor_array['ARTIFACT_TIMESTAMP']?> from Nexus"><img class="left" src="/images/ButDownload.gif" alt="Download" width="19" height="19" />&nbsp;<?=$descriptor_array['ARTIFACT_TIMESTAMP']?></a></td>
              <td class="<?=$artifact_age_class?>"><?=$artifact_age_string?></td>
              <td><?php if( $descriptor_array['DEPLOYMENT_ENABLED'] ) { echo "$deployment_age_string"; } ?></td>
              <td><?php if( $descriptor_array['DEPLOYMENT_ENABLED'] ) { ?><a href="<?=$descriptor_array['DEPLOYMENT_URL']?>" class="TxtBlue" target="_blank" title="Open the instance in a new window"><?=$descriptor_array['DEPLOYMENT_URL']?></a><?php } ?></td>
              <td><?php if( $descriptor_array['DEPLOYMENT_ENABLED'] ) { ?><a href="/logs.php?file=<?=$descriptor_array['DEPLOYMENT_LOG_PATH']?>" class="TxtOrange" title="Instance logs" target="_blank"><img src="/images/terminal_tomcat.png" width="32" height="16" alt="instance logs"  class="left" /></a><a href="/logs.php?file=<?=$_SERVER['ADT_DATA']?>/var/log/apache2/<?=$descriptor_array['PRODUCT_NAME']."-".$descriptor_array['PRODUCT_VERSION'].".".$_SERVER['SERVER_NAME']?>-access.log" class="TxtOrange" title="apache logs" target="_blank"><img src="/images/terminal_apache.png" width="32" height="16" alt="apache logs"  class="right" /></a><?php } ?></td>
              <td><?php if( $descriptor_array['DEPLOYMENT_ENABLED'] ) { ?><a href="<?=$descriptor_array['DEPLOYMENT_JMX_URL']?>" class="TxtOrange" title="jmx monitoring" target="_blank"><img src="/images/action_log.png" alt="JMX url" width="16" height="16" class="center" /></a><?php } ?></td>
              <td><?php if( $descriptor_array['DEPLOYMENT_ENABLED'] ) { ?><a href="/stats/awstats.pl?config=<?=$descriptor_array['PRODUCT_NAME']."-".$descriptor_array['PRODUCT_VERSION'].".".$_SERVER['SERVER_NAME']?>" class="TxtOrange" title="<?=$descriptor_array['DEPLOYMENT_URL']?> usage statistics" target="_blank"><img src="/images/server_chart.png" alt="<?=$descriptor_array['DEPLOYMENT_URL']?> usage statistics" width="16" height="16" class="center" /></a><?php } ?></td>
              <?php
            if (file_exists ($descriptor_array['DEPLOYMENT_PID_FILE']) && processIsRunning(file_get_contents ($descriptor_array['DEPLOYMENT_PID_FILE'])))
              $status="<img width=\"16\" height=\"16\" src=\"/images/green_ball.png\" alt=\"Up\"  class=\"left\"/>&nbsp;Up";
            else
              $status="<img width=\"16\" height=\"16\" src=\"/images/red_ball.png\" alt=\"Down\"  class=\"left\"/>&nbsp;Down !";
            ?>
              <td><?php if( $descriptor_array['DEPLOYMENT_ENABLED'] ) { echo "$status"; } ?></td>
            </tr>
            <?php 
          } 
          ?>
          </tbody>
        </table>
        <p>&nbsp;</p>
        <p>Each instance can be accessed using JMX with the  URL linked to the monitoring icon and these credentials : <span class="TxtBoldContact">acceptanceMonitor</span> / <span class="TxtBoldContact">monitorAcceptance!</span></p>
        <p><a href="/stats/awstats.pl?config=<?=$_SERVER['SERVER_NAME'] ?>" class="TxtBlue" title="http://<?=$_SERVER['SERVER_NAME'] ?> usage statistics" target="_blank"><img src="/images/server_chart.png" alt="Statistics" width="16" height="16" class="left" />http://<?=$_SERVER['SERVER_NAME'] ?> usage statistics</a></p>
      </div>
    </div>
  </div>
  <div class="Footer">eXo Platform SAS</div>
</div>
</body>
</html>
