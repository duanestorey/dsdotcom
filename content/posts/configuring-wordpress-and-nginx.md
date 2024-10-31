---
title: "Configuring WordPress And Nginx"
date: "2013-10-16T23:54:19.000Z"
categories: 
  - "wordpress"
tags: 
  - "hosting"
  - "nginx"
  - "php"
  - "servers-2"
  - "wordpress-3"
coverImage: "speed-scaled.jpg"
---

After my [recent mention on Reddit last week](/tips/blogging/how-to-survive-the-reddit-effect/), I decided to try and improve the responsiveness of my little [DigitalOcean VPS](https://www.digitalocean.com/?refcode=c62a4d3586fc) even more than it already was. I was definitely happy that I was able to withstand almost 20,000 visitors in just a few hours, but thought I might be able to improve things further by switching from Apache to Nginx.

I debated it a long time ago, but never made the plunge for a few different reasons. First, I typically used hosting services based on cPanel. From what I gather, cPanel doesn't really work well with Nginx (or at all), so that was never really an option. And second, I could never quite figure out how to set up WordPress and Nginx properly so that they worked nicely together. It's slightly more complicated because I often use W3 total cache, and it normally places its configuration into .htaccess for Apache.

But I finally decided it was time to give it a try - I fired up a new VPS at [DigitalOcean](https://www.digitalocean.com/?refcode=c62a4d3586fc) to see if I could get my personal website working with Nginx instead of Apache. After scanning around the internet for a while, I managed to piece together a Nginx configuration that works with WordPress 3.6 as well as W3 Total Cache and my mobile website.

```
server {
        server_name  www.migratorynerd.com migratorynerd.com;
        listen 80;
        index index.php index.html index.htm;
        root /var/www/duane/migratorynerd.com/;

        access_log off;
        error_log /var/log/nginx/migratorynerd.com-error.log;

        gzip on;
        gzip_types text/css text/x-component application/x-javascript application/javascript text/javascript text/x-js text/richtext image/svg+xml text/plain text/xsd text/xsl text/xml image/x-icon;

        location ~ \.(ttf|ttc|otf|eot|woff|font.css)$ {
                add_header Access-Control-Allow-Origin "*";
        }

        set $cache_uri $request_uri;

        if ($request_method = POST) {
                set $cache_uri 'null cache';
        }

        if ($query_string != "") {
                set $cache_uri 'null cache';
        }

        if ($request_uri ~* "(/wp-admin/|/xmlrpc.php|/wp-(app|cron|login|register|mail).php|wp-.*.php|/feed/|index.php|wp-comments-popup.php|wp-links-opml.php|wp-locations.php|sitemap(_index)?.xml|[a-z0-9_-]+-sitemap([0-9]+)?.xml)") {
                set $cache_uri 'null cache';
        }

        if ($http_cookie ~* "comment_author|wordpress_[a-f0-9]+|wp-postpass|wordpress_logged_in") {
                set $cache_uri 'null cache';
        }

        if ($http_user_agent ~* "(W3\ Total\ Cache/0\.9\.3|iPhone|iPod|Android|BlackBerry|BB|IEMobile|webOS)") {
                set $cache_uri 'null cache';
        }

        location / {
                try_files /wp-content/cache/page_enhanced/${host}${cache_uri}_index.html $uri $uri/ /index.php?$args ;
        }

        location = /favicon.ico { log_not_found off; access_log off; }
        location ~ .php$ {
                try_files $uri /index.php;
                include fastcgi_params;
                fastcgi_pass unix:/var/run/php-fpm.sock;
                fastcgi_param   SCRIPT_FILENAME    $document_root$fastcgi_script_name;
                fastcgi_param   SCRIPT_NAME        $fastcgi_script_name;
                fastcgi_buffer_size 128k;
                fastcgi_buffers 4 128k;
        }

        location ~* .(ogg|ogv|svg|svgz|eot|otf|woff|mp4|ttf|css|rss|atom|js|jpg|jpeg|gif|png|ico|zip|tgz|gz|rar|bz2|doc|xls|exe|ppt|tar|mid|midi|wav|bmp|rtf)$ {
               expires max; log_not_found off; access_log off;
        }
}

```

Feel free to cut and paste this file and use it on your own WordPress website (with your own modifications obviously) if you're setting up Nginx at any point in time. There are a couple lines in this file that I want to discuss a bit further.

1. _access\_log off;_ - I can't remember the last time I looked in any detail at my access log. Between Google Analytics and Google Webmaster Tools I get a good sense of all the traffic on my website. So I figured I might as well disable access logging to save CPU cycles as well as disk space on my VPS. I left the error log in place, since I definitely take a look at that periodically.
2. _gzip on;_ - This section enables GZIP compression for common text formats such as CSS and Javascript
3. _fastcgi\_buffer\_size 128k;_ - I set up Nginx to work with php-fpm, which is how must tutorials recommend having Nginx and PHP work together. As soon as I got my site working though, I noticed most of my HTML pages were truncated at the bottom. I eventually tracked it down to these two parameters, since apparently there wasn't enough memory being passed back and forth between Nginx and PHP, which caused some of it to get lost. Adding these parameters fixed it.

There's also a small bit of logic in the nginx server configuration file above with regards to user-agents - this is code that makes W3 play nicely with mobile plugins such as the [WPtouch Mobile WordPress plugin](http://www.bravenewcode.com/wptouch/). You can read more about setting up [WPtouch with Nginx](http://www.bravenewcode.com/wptouch/optimizing-nginx-cache-wptouch-pro-3/).

### Testing With W3 Total Cache

Once you have it all set up and working, it's a good idea to check to make sure that W3 is serving cached files. The best way to check this is to open up your website in a browser and make sure you are logged out (W3 usually doesn't cache pages for logged-in users). After viewing the website, open up the page source and scroll to the bottom. You should see something like this.

```
 Served from: www.migratorynerd.com @ 2013-10-16 16:49:32 by W3 Total Cache -->
```

The date and time is what you are ultimately looking for. Now refresh the entire page again in the browser, and view the page source again. If the date and time stayed the same, Nginx just served the cached W3 page directly from disk and everything is set up correctly. If the date and time changes, it's likely you have a configuration problem in the Nginx config file, probably with the path to the W3 files.

### Final Thoughts

All in all it wasn't very hard to set up Nginx, and I'm glad I finally made the switch. In all honesty now that I'm used to it, I find configuring Nginx to be simpler in general than Apache. I always thought configuring Virtual Hosts in Apache was a bit clunky. The process in Nginx is simply to create a new config file in a particular directory, and it just works.

I'm glad I finally took the plunge and switch to Nginx. If you've been considering it as well, I recommend going for it as it turned out to be simpler than I originally imagined.
