---
title: "GeoIP Is Not The Right Way To Serve Languages"
date: "2013-10-09T16:35:03.000Z"
categories: 
  - "blogging"
tags: 
  - "geoip"
  - "languages"
  - "survey-monkey"
coverImage: "Screen-Shot-2013-10-09-at-5.25.46-PM.png"
---

I've been traveling around the world for almost three years now. Without a doubt, nothing is more frustrating than trying to access Google, say in Argentina for example, and being presented with a Spanish website. I don't have exact numbers, but I'd say 80% of companies use some form of GeoIP (geo-location using the IP address as a means to determine where you are) to decide which language to serve you.

The problem of course is that even though I am currently in Portugal, _I don't speak Portuguese!_ So using GeoIP to determine where someone lives is fine, but it should never be used as a method to determine what language someone speaks, and many business travellers are often in other countries.

For example, I recently attempted to sign-up for SurveyMonkey, and was immediately sent to their Portuguese website because I am in Portugal. It makes sense if you're a local in Portugal, but I'm not. And as such, it's a big pain in the ass for me to navigate around to try and find something in English.

\[caption id="attachment\_11937" align="aligncenter" width="1002"\]![Survey Monkey, in Portuguese](images/Screen-Shot-2013-10-09-at-5.32.28-PM.png) Survey Monkey, in Portuguese\[/caption\]

If you've set a preferred language in your operating system, almost every browser will present that to a remote website as your language preference. In my opinion, this is the way _every_ website should determine which language to present, since it's the one the user has purposefully requested in their operating system. By all means, use my Geo Location to do something cool, like show me Portuguese Animé characters or something. But please respect the fact that I'm an english speaker who is simply travelling.

Here are the headers that were presented to the website in Firefox when I was browsing. As you can see, the _Accept-Language_ header is set, showing that I want to receive english content **only**.

\[caption id="attachment\_11935" align="aligncenter" width="692"\]![Firefox Live Headers, Showing English As My Primary Choice](images/Screen-Shot-2013-10-09-at-5.25.46-PM.png) Firefox Live Headers, Showing English As My Primary Choice\[/caption\]

So for the love of God, please stop using GeoIP to serve languages. It may work for locals, but you're massively inconveniencing business people who are simply travelling.
