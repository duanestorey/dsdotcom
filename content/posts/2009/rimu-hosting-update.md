---
title: "Rimu Hosting Update"
date: "2009-03-08T19:42:13.000Z"
categories: 
  - "journal"
tags: 
  - "apache"
  - "lighttp"
  - "php"
  - "rimu-hosting"
  - "web-5"
---

So I've had my website on Rimu for over a week now, and so far things are going quite well. I've actually had a few support requests in with them, mostly trivial items, and they've answered everything in about 30 minutes, which is great.

The one thing I've found out is that their base plan (which only included 160 MB of memory) just isn't powerful enough to run more than a small WordPress site. I spent a bunch of time reducing the memory requirements of Apache and MySQL, but my efforts just weren't successful. Every once and a while an application would crash with a kernel out of memory warning which would basically render the site inoperable. The guys at Rimu actually tried to help me make it all work, but they basically recommended upgrading the virtual server, as Apache is pretty much a memory hog.

Thankfully Rimu allows you to customize your plan from their control panel, so I upgraded my memory to 400 MB and also increased my transfer allowance. In total I'm paying about $25/month right now, which is still totally reasonable. I'm also going to try to install lighttpd today on the server and use it to serve the static content (images, javascript, css), since apparently it's lightning fast at anything that doesn't involve PHP. Once I find a configuration that seems to work flawlessly, I'll start posting some of my configuration files. I've found [this guy's site](http://emergent.urbanpug.com/?p=61) is a pretty great resource for configuring MySQL and Apache for low memory configurations.
