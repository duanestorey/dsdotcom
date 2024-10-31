---
title: "Wordpress Without Borders, Round Two"
date: "2008-03-29T03:05:20.000Z"
categories: 
  - "journal"
tags: 
  - "ajax"
  - "borders"
  - "conversion"
  - "dynamic"
  - "english"
  - "javascript"
  - "languages"
  - "php"
  - "wordpress-26"
---

Thanks to everyone who left some [feedback the other day about the new translation plugin](http://www.migratorynerd.com/2008/03/wordpress-without-borders/). I've had some really great comments about it, and even the odd person that's really anxious to test it out. I'm hoping to put a version up online tonight for people to download and try for themselves.

For those people who didn't catch it, what I did was write a Wordpress plugin that takes advantage of the Google AJAX language API to dynamically translate the content of my blog into whatever language you as a reader ask for (by adjusting your browser settings). This works in both directions: blog entries will be converted into other languages, and comments left in other languages will be converted as well. So basically, as a reader of this blog you can read it in whatever language you want, and comment in whatever language you want. Check out the comments below for an example.

A couple of the new changes I made are:

- You can now enable and disable the translation via a menu bar that magically appears at the top of the browser window
- Comments will be translated into whatever language you request them to be in
- If there is no direct conversion available between two languages (for example, chinese to french), english is used as a proxy language to assist with the translation
- All menus are in the requested language as well

![](images/picture-38.png)

As you can see in the above photo, a menu will appear at the top of the screen if a translation can be performed on the page. Simply click the link if it appears and it will translate the content for you. The menu will also hover up top as you scroll down the page so you can switch back and forth between translated content.

If you are viewing the content in a different language, the menu will adjust to reflect that. For example, here's how the menu looks on an english site when the viewer has their default language set to chinese.

![](images/picture-39.png)

I encourage everyone to try it out on my blog. If you know a second language, feel free to post a comment in that language. Also, if you have Firefox or any other browser that lets you adjust the language, try switching it to something else and trying it out. If you notice a menu at the top of your browser window, make sure you click on it to see what happens!

## Alpha Version Now Available

If you want to try installing this on your own blog, here's how. First, download the alpha version (this is as alpha as it gets folks) by [clicking this link](http://www.migratorynerd.com/data/noborders.tar.gz). Install it by unzipping the file into your plugins directory and activating it in your menu.

The good news is that it will translate categories and tags for you just like that. The bad news is that comments, content and excerpts require small changes to your theme (I worked hard to try and avoid this, but it's simply not possible if you want it to play nice with the rest of your blog). It's a really easy change, so don't be afraid to try it. Just replace the following:

- replace **the\_content()** with **bnc\_the\_content()**
- replace **the\_title()** with **bnc\_the\_title()**
- replace **comment\_text()** with **bnc\_comment\_text()**
- replace **the\_excerpt()** with **bnc\_the\_excerpt()**

For **the\_title()** only replace those instances that are used to show something to the viewer. That is, if you have **the\_title()** being used to populate an ALT or TITLE tag in a hyperlink, just leave it as **the\_title()**. If you've ever been inside a theme, that should take you no more than 2 minutes to change over.

If you have other content that you'd like to be translated (i.e. things in your menu), you can wrap the text with **bnc\_translate\_start()** and **bnc\_translate\_stop()**. Whatever is between those two functions will be translated along with the rest of the content.

If you manage to get it working on your blog, please drop a comment here so other people can head on over and take a look.
