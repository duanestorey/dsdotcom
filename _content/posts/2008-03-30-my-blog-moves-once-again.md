---
title: "My Blog Moves Once Again"
publishDate: "2008-03-30"
modifiedDate: "2008-03-30"
slug: "my-blog-moves-once-again"
author: "Duane Storey"
category:
  - "Journal"
tag:
  - "Blog"
  - "home"
  - "media temple"
  - "quad core"
  - "self hosting"
  - "server"
  - "Wordpress"
---

After setting up my RAID 5 array last night, I decided that maybe I would take a stab at self hosting my blog again. Up until two years ago, I had always run my blog from home. It’s not really that hard to set up your own Apache server, and Linux can literally run for months or more without requiring a reboot. The downside of course is that home internet connections can be flaky, and most co-locations and hosting companies have UPS backup when things go wrong.

I recently upgraded my home fiber internet connection to a small business plan, so my internet connection at home is actually fairly insane — I get 10mbps symmetric bandwidth, along with 140 GB of transfer each month. That’s some serious shit.

I used to be on HostMonster, but recently switched over to Media Temple. Unfortunately, I haven’t been very thrilled with the service I’ve been getting there. You can set up a lot of sites and host them on their grid server, but at least a few times a week lately my blog has been down, or it’s just sat there with a “database connection error” for hours at a time. Without a doubt I can do a lot better than that with a machine at home.

So right now duanestorey.com is hosted in the corner of my living room, churning away happily on a quad core machine with 1TB of redundant storage. I tossed on a PHP op-code accelerator and tuned apache a bit, so it should be fairly smoking fast, assuming the fiber can keep up. I did a benchmark on Media Temple, and it could serve 12 requests per second. I tested my home machine here and it’s around 130 requests per second, so we’ll see how it goes.

Obviously the major downside is that if the machine crashes while I’m at work or away, then my blog will be down. I may just send automated mysql dumps over to Media Temple (since I still am keeping my account for other things), so I can remap my DNS if my blog at home falls over while I’m away. I don’t think it will though.

PS – It’s an absolute joy to use WordPress when it’s amazingly responsive