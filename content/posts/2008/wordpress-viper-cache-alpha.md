---
title: "Wordpress Viper Cache Alpha"
date: "2008-02-12T07:20:10.000Z"
categories: 
  - "journal"
tags: 
  - "blogging"
  - "optimization"
  - "plugin"
  - "tips-tag"
  - "viper-cache"
  - "wordpress-19"
  - "wp-cache"
---

I've been dabbling with a new caching engine for Wordpress over the last few weeks. While WP-Cache does a pretty decent job, it falls short in a few areas, and it's something I've been trying to fix. In particular, here's what I don't like about it:

1. It relies on the entire Wordpress engine to do it's caching
2. It doesn't make use of proper HTTP caching headers

The problem with the first item is that even if you have a cache hit, the entire Wordpress PHP core is parsed and partially executed. What that means is the caching system will always be limited by how fast the PHP parser is on the host machine, and how bloated the Wordpress code-base is.

The second item is a little more subtle. If you request a page from a website using WP-Cache and it registers as a cache hit, basically WP-Cache reads a file from the disk and transmits it to you. This is a big gain, since you don't have to do all the database queries over again to generate the original page. However, it's sort of unnecessary to transmit the data to a browser if the data already exists already in the local machine's cache. In order to take advantage of that, you have to do a bit more bookkeeping and set proper HTTP headers at various stages of the requests, something that WP-Cache hasn't tackled yet.

With the caching engine I've been working on (called Viper Cache for now), I have basically solved both of those problems. To solve the first I removed the caching engine from Wordpress and made it stand-alone, relying only on PHP itself. So when you register a cache hit, Wordpress isn't instantiated at all and an optimized PHP script serves the proper content. This works together with a Wordpress plugin that manages invalidating parts of the cache whenever content on the blog changes.

In addition, proper HTTP caching headers are sent, so for most browsers, no data is even transmitted the second time a page is requested (what happens is the browser asks if the content has changed using an If-Modified-Since header, and the Viper Cache will respond with a 304 Not Modified if the local copy is the same as the remote copy). This results in a CPU and bandwidth savings. If the copy on the server has been modified, and the local cache doesn't match the remote copy, the entire page is served again from the website cache.

I've been running it on my blog for about a week now, slowly ironing out the major bugs (and there have a been a few related to Wordpress cookies and improper Content-Types). I did a fairly exhaustive benchmark a few minutes ago, and using my configuration on Media Temple, Viper Cache is about 2.5x - 3.0x faster then WP-Cache for serving pages (and that's not even taking into account items that are already in the browser's cache).

What I need now is a few guinea pigs to help iron out the last bugs. Is anyone interested in putting it on their blog and testing it out for a week or so? If so, drop me a comment.
