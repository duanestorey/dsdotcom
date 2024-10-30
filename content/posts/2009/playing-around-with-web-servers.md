---
title: "Playing Around With Web Servers"
date: "2009-03-09T18:28:28.000Z"
categories: 
  - "journal"
tags: 
  - "apache"
  - "fun"
  - "threads"
  - "web-server"
  - "webby"
  - "wordpress-55"
---

Obviously I've been spending a bit of time tweaking Apache such that it is well optimized for working in a VPS environment. Truthfully though, Apache is a major resource hog. To be honest, I really can't comprehend what it's doing with all that memory it seems to hoard. One of the other major downsides to Apache is that it forks additional processes to handle server load, and processes are rather heavy (at least when compared to user threads).

Since I haven't touched any C++ in about two months, I thought I'd try my hand at writing a quick web server to see if I can better understand some of problems. For now, I'm leveraging boost for all the threading, and just using simple select now for all the socket communication (I understand the limitations of select, and will probably move things to boost::asio when it's all done).

I originally wrote it using a half-async pattern, but found that I was having problems getting the threading the way I wanted without having lock contention going on between threads. So I've pretty much abandoned that in favor of a fully asynchronous architecture, which is trickier to handle but ultimately gets rid of nearly all of the locks. I still haven't really figured out the best way to handle memory, but obviously some type of pool is going to be required for high efficiency. One of the areas I spent a lot of time with at my last job was optimizing code for speed, so I'm hoping I can bring some of that to the web space problem as well.

In terms of the actual server, right now there's a main thread handling all the dispatching and communications, and a pool of worker threads handling the actual web traffic. It's a typical web traffic architecture, and I plan to pick it apart when I'm done to see if it actually makes sense.

In about four hours in front of the TV yesterday I managed to get the web server (affectionately called Webby right now) to serve static content. I'm using four threads right now for the worker pool, but it's completely configurable (I'm guessing around four threads per core is probably ideal). Once I had that working, I decided to take a stab at getting PHP code to work. I did some basic stress testing at this point (no point in continuing if I can't blow Apache away). I did 10,000 requests for static content using Apache, and 10,000 requests for the same content using Webby. Apache took 42.5 seconds to serve a static HTML file over the LAN, which amounts to around 235 individual requests per second. Webby did it in about 3.28 seconds.

Apache has an distinct advantage because PHP can be built as a module for it, basically loading PHP into Apache. Given that this is just something I'm doing for fun, I don't really want to spend the time doing that at this point. So my only options at this point are to drop to the OS and execute PHP, or execute it as a CGI. I tried the former, and it was painfully slow. So that basically meant going the CGI route.

The best way to do CGI is using Fast CGI, which is something I've never done before. Basically you open a local socket to a server running PHP, and send it requests to process scripts. I ended up writing a mini Fast CGI library, since I couldn't find one that was usable. It took me a few hours, but it seems to be working now with PHP.

I still have a few major optimizations to do before I can test out the PHP part properly (you have to initialize PHP with various variables before WordPress will work properly), but maybe next weekend I can set aside an afternoon and finish it off.

Apache's major detriment is that it's such a memory hog. Without a doubt, one of the major advantages to Webby in a low memory environment is that it won't start trashing into swap space as soon as Apache will. I also will be able to adjust the stack size per thread (something that I haven't done yet), so conceivably I could get the memory foot print even lower (although right now Webby is using around 1.5 MB of memory, whereas Apache is using around 120 MB I believe).
