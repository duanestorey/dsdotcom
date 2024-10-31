---
title: "CoolExcerpt Wordpress Plugin"
date: "2007-04-22T22:47:51.000Z"
categories: 
  - "journal"
tags: 
  - "cool-excerpt"
  - "excerpt"
  - "flickr"
  - "open-source"
  - "plugins"
  - "video"
  - "youtube"
---

A few months ago, I wrote a flickr plugin for wordpress that integrated with a few different image galleries including Lightbox and Greybox. As last count, 126 different blogs were using the crossroads plugin to show their flickr images in their posts.

While updating my blog theme last night, I realized one of the current problems I have with most of the themes I downloaded. Typically, a theme will present the full content of a few blog entries on the main page. A few of the "cooler" themes I've seen only show one post on the main page, which is sort of a neat idea if you don't write very often, but on blogs with multiple entries per day, it becomes easy for readers to miss posts.

A more common approach I've seen on blogs is to show the latest entry in full and also show a few other entries in brief form (what Wordpress calls an **excerpt**). One catch with the excerpt is that it automatically strips out all the HTML code to ensure that the text is brief. Unfortunately for me, since many of my blog entries are photocentric, I lose some of the context about what the blog post is about. For example, if you as a reader were to read the text of an excerpt for a photocentric blog entry, you may not think it was interesting enough to dig deeper, and miss seeing a photo or two. The advantage of excerpts is that you can show more content on your main page without burdening the reader by having to read every entry in full as they progress down your main page.

So, today while sitting at home debating how I was going to fix my door, I decided to write something to address that issue. What I came up with is a new Wordpress plugin which I'm tentatively calling "CoolExcerpt." This plugin scans each excerpt entry and determines whether or not it contains any image or video content and indicates that by displaying related icons underneath the excerpt. In addition, I've added some Ajax functionality to automatically show the full content via an excerpt link without having to reload the whole page from scratch.

If anyone wants to test it out and give me some feedback, [feel free to download the plugin here](http://www.migratorynerd.com/data/cool_excerpt_100alpha.tar.gz). It requires the scripaculous effects library to be loaded before hand, so you must have a theme that uses the library or download the Wordpress scriptaculous plugin.

After activating the plugin, replace each instance of the\_content() with ds\_the\_content() and the\_excerpt() with ds\_the\_excerpt() in your theme files.
