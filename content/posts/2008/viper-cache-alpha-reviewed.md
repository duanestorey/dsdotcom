---
title: "Viper Cache Alpha Reviewed"
date: "2008-02-20T05:51:05.000Z"
categories: 
  - "journal"
tags: 
  - "plugins"
  - "review"
  - "themes"
  - "viper-cache"
  - "web-design"
---

Well, this is interesting. I gave a few individuals an alpha version of the Viper Cache plugin I've been working on. It has just been reviewed on [Anieto2k](http://www.anieto2k.com), one of the largest plugin sites. The author compared it against all the Wordpress plugins available today for caching. The full write-up is [available here](http://www.anieto2k.com/2008/02/19/testeando-viper-cache-para-wordpress/).

The article looks like it is in Spanish so you'll just have to settle for the pictures. Based on a few guesses in the translation, it looks like Viper Cache is the lowest in memory out of all four, and allows around 1330 pages per second on the test machine compared to the second place runner up of Super-Cache at around 300 pages per second. I also want to point out that I seriously doubt their test takes into account (only because such a test doesn't immediately come to my mind either) cache hits that are **only** fetched from local caches (which is made possibly by some HTTP caching directives Viper Cache inserts), and that would result in further performance gains.

So, like I had hoped, it appears that this will ultimately be the fastest Wordpress caching engine around. I have one more major enhancement to make to it with regards to cache invalidation, and then I'll release it. I just finished the change. I actually thought of a way smarter way to do what I was trying to do, and it will make an even larger difference. I'm running it here already, and I think it will work like a charm.

\*\* Jonathan Lumb [translated that article into english](http://www.sprayfly.com/2008/02/20/testing-viper-cache-for-wordpress/) if anyone is interested. Thanks.

I'll release this soon.
