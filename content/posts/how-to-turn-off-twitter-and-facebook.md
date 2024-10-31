---
title: "How To Turn Off Twitter and Facebook"
date: "2009-07-30T19:49:44.000Z"
categories: 
  - "journal"
tags: 
  - "facebook"
  - "hack"
  - "twitter"
---

I've been trying to get more work done during the days lately, mainly so that I have less work to do in the evenings. One thing I've been trying to curb is my Twitter and Facebook usage.

Some days I hardly use either, but sometimes when there's a good conversation going on, I find it hard to pull away from. In addition, I do a lot of things on the computer almost instinctively, one of which is saving files after every small edit (at the end of every line of code I usually hammer on command-S, which saves the file -- I don't even think about it, it just happens). Similarly, if I have a browser open and am about to task swap, I usually click on my Twitter bookmark just to see what's going on. Even if I don't really care about Twitter at that point, sometimes that leads into five minutes of @replies and conversation.

So while nothing more than a brain hack really, I came up with a simple solution which basically involves modifying the hosts file. Simply define Twitter and Facebook to resolve to localhost, i.e. 127.0.0.1:

`127.0.0.1 www.twitter.com 127.0.0.1 twitter.com 127.0.0.1 www.facebook.com 127.0.0.1 facebook.com`

Which will basically cause each of those sites to bork when trying to load. Obviously, you can go in and change those entries whenever you want, but it's enough of a deterrent that I don't find myself wanting to do it. What it does do for me is makes it so that if I accidentally click on a Twitter link (out of habit), that I don't find myself suddenly at the bottom of the rabbit hole wondering just where in the hell @Alice is.
