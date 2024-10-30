---
title: "How To Make Your Website Or Blog Faster"
date: "2008-02-18T06:05:56.000Z"
categories: 
  - "journal"
tags: 
  - "apache"
  - "caching"
  - "digg"
  - "drupal"
  - "htaccess"
  - "speed"
  - "tips-tag"
  - "viper-cache"
---

There are a lot of different ways you can increase the speed of your website, even if you have relatively cheap hosting. If you're lucky, your blogging platform already has a caching engine built in (Drupal does). If you're unlucky, and running something like Wordpress, you have to do a bit more work.

Caching makes a website more responsive because it takes an expensive operation (such as a long database query) and stores it so that next time it doesn't have to recompute it entirely. For example, when you go to this website, normally Apache would execute PHP, parse the Wordpress code, do some MySQL database queries, and then finally output the HTML page. Depending on the hosting service, this may take a long time. With a cache, the final HTML page is simply written to disk (or memory if a memory cache is being used) so that when that page is requested the second time, it is merely read from disk and sent, which by comparison is relatively fast.

The first thing I'd recommend everyone with Wordpress to do is to enable the built in object cache. It's not very well documented, but Wordpress has the built in ability to cache many of the expensive database queries. To enable it, locate your wp-config.php file (which is usually in the root directory of your web installation), and add the following line to it:

`define('ENABLE_CACHE', true);`

That's it. You should get a fair performance increase just be turning that on.

I also suggest you Wordpress people download the [Wp-Cache](http://mnm.uib.es/gallir/wp-cache-2/) plugin. It's fairly easy to install, and gives you a huge speed increase as soon as it's up and running. Also, if your site ever gets to the front page of a website such as Digg, your website will most likely choke and die without some type of caching enabled. If you're **really** adventurous, you can help me test out my [Viper Cache plugin](http://www.migratorynerd.com/2008/02/wordpress-viper-cache-alpha/) that should ultimately be faster that Wp-Cache (and it's in use on this website).

While that solution will increase the speed of your PHP scripts, it doesn't do much to help JPEGs or other files on your server. Thankfully, there is another easy way to make those a lot faster using the Apache mod\_expires module.

With mod\_expires, you can tell Apache to insert caching directives for particular types of files as they are requested. For example, you can make it so that Apache instructs all browsers to store images in their local caches for four hours. With that directive, a browser will only have to download that image once and, for the next four hours, can simply retrieve it from it's local cache. This not only saves you bandwidth, but makes its seem like the pages on your website load a lot faster.

If your website supports .htaccess along with mod\_expires, you can simply add something like this to it:

```

   <IfModule mod_expires.c>
   ExpiresActive on
   ExpiresDefault now
   ExpiresByType image/gif "access plus 4 hours"
   ExpiresByType image/jpeg "access plus 4 hours"
   ExpiresByType image/png "access plus 4 hours"
   ExpiresByType audio/mp3 "access plus 2 hours"
   ExpiresByType audio/x-m4a "access plus 2 hours"
   </IfModule>
```

By setting the "ExpiresDefault" to "now", it is instructing Apache to make everything non-cacheable except for the items listed below it. Without this, Apache would cache everything, including PHP scripts (which since they are dynamic, would be a fairly bad thing).

As you can see, I've included a directive to cache JPEG images (with a MIME type of image/jpeg) for four hours, MP3 files for two hours, etc. You can modify this and add whatever MIME type you like, and the browsers will do the caching for you. The one downside to this is that the browser will not request that file again until the allotted time expires. What that means is that even if you change it on your webpage, it's possible people won't see those modifications for quite some time. If you set relatively small expires times, that isn't normally a problem. However if you want to set longer ones, the only way to force an immediate change to an item is to change the filename on the server (which is an easy work around).

If you combine those three methods, you can make Wordpress a hell of a lot faster, and decrease the demands on your hosting server. Plus, you should be able to handle a Digg or any other sudden influx of traffic with relative ease.
