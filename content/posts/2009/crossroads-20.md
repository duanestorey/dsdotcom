---
title: "Crossroads 2.0"
date: "2009-02-21T10:47:44.000Z"
categories: 
  - "journal"
tags: 
  - "crossroads"
  - "photography"
  - "plugins"
  - "wordpress-54"
---

A few years ago I sat down to write my very first WordPress plugin, and ended up writing Crossroads. My goal at the time was to integrate Flickr comments into the normal comments on my blog, which was something I ultimately did. Unfortunately though, some of the limitations of the Flickr API made that feature fairly slow to use, and so it's something I ditched on my own blog a long time ago.

A few days ago I sat down and started working on version 2.0. It's a 100% complete rewrite, which given the state of the old code is definitely something that's going to improve it. I'm writing it with both Flickr and SmugMug in mind, so it will most definitely support both out of the box. I'm also ditching all the old prototype concepts and switching it entirely to jQuery.

I'm still prototyping several concepts, and still don't have that warm, fuzzy feeling with the direction it's going. Part of the problem is having it integrate with WordPress without forcing the user have to make any adjustments to their theme. That's a big constraint, and given the market I'm trying to cater to, I'm not sure that's the best option to be honest.

At least personally, I've never found an image plugin that makes showcasing my photos very easy. I use Flickr RSS fairly religiously, but that only showcases recent photos, so it doesn't help for general blogging. I've always felt like there needs to be a better way to have photos tell stories in addition to the text of a blog entry, and that's something I'm hoping Crossroads will ultimately help with.

One of the shortcomings with the first version was that you had to post photo and set IDs into the post content to have images show up. I'm going to fix that this time and put some kind of administrative interface for selecting photos or albums to be included. So hopefully when it's done it'll be far better than the old version.

Here are a few photos from last year's Northern Voice displayed using Crossroads 2.0. As I mentioned, this is just one of many rough implementations I'm going to do to test things out.

\[crossroads type="flickr" set="72157606640072832"\]

Another complaint I used to get is that you could only have one line of Crossroad photos per post in the old version. Since I have rewritten it, I managed to get that functionality in right from the get-go this time. Here's another group of photos to demonstrate.

\[crossroads type="flickr" set="72157600042953096"\]

For all you photographers out there, drop a comment and let me know what features or functionality you'd like to have on your blog, or any problems you've hit that you'd like solved.
