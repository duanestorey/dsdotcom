---
title: "Wordpress Media Burner Plugin"
date: "2008-02-03T10:20:18.000Z"
categories: 
  - "journal"
tags: 
  - "around-the-web"
  - "blogging"
  - "open-source"
  - "programming"
  - "rss"
  - "wordpress-18"
---

Tonight I had a few people over at my place to watch movies, drink some beer, and hack out a few techy type things. My little project for the evening was to try and media-ify my blog somehow. One of the ideas I was playing with was extracting all the media out of all my blog posts and having that in a separate player somewhere. There are other players for Wordpress (based on the popular flash FLV player) that do something similar, except with those you have to manually input a playlist or set up a special directory.

[![](http://farm3.static.flickr.com/2275/2238142823_8e6650bf19.jpg?v=0)](http://flickr.com/photos/duanestorey/2238142823/)A snapshot of the media player

The main benefit of the way I've done it is that it's just business as usual for you, the blogger. The plugin will parse all your entries and pull out the media you normally include anyways. It generates a site-wide RSS feed all that media which can then be used to feed a playlist for a media player.

It took a little bit of sorcery to get the youtube videos to play in it (since youtube probably doesn't want you to do this), but the end result is pretty cool I think. I had to reverse engineer the FLV player to figure out how to get m4a audio files to play in it (since the FLV documentation doesn't indicate it supports it), but I seem to have figured it out.

You can check out the RSS feed at [this location](http://www.migratorynerd.com/media/feed). If you want to mess around with the player, click "Media" up in the top menu. If anyone is running Wordpress and wants to take it for a spin, drop me an email and I'll send you what I have working right now. It will be a bit slow for any large sites, and I'll have to add caching before I release it.
