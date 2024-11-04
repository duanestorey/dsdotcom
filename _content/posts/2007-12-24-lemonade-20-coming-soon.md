---
title: "Lemonade 2.0, Coming Soon"
publishDate: "2007-12-24"
modifiedDate: "2007-12-24"
slug: "lemonade-20-coming-soon"
author: "Duane Storey"
category:
  - "Journal"
tag:
  - "applications"
  - "Around The Web"
  - "facebook"
  - "game"
  - "lemonade"
  - "Technology"
---

Last night while watching TV with my dad, I decided to slowly hack together the start of a new game for Facebook. Inspired recently by The Oregon Trail on there, I thought I’d put another good game from my youth up, namely Lemonade. There’s already one on Facebook that mimics the old version (although, it’s basically a wrapper for a website that already has it), but I have some ideas that will sort of bring the game into the modern age, including a mobile component.

  
![](_images/lemonade-20-coming-soon-1.jpg)  
To be honest, the mathematics behind it all has proven rather interesting. I want the game to be challenging, without easy loopholes to win, and to that end I’ve spent a lot of time mulling over the math in my head. For example, having a weather forecast is fairly useless unless it mimics the approximate success rates of forecasts that we normally have. To that end, the forecasts can’t be entirely random, so I’ve started by modeling them on five state, first-order [Markov chains](http://en.wikipedia.org/wiki/Markov_chain). I might have to take this to the next level (i.e. do a second-order chain or something), but for now I think the results are fairly realistic.

Unlike all the other Facebook applications I’ve done, this one requires a database back-end for accounting purposes and the ability to save and resume games. I’ve got most of the ground work done, so the rest should come together fairly quickly.

If anyone has any cool ideas for this, let me know. I’ll try and finish it off before I leave for Toronto and get people playing. If anyone wants to help test it, drop me a comment!