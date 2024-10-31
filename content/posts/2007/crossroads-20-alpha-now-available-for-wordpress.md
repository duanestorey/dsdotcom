---
title: "Crossroads 2.0 Alpha Now Available For Wordpress"
date: "2007-07-28T21:28:21.000Z"
categories: 
  - "journal"
tags: 
  - "crossroads"
  - "flickr"
  - "greybox"
  - "highslide"
  - "lightbox"
  - "plugin"
---

I've really been trying hard to get a new release of Crossroads out for quite some time, but with my job at work, it's just so hard to find a few hours to spare these days. That being said, I've up-reved the plugin to version 2.0, and am making the changes I've done available as a download. **This is an early alpha, and for sure it has a few bugs**, so please don't expect it to be perfect.

Compared to version 1.0, here are some changes:

- Added support for [GreyBox](http://orangoo.com/labs/GreyBox/) image gallery
- Added support for [HighSlide JS](http://vikjavev.no/highslide/) image gallery
- Fixed a few changes with how the scripts were loaded to improve compatibility
- Added more adminstration options
- Removed Flickr comments from posts, as these were very slow -- you can re-enable them via the admin panel if you want

The original plugin page for Crossroads [can be found here](http://www.migratorynerd.com/crossroads-plugin/), and it details how to use it and provides a demo using Highslide JS.

Please note that I have included the image galleries as part of the distribution (for compatibility reasons), but that it is your responsibility to head over to Lightbox, Greybox or HighSlide and verify that you are conforming to their licenses if you use them on your site. A few options still don't work properly (namely the local image caching), and I haven't tested HighSlide that much at all.

So, without further ado, [here is the download link for Crossroads v2.00a](http://www.migratorynerd.com/data/crossroads_2_00_alpha.tar.gz). Please post feedback or bugs as comments to this post. Also, for those of you with blogs that use it, I'd appreciate it if you can add a link to this post to get the word out (I'll add a Crossroads RSS feed at some point). Thanks.

Here's a sample import of a Flickr set using one simple command in the HTML, courtesy of Crossroads:
