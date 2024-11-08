---
title: "New Flickr Plugin"
publishDate: "2008-08-11"
modifiedDate: "2008-08-11"
slug: "new-flickr-plugin-for-wordpress-with-attribution"
author: "Duane Storey"
category:
  - "Journal"
tag:
  - "api"
  - "attribution"
  - "BraveNewCode"
  - "creative-commons"
  - "flickr"
  - "Photos"
  - "Wordpress"
---

Yes sports fans, it’s that time again — we’ve been messing around with yet another plugin over at BraveNewCode. But first, everyone should know that we just put the finishing touches on WPtouch 2.0 today. There are still a few small bugs to squish, but hopefully it’ll hit the market sometime in the next week or two. It’s pretty sexy, if I do so say so myself.

In terms of the new plugin, I’ll give everyone a little context. In the last two months I’ve had several people infringe upon my Flickr photo licenses by using my photos commercially. One of those incidences ended peacefully, but unfortunately the other one is still ongoing. While not for certain, I suspect the odd person grabs photos from my blog (after doing a Google image search), not really knowing that photos on my blog have CC licenses associated with them.

So to battle that problem, I’ve always envisioned a Flickr plugin for WordPress that would be aware of the CC licenses for each of the photos. Today I sat down and hacked out a proof of concept in a few hours. You should be able to see the end result here:

[![Jessie Farrell](_images/new-flickr-plugin-1.jpg)](http://flickr.com/photos/duanestorey/2749456364/)

The code fragment in the HTML I used to generate the Flickr image above is the same fragment I always use, consisting of nothing more than an IMG tag. The benefit of this is that every single Flickr image I’ve ever used will automatically be adjusted when the plugin is on. The plugin now performs the following behind the scenes:

1. The plugin locates Flickr images in the HTML source, and then deconstructs the Flickr URL to determine the Flickr image number
2. Using the Flickr API, the plugin determines extra information about the image, including the CC license, and the author
3. The plugin queries the Flickr licensing information to determine information about each license, along with a URL describing each one
4. The plugin modifies the DOM model to add proper captioning and attribution, along with the proper license information for the photo

The last point was one of the main complaints I heard on some blogs about a year ago. For whatever reason people felt it was too much work to find the profile information on Flickr and obtain the user’s real name. So, now those people don’t have to.

Arieanna and I had a conversation in Victoria about b5media last week, and one of the things she told me was that a great deal of traffic on their site comes in via image searches. Part of the reason the searches rank so hightly is that bloggers there spend the extra time storing photos locally and renaming the photos using SEO friendly names. So, I’ve added functionality to cache all Flickr photos locally, renaming them using SEO hints from the Flickr descriptions.

It’s still a ways away from being production ready, but I’m really excited about this plugin. It simplifies attribution, and always makes sure the appropriate license is displayed for each photo that is used. In addition, the caption is also pulled from Flickr, saving someone the time of adding one themselves. I also added a fade and a tooltip when a user hovers over a CC photo, hoping that the combination will alert the user to the fact that the photo carries a license with it.

If anyone any suggestions, just let me know. For now, I’ll be testing the plugin on this site. Look for an official announcement over on BraveNewCode when this one is finally released to the public.