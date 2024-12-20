---
title: "Redundant Web Architectures"
publishDate: "2010-06-24"
modifiedDate: "2010-06-25"
slug: "redundant-web-architectures"
author: "Duane Storey"
featuredImage: "_images/redundant-web-architectures-featured.jpg"
category:
  - "Journal"
---

[![](_images/redundant-web-architectures-1.jpg "2379600590_bf110b0d85")](http://www.migratorynerd.com/wordpress/wp-content/uploads/2010/06/2379600590_bf110b0d85.jpg)  
So I realize this could potentially be a pretty deep rabbit hole, but I thought I’d ask the question here. I’m in the process of trying to put together a simple web server configuration that is more robust than a single server configuration. Truthfully, I’ve never set something like this up, but for what I’m after I’m not looking for something amazingly complex or expensive.

The server needs to basically handle HTTP requests, and interface with a database. In my mind, this would require two servers in separate co-location centres (which would help if one centre went down and not the other). One machine would be the primary web/database server, and the second machine would be the slave. A cron job would synchronize the files on the slave every 5 minutes or so to make sure it stays up to date, and the database would be set up in a master/slave scenario.

All of that basically makes sense to me so far, but if you know of a better configuration let me know. I know some people purposefully put the database server on its own machine, but to keep redundancy I believe I’d need four machines in that scenario, and not two, which is more complicated than I want to start out.

The piece of the puzzle I don’t really understand is how to check for a failure and how to recover from one. In my mind this would require some kind of external DNS service that would ping the primary web server and alter the DNS record for when the primary stopped responding. As these machines are in separate co-location facilities, I don’t think I can do any type of IP takeover (at least, not that I’m aware of). Can anyone recommend a way to solve this piece of the puzzle, or suggest another way of doing this?