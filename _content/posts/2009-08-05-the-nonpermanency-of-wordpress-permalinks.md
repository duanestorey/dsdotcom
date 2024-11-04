---
title: "The Non-Permanency Of WordPress Permalinks"
publishDate: "2009-08-05"
modifiedDate: "2009-08-05"
slug: "the-non-permanency-of-wordpress-permalinks"
author: "Duane Storey"
category:
  - "Journal"
tag:
  - "Permalinks"
  - "seo"
  - "URL"
  - "Wordpress"
---

So, I was lying in bed last night, letting my mind fill up with useless dribble, when I started thinking about WordPress permalinks. I came to the conclusion that the WordPress friendly permalinks aren’t really permalinks in the traditional sense, in that they can be changed after the fact by an end-user.

First, here’s a definition of a permalink:

> A permalink, or permanent link, is a URL that points to a specific blog or forum entry after it has passed from the front page to the archives. Because a permalink remains unchanged indefinitely, it is less susceptible to link rot.

An example of a WordPress permalink is this link, http://www.migratorynerd.com/blog/2009/wheres-duanedo/, which points to my [Where’s Duanedo](http://www.migratorynerd.com/blog/2009/wheres-duanedo/) post.

Why it’s not really permanent is that I can go into the WordPress back-end administration panel and change the structure of that link after the fact, adding the month and/or the day of the week. For example, in about 15 seconds I can change it to http://www.migratorynerd.com/blog/2009/08/01/wheres-duanedo/, which depending on how you feel, might be a bit cooler. Those changes are retroactive, and will change all the links on the site.

Unfortunately, since I’ve given out the old permalink to people, it’s possible that those links may not work anymore (I believe WordPress will do a search under the hood to see if it can find the post, but I’m not entirely certain how well that works).

Anyone who has moved a site knows that you can still make the old permalinks work by doing some .htaccess 301 trickery, but depending on how you involved the changes are you did, that can sometimes be a bit gimmicky.

Really, the only true WordPress links that are permanent are the default links involving the ?p= URL parameters. For example, the true permalink for my Where’s Duanedo post is http://www.migratorynerd.com/?p=4943, which involves the post ID.

I was thinking last night of the consequences of always using the true permalink under the hood, even if friendly permalinks were enabled in WordPress. Truthfully, I think it would work just fine, since WordPress automatically does a 301 redirect from the true permalink to the friendly permalink, the former of which is not susceptible at all to link-rot (as long as the blog address doesn’t change). It adds some header traffic for the 301 redirects, but should always work, even if the URL structure is changed at a later time.

Anyways, some food for thought.