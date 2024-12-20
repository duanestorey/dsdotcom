---
title: "Server Hawk Down!"
publishDate: "2009-01-13"
modifiedDate: "2009-01-13"
slug: "server-hawk-down"
author: "Duane Storey"
category:
  - "Journal"
tag:
  - "home"
  - "hosting"
  - "server"
---

Well, last night was interesting, to say the least. For those of you who follow around here, about a year ago I decided to host my blog from home instead of on some shared hosting account. My main reason for doing that was that I had been through around 3 shared hosting providers that year, and all of them had let me down on more than one occasion. So I figured I could do it better myself.

Which was true to an extent. However last night, while I was packing boxes near the server, I guess I accidentally bumped the power cable, causing my server to reboot. Normally, that’s not a terrible thing, as the file system usually does a check and everything is good to go. Unfortunately in my case, I had to manually go through around 500 errors that resulted from that, and I basically knew something was completely wrecked.

When I built that computer, I actually bought three 500 GB SATA2 drives for the RAID 5 array. Those have worked flawlessly, and I’ve never had an error on those. Unfortunately, I used a really cheap and old 100GB drive from an old PC of mine for the OS drive, which I think is the root cause of the problem. For whatever reason, it just seems to go into egg-beater mode whenever the power goes off, and ends up corrupting stuff.

Realizing I was in over my head with rebuilding an OS drive that needs access to a software RAID array, I had to call in the big guns: Trevor O. Trevor came by last night for a few hours and we basically came up with a quick patch involving a YUM update that fixed most of the problems (at least until I attempt a reboot).

Once I get back to Chilliwack I’ll put a decent OS drive in there and rebuild it. That being said, I’m going to deem my one-year long hosting experiment as the Apollo 13 of web hosting — we never made it to the moon, but at least we got everyone home safe and sound. I’ve moved all my important sites over to Media Temple, and will probably leave them there going forward. I still plan on using my server for all my photography, and for mass storage, so it’ll still get good use out in Chilliwack.