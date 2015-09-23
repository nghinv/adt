<!DOCTYPE html>
<?php
require_once(dirname(__FILE__) . '/lib/functions.php');
checkCaches();
?>
<html>
<head>
    <?= pageHeader("links"); ?>
</head>
<body>
<!-- navbar ================================================== -->
<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container-fluid">
            <a class="brand" href="/"><?=$_SERVER['SERVER_NAME'] ?></a>
            <ul class="nav">
                <li><a href="/">Home</a></li>
                <li><a href="/features.php">Features</a></li>
                <li class="active"><a href="/links.php">Links</a></li>
                <li><a href="/servers.php">Servers</a></li>
            </ul>
        </div>
    </div>
</div>
<!-- /navbar -->
<!-- Main ================================================== -->
<div id="wrap">
    <div id="main">
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span12">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th class="col-center" colspan="1">SITE</th>
                            <th class="col-center" colspan="2">PRE-PROD</th>
                            <th class="col-center" colspan="2">PRODUCTION</th>
                        </tr>
                        <tr>
                            <th class="col-center">Name</th>
                            <th class="col-center">Site URL</th>
                            <th class="col-center">Logs URL</th>
                            <th class="col-center">Site URL</th>
                            <th class="col-center">Logs URL</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>eXo Community</td>
                                <td class="col-left"><a href="https://community-preprod.exoplatform.com">community-preprod.exoplatform.com</a></td>
                                <td class="col-center"><a href="https://community-preprod.exoplatform.com/logs">community-preprod.exoplatform.com/logs</a> (LDAP)</td>
                                <td class="col-left"><a href="https://community.exoplatform.com">community.exoplatform.com</a></td>
                                <td class="col-center"><a href="https://community.exoplatform.com/logs">community.exoplatform.com/logs</a> (LDAP)</td>
                            </tr>
                            <tr>
                                <td>eXo Intranet</td>
                                <td class="col-left"><a href="https://int-preprod.exoplatform.org">int-preprod.exoplatform.org</a></td>
                                <td class="col-center"><a href="https://int-preprod.exoplatform.org/logs">int-preprod.exoplatform.org/logs</a> (LDAP)</td>
                                <td class="col-left"><a href="http://int.exoplatform.org">int.exoplatform.org</a></td>
                                <td class="col-center"><a href="http://int.exoplatform.org/logs">int.exoplatform.org/logs</a> (LDAP)</td>
                            </tr>
                            <tr>
                                <td>eXo Blog</td>
                                <td class="col-left"><a href="https://www-preprod.exoplatform.com/blog">www-preprod.exoplatform.com/blog (LDAP)</a></td>
                                <td class="col-center">-</td>
                                <td class="col-left"><a href="https://www.exoplatform.com/blog">www.exoplatform.com/blog</a></td>
                                <td class="col-center">-</td>
                            </tr>
                            <tr>
                                <td>eXo Docs</td>
                                <td class="col-left"><a href="https://docs-preprod.exoplatform.com">docs-preprod.exoplatform.com</a></td>
                                <td class="col-center">-</td>
                                <td class="col-left"><a href="https://docs.exoplatform.com">docs.exoplatform.com</a></td>
                                <td class="col-center">-</td>
                            </tr>
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
                            <th class="col-left" colspan="6">eXo Cloud</th>
                        </tr>
                        <tr>
                            <th class="col-center">Name</th>
                            <th class="col-center">OPC</th>
                            <th class="col-center">OPC Logs</th>
                            <th class="col-center">Dashboard</th>
                            <th class="col-center">MGT Logs</th>
                            <th class="col-center">AS Logs</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>eXo Cloud PRE-PROD</td>
                                <td class="col-left"><a href="https://cloud-preprod-opc.exoplatform.org/opc">cloud-preprod-opc.e.org/opc</a></td>
                                <td class="col-center"><a href="https://cloud-preprod-opc.exoplatform.org/logs/opc">cloud-preprod-opc.e.org/logs/opc</a> (LDAP - team-itop only)</td>
                                <td class="col-left"><a href="https://cloud-preprod.exoplatform.org/a">cloud-preprod.e.org/a</a></td>
                                <td class="col-left"><a href="https://cloud-preprod.exoplatform.org/logs/mgt">cloud-preprod.e.org/logs/mgt</a> (LDAP)</td>
                                <td class="col-left"><a href="https://cloud-preprod.exoplatform.org/logs/as">cloud-preprod.e.org/logs/as</a></td>
                            </tr>
                        </tbody>
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
