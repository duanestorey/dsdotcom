---
title: "How To Survive The Reddit Effect"
publishDate: "2013-10-07"
modifiedDate: "2013-10-07"
slug: "how-to-survive-the-reddit-effect"
author: "Duane Storey"
featuredImage: "_images/how-to-survive-the-reddit-effect-featured.jpg"
category:
  - "Blogging"
tag:
  - "Cache"
  - "CDN"
  - "Infinity Cache"
  - "MaxCDN"
  - "Reddit"
  - "W3"
---

Yesterday I was casually flipping around some apps on my iPhone and opened up my Adsense application which shows me how much I’ve made from Google Adsense each day. I noticed almost immediately that there was an anomaly – for the current day I had served almost 4,000 ad impressions, when normally it would have been more like 200 around that time of day. I then followed up by logging into Google Analytics to see if I had been mentioned somewhere, and sure enough I noticed that my number one referrer was Reddit.com at the time. Reddit gets around one billion page views a month I believe, so any mention on that website can be enough to completely cripple a website with new visitors.

Apparently someone had shared a recent post of mine on Reddit discussing how to cut a SIM into a Micro or Nano SIM. It’s not exactly rocket science, as many others pointed out in the comments. But it generated a lot of discussion from people who said it does indeed work (which it does) and people who didn’t think it would work at all.

But for me I wasn’t really interested in the comments on Reddit, but rather whether or not my website was going to be able to handle the load of all those new users. At the time Google Analytics was showing around 300 active users, and the CPU for my $20/mo VPS unit was sitting around 35%. I tried to log into the admin panel for WordPress, but I couldn’t get to it from Amsterdam due to my slow internet connection and the massive amount of traffic my website was serving.

My website is actually fairly well-tuned for a WordPress based website, but I’ve never had to face a traffic spike like this. I wrote a post a few weeks ago talking about [how to speed up your WordPress website](http://www.migratorynerd.com/tips/wordpress/5-ways-to-speed-up-your-wordpress-website/), and I thankfully completed all those same optimizations on my own website prior to the Reddit mention.

First, I had the APC PHP cache installed on my server which greatly reduces the load for PHP scripts. Second, I had W3 total cache installed which essentially removes PHP and WordPress from the equation for spikes like this, since Apache will simply serve static html files after they are cached. I also had Infinity Cache installed, which is a soon-to-be-released mobile caching solution for WPtouch. And lastly, I serve all of my static website assets from MaxCDN, which is a global content delivery system.

So in terms my site’s ability to actually be able to survive the Reddit Effect, I was in pretty good shape.

But since I couldn’t log into my admin panel, I decided to try and optimize Apache and PHP a bit further to try and make my server a bit more responsive.

### httpd.conf Adjustments

One of the first adjustments I did was to increase the MaxServers for httpd up to 20 from 10. Since the VPS is memory limited, I left it at 10 simply because I didn’t see much point raising it higher. But I figured doubling the amount of processes available to handle the incoming requests would help, and it did.

Another quick adjustment I made was to disable KeepAlive connections. Normally KeepAlive connections are good because clients can request multiple items in essentially the same request. But often an Apache process will continue waiting around for more requests, even after the client is finish. That means that particular Apache process isn’t available to serve any more requests until it essentially times out. So I disabled KeepAlives and then rebooted Apache from the command line.

After my changes Apache stabilized at serving over 100 page views per minute, with approximately 500 visitors active at any time.

![Reddit Traffic Spike](_images/how-to-survive-the-reddit-effect-1.jpg)Reddit Traffic Spike



### Final Results

The total traffic onslaught lasted about eight hours, during which time my website served close to 20,000 page views. During the most intensive hour, there were almost 6,000 page views.

![Traffic Spike](_images/how-to-survive-the-reddit-effect-2.jpg)Traffic Spike



I logged into [MaxCDN](http://www.kqzyfj.com/click-4154459-11373487) after it was over to check the traffic stats, and they showed approximately 50 GB worth of data transferred during that time period, which is about 5% of the total amount I’m allocated *for an entire year*. That’s a substantial amount of traffic. I haven’t done the math, but I suspect my little VPS would have basically fallen over had I not used the CDN, simply because it wouldn’t have been able to keep up with the bandwidth requirements of all those requests.

![MaxCDN During The Spike](_images/how-to-survive-the-reddit-effect-3.jpg)MaxCDN During The Spike



### Key Takeaways

For the most part my little $20 [DigitalOcean VPS](https://www.digitalocean.com/?refcode=c62a4d3586fc) stayed active during the eight hour assault on it by Reddit users. It amounted to nearly 20,000 unique page views during that period of time, with almost 50GB worth of data transferred. I’m quite pleased the VPS didn’t fall over, and I owe that mostly to the various caching systems I had in place, as well as my choice for serving most of my static content from a CDN.

A few people on Reddit commented that my site was down during that period, which was my experience at the beginning as well. This was simply the result of having so many people try to view that particular webpage that Apache couldn’t keep up with the requests. But after making a few quick Apache adjustments, Apache’s ability to handle webpages nearly doubled, which allowed me to log back into my site and seemed to make the website in general more responsive for everyone.

So even if you have a small VPS, by installing W3 Total Cache and utilizing a CDN like [MaxCDN](http://www.kqzyfj.com/click-4154459-11373487), you should be able to handle the Reddit Effect if it happens to you too.

I’m in the process of setting up another small $5/mo VPS with a Varnish instance in case it ever happens again. With that addition to the mix I don’t think I’ll have any issues at all with being mentioned on Reddit again in the future.