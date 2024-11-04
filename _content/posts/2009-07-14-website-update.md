---
title: "Website Update"
publishDate: "2009-07-14"
modifiedDate: "2009-07-14"
slug: "website-update"
author: "Duane Storey"
category:
  - "Journal"
tag:
  - "BraveNewCode"
  - "design"
  - "Photography"
  - "website"
---

It’s been about three days since I launched the new site, and I’ve had some great feedback regarding it (thanks everyone). I’ve made a few small tweaks based on some comments, so hopefully it’s a bit better.

I’ve been watching my traffic, and so far I’ve consistently about double the traffic (actually, a tad bit more) since launching. I’m sure part of that is a transient contribution from launching a new site and Twittering about it, but I’m fairly certain the traffic will definitely go up, since there’s now a lot more to do here now (specifically in the photography section). I’m also tracking bounce rate, and I’m hoping that goes down significantly for the same reasons. I generally don’t care that much about traffic, but as a metric for gauging the effectiveness of a new design and/or content change, I think it’s decent enough.

I’ve slowly been tinkering away at Crossroads as well. Last night I added client side caching of the Flickr API requests, which should make most photography pages load a lot faster (at least when the data is already in the cache). I also added all the code such that I can import Flickr comments into each photo area, but I’m holding off flipping the switch on that until I can come up with a decent way to present it (it’ll probably be AJAX when it’s done, as getting the comment avatars is expensive from a Flickr API point of view and I don’t really want to slow down each page load because of it). Hopefully I’ll have time to finish version 2.0 of Crossroads in the next few weeks and finally release it.

Anyways, I’ll keep building this out over the next little while, and hopefully release a full featured photography theme at some point. Seems like there’s definitely some interest in the microstock implementation as well, so I’ll hack that out at the same time.