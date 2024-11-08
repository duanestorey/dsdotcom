---
title: "How To Make A Better WordPress – Entry #1: Sitemaps"
publishDate: "2008-02-11"
modifiedDate: "2008-02-11"
slug: "how-to-make-a-better-wordpress-sitemaps"
author: "Duane Storey"
category:
  - "Journal"
tag:
  - "bots"
  - "google"
  - "search engines"
  - "sitemaps"
  - "tips"
---

I’ve decided to do a multipart series on how to take your WordPress installation to the next level. Most people seem to have an out-of-the-box WordPress installation, and I think they are really missing out on a few things that can really improve the quality of their blog.

The first topic I’d like to cover is search engines. Most of you know how a search engine works, but for those who don’t, here’s a really quick primer. Search engines employ little agents called “Bots” that basically roam around the internet taking snapshots of the content. Google’s little guy is called “GoogleBot”, and identifies itself by a unique User-Agent header in all HTTP requests (if you have some kind of data analytics program, you might see GoogleBot show up from time to time).

Whenever a search engine encounters a hyperlink, it checks to see if it’s in its database, and if not, adds to a queue of future web crawls. If you set up a new blog, you can either wait patiently for someone else to link to it (which will cause the Bot to eventually get to it), or you can manually submit it (usually there’s a manual way for most big search engines).

The problem with this method is that unless your site gets indexed very quickly, chances are the Bot may miss some of your content from time to time. So if you write a new blog entry one day, it may be on page 2 or 3 of your blog before each Bot actually visits your site. Since most search engines only do “deep” crawls (these are crawls that actually go deep within your tags and archive pages, as opposed to shallow ones that the Bots do every few days usually) every month or so, that entry may not get indexed at all until then (or ever if it ends up being too deep).

So while most people are content to wait around and let each Bot eventually figure out the layout of your website, there’s a more pro-active approach that you can take. That my friends, is a Sitemap.

Most search engines look for a sitemap on your website that tells the Bot which URLs to crawl. In the site map is a list of pages, and a hint for how often the Bot should travel your website. By creating a sitemap, you basically instruct the Bots which URLs to travel and the frequency they should travel your website. One advantage to this is that entries that have no links pointing to them (or the links are deep within your website) may actually still get indexed.

If you want to see what a sample sitemap looks like, you can check out the one for my site by clicking here: [sitemap](http://www.migratorynerd.com/sitemap.xml).

As you can see, it’s fairly large, and would take pretty close to forever to put one together manually. Thankfully, someone came up with a really great [Sitemap Generation For WordPress](http://www.arnebrachhold.de/projects/wordpress-plugins/google-xml-sitemaps-generator/). If you install it, it will create a sitemap.xml file for your website whenever the content changes.

![Google Sitemap Submission](http://www.migratorynerd.com/wp-content/uploads/2008/02/picture-10.png)

The last step of this exercise is to tell the search engine that there’s a sitemap available. Google recently added a set of [Webmaster tools](https://www.google.com/accounts/ServiceLogin?service=sitemaps&passive=true&nui=1&continue=http%3A%2F%2Fwww.google.com%2Fwebmasters%2Ftools%2Fsiteoverview&followup=http%3A%2F%2Fwww.google.com%2Fwebmasters%2Ftools%2Fsiteoverview&hl=en) that you can log into and submit a sitemap for each site you have. Once you tell Google where it is, it will grab a new copy periodically and update its database. When I first added a sitemap several weeks ago, I only had around 400 out of 1600 URLs indexed by Google. A few weeks later, Google has grabbed 300 more, and it continues to climb.

So that’s WordPress power tip #1. Make sure if you have WordPress that you set up a proper sitemap. And if that power tip helped you, make sure [to head on over and sponsor me for the Big Brothers bowling event](http://my.e2rm.com/personalPage.aspx?SID=1703456) coming up.