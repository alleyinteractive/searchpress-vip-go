# SearchPress VIP Go Add-On

Because [VIP Go](https://vip.wordpress.com/documentation/vip-go/) uses [a WP-CLI-based cron system](https://github.com/Automattic/Cron-Control), individual cron jobs can run longer without impacting server performance or other cron jobs.

This add-on converts the current cron-based full sync tasks in SearchPress, which by default splits the indexing into 500-post batches across _n_ cron events, to instead index all posts in a single cron event. Posts are still indexed in batches of 500 to conserve memory and maintain reasonable HTTP requests, but a batch of 500 may take 5-10 seconds vs 1 minute.

## Instructions

To use this plugin, simply install and activate. No configuration is necessary.

## Only Recommended for VIP Go

This plugin can technically be used outside of VIP Go, though you should only consider doing so if you're using the [Cron Control plugin](https://github.com/Automattic/Cron-Control) or something similar that allows multiple cron jobs to run at a time. Using this plugin with the default WordPress cron could lead to issues, like slowing down your web server and blocking other cron jobs.
