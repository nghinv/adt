<!DOCTYPE html>
<?php
require_once(dirname(__FILE__) . '/lib/functions.php');
require_once(dirname(__FILE__) . '/lib/functions-ui.php');
checkCaches();
?>
<html>
<head>
    <?= pageHeader(); ?>
</head>
<body>
<?php pageTracker(); ?>
<?php pageNavigation(); ?>
<!-- Main ================================================== -->
<div id="wrap">
    <div id="main">
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span12">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th class="col-center" colspan="4">Product</th>
                            <th class="col-center" colspan="3">Deployment</th>
                            <th class="col-center" colspan="6">Ports</th>
                        </tr>
                        <tr>
                            <th class="col-center">Name</th>
                            <th class="col-center">Version</th>
                            <th class="col-center">Feature Branch</th>
                            <th class="col-center">Bundle</th>
                            <th class="col-center">Database</th>
                            <th class="col-center">Server</th>
                            <th class="col-center">S</th>
                            <th class="col-center">Prefix</th>
                            <th class="col-center">HTTP</th>
                            <th class="col-center">ES</th>
                            <th class="col-center">AJP</th>
                            <th class="col-center"><span rel="tooltip" title="JMX RMI Registration port / JMX RMI Server port">JMX RMI</span></th>
                            <th class="col-center"><span rel="tooltip" title="CRaSH ssh port">CRaSH</span></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $merged_list = getGlobalAcceptanceInstances();
                        $descriptor_arrays = array();
                        foreach ($merged_list as $tmp_array) {
                            $descriptor_arrays = array_merge($descriptor_arrays, $tmp_array);
                        }
                        function cmp($a, $b)
                        {
                            return strcmp($a->DEPLOYMENT_HTTP_PORT, $b->DEPLOYMENT_HTTP_PORT);
                        }
                        usort($descriptor_arrays, "cmp");

                        $servers_counter = array();
                        foreach ($descriptor_arrays as $descriptor_array) {
                            // Compute the number of deployed instances per acceptance server
                            $servers_counter[$descriptor_array->ACCEPTANCE_HOST]['nb']=$servers_counter[$descriptor_array->ACCEPTANCE_HOST]['nb']+1;
                            // Compute the minimum amount of JVM size allocated per acceptance server
                            if (strpos($descriptor_array->DEPLOYMENT_JVM_SIZE_MIN,'g')) {
                              $servers_counter[$descriptor_array->ACCEPTANCE_HOST]['jvm-min']=$servers_counter[$descriptor_array->ACCEPTANCE_HOST]['jvm-min']+str_replace('g','',$descriptor_array->DEPLOYMENT_JVM_SIZE_MIN);
                            } else if (strpos($descriptor_array->DEPLOYMENT_JVM_SIZE_MIN,'m')) {
                              $servers_counter[$descriptor_array->ACCEPTANCE_HOST]['jvm-min']=$servers_counter[$descriptor_array->ACCEPTANCE_HOST]['jvm-min']+(str_replace('m','',$descriptor_array->DEPLOYMENT_JVM_SIZE_MIN)/1000);
                            } else {
                              throw new Exception("The unit of the DEPLOYMENT_JVM_SIZE_MIN is not manage (".$descriptor_array->DEPLOYMENT_JVM_SIZE_MIN.")");
                            }

                            // Compute the maximum amount of JVM size allocated per acceptance server
                            if (strpos($descriptor_array->DEPLOYMENT_JVM_SIZE_MAX,'g')) {
                              $servers_counter[$descriptor_array->ACCEPTANCE_HOST]['jvm-max']=$servers_counter[$descriptor_array->ACCEPTANCE_HOST]['jvm-max']+str_replace('g','',$descriptor_array->DEPLOYMENT_JVM_SIZE_MAX);
                            } else if (strpos($descriptor_array->DEPLOYMENT_JVM_SIZE_MAX,'m')) {
                              $servers_counter[$descriptor_array->ACCEPTANCE_HOST]['jvm-max']=$servers_counter[$descriptor_array->ACCEPTANCE_HOST]['jvm-max']+(str_replace('m','',$descriptor_array->DEPLOYMENT_JVM_SIZE_MAX)/1000);
                            } else {
                              throw new Exception("The unit of the DEPLOYMENT_JVM_SIZE_MAX is not manage (".$descriptor_array->DEPLOYMENT_JVM_SIZE_MAX.")");
                            }

                            $matches = array();
                            if (preg_match("/([^\-]*)\-(.*\-.*)\-SNAPSHOT/", $descriptor_array->PRODUCT_VERSION, $matches)) {
                                $base_version = $matches[1];
                                $feature_branch = $matches[2];
                            } elseif (preg_match("/(.*)\-SNAPSHOT/", $descriptor_array->PRODUCT_VERSION, $matches)) {
                                $base_version = $matches[1];
                                $feature_branch = "";
                            } else {
                                $base_version = $descriptor_array->PRODUCT_VERSION;
                                $feature_branch = "";
                            }
                            ?>
                            <tr>
                                <td>
                                  <?= componentAppServerIcon($descriptor_array); ?>
                                  <?= componentProductHtmlLabel($descriptor_array); ?>
                                  <br/><?= componentLabels($descriptor_array); ?>
                                </td>
                                <td class="col-left"><?= componentProductVersion($descriptor_array); ?></td>
                                <td class="col-right"><?=$feature_branch?></td>
                                <td class="col-right"><?=$descriptor_array->DEPLOYMENT_APPSRV_TYPE?></td>
                                <td class="col-right"><?=$descriptor_array->DATABASE?></td>
                                <?php
                                if ($descriptor_array->ACCEPTANCE_HOST === "acceptance5.exoplatform.org") {
                                    $host_html_color = "color-acceptance5";
                                } else if ($descriptor_array->ACCEPTANCE_HOST === "acceptance7.exoplatform.org") {
                                    $host_html_color = "color-acceptance7";
                                } else if ($descriptor_array->ACCEPTANCE_HOST === "acceptance8.exoplatform.org") {
                                    $host_html_color = "color-acceptance8";
                                } else {
                                    $host_html_color = "color-acceptanceX";
                                }
                                ?>
                                <td style="font-weight:bold;" class='col-right <?=$host_html_color?>'><?=$descriptor_array->ACCEPTANCE_HOST?></td>
                                <td class="col-center"><?= componentStatusIcon($descriptor_array); ?></td>
                                <td class="col-center"><?=$descriptor_array->DEPLOYMENT_PORT_PREFIX?>xx</td>
                                <td class="col-center"><?=$descriptor_array->DEPLOYMENT_HTTP_PORT?></td>
                                <td class="col-center"><?=$descriptor_array->DEPLOYMENT_ES_HTTP_PORT?></td>
                                <td class="col-center"><?=$descriptor_array->DEPLOYMENT_AJP_PORT?></td>
                                <td class="col-center"><?=$descriptor_array->DEPLOYMENT_RMI_REG_PORT?> / <?=$descriptor_array->DEPLOYMENT_RMI_SRV_PORT?></td>
                                <td class="col-center"><?=$descriptor_array->DEPLOYMENT_CRASH_SSH_PORT?></td>
                            </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="container-fluid">
          <div class="row-fluid">
            <div class="span12">
              <table class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                    <th class="col-center">hostname</th>
                    <th class="col-center">server name</th>
                    <th class="col-center">deployment<br />count</th>
                    <th class="col-center">JVM size<br />allocated</th>
                    <th class="col-center">characteristics</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="col-center">acceptance5.exoplatform.org</td>
                    <td class="col-center">prj03</td>
                    <td class="col-center"><?=$servers_counter["acceptance5.exoplatform.org"]['nb']?></td>
                    <td class="col-center"><?=$servers_counter["acceptance5.exoplatform.org"]['jvm-min']?>GB &lt; ... &lt; <?=$servers_counter["acceptance5.exoplatform.org"]['jvm-max']?>GB</td>
                    <td>RAM = 128GB <br /> CPU = Xeon W3530 2.80GHz (6 cores + hyperthreading = 12 threads) <br /> Disks = 2 x 2TB (sda = HGST HUS724020ALA640 / sdb = HGST HUS724020ALA640)</td>
                  </tr>
                  <tr> 
                    <td class="col-center">acceptance7.exoplatform.org</td> 
                    <td class="col-center">prd05</td> 
                    <td class="col-center"><?=$servers_counter["acceptance7.exoplatform.org"]['nb']?></td> 
                    <td class="col-center"><?=$servers_counter["acceptance7.exoplatform.org"]['jvm-min']?>GB &lt; ... &lt; <?=$servers_counter["acceptance7.exoplatform.org"]['jvm-max']?>GB</td>
                    <td>RAM = 128GB <br /> CPU = Xeon E5-1650 v2 @ 3.50GHz (6 cores + hyperthreading = 12 threads) <br /> Disks = 3 x 300GB SSD (sda = INTEL SSDSC2BB300H4 / sdb = INTEL SSDSC2BB300H4 / sdc = INTEL SSDSC2BB300H4)</td> 
                  </tr>                 </tbody>
                  <tr>
                    <td class="col-center">acceptance8.exoplatform.org</td>
                    <td class="col-center">prd06</td>
                    <td class="col-center"><?=$servers_counter["acceptance8.exoplatform.org"]['nb']?></td>
                    <td class="col-center"><?=$servers_counter["acceptance8.exoplatform.org"]['jvm-min']?>GB &lt; ... &lt; <?=$servers_counter["acceptance8.exoplatform.org"]['jvm-max']?>GB</td>
                    <td>RAM = 128GB <br /> CPU = Xeon E5-1650 v2 @ 3.50GHz (6 cores + hyperthreading = 12 threads) <br /> Disks = 3 x 300GB SSD (sda = INTEL SSDSC2BB30 / sdb = INTEL SSDSC2BB30 / sdc = INTEL SSDSC2BB30)</td>
                  </tr>                 </tbody>
              </table>
            </div>
          </div>
        </div>
        <!-- /container -->
      </div>
</div>
<?php pageFooter(); ?>
</body>
</html>
