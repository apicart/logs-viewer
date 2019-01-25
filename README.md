<h1 align="center">
  Logs Viewer
  <br>
   <a href="https://travis-ci.org/apicart/logs-viewer">
    <img src="https://travis-ci.org/apicart/logs-viewer.svg?branch=master">
  </a>
  <a href="https://github.com/apicart/logs-viewer/blob/master/LICENSE">
    <img src="https://img.shields.io/github/license/apicart/logs-viewer.svg" alt="">
  </a>
  <a href="https://packagist.org/packages/apicart/logs-viewer">
    <img src="https://img.shields.io/packagist/dt/apicart/logs-viewer.svg" alt="">
  </a>
</h1>

A simple tool for viewing logs. Because sometime that's all you need.

## Installation 
1. Create Logs Viewer project
```
composer create-project apicart/logs-viewer logs-viewer
```
2. Direct your logs into the logs viewer `logs-viewer/var/external-logs` directory.
3. Run `php -S [::]:80 -t logs-viewer/public`
4. The Logs Viewer should be now accessible from http://localhost

## Preview
<div align="middle">
  <img src="https://github.com/apicart/logs-viewer/blob/master/screen.png?raw=true">
</div>
