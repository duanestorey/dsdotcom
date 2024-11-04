---
title: "Apple TV – Nearly Hacked"
publishDate: "2008-06-01"
modifiedDate: "2008-06-01"
slug: "apple-tv-nearly-hacked"
author: "Duane Storey"
category:
  - "Journal"
---

The other day John contacted me and told me about a new hack for the Apple TV. It’s basically an amalgamation of all the available hacks and updates for it, but it’s packaged in a nice, easy to use USB installer.

Before I get into my hacking attempt, I want to give a quick review of what I think of Apple TV so far. To be honest, I really like the idea of what Apple TV is supposed to be. Unfortunately, it’s not really that yet. The main problem is there really isn’t enough content on it. I check back every week, hoping for a massive change in titles, but for the most part there’s just not much to watch. There are so few in fact that Apple has resorted to duplicating the same title multiple times on the page so as to make it seem like they have more.

The main part of the Apple TV hack that appeals to me is the ability to access an external storage drive. With that functionality I can basically rip all my DVDs to my hard drive and watch them whenever I want on my Apple TV.

The actually hacking procedure was really straight forward, so I won’t go into any detail. The hard part for me was making Apple TV talk to my 1TB drive over on my Linux box. I messed around for 90 minutes trying to mount the Linux drive as NFS, but just had so many problems with it I eventually gave up. I then managed to installed an Apple File Protocol (AFP) daemon on the Linux box, which ended up solving the problem. So now I have my Apple TV connected to my Linux box over AFP.

I haven’t played around with this too much yet, so I really have no idea what to do next. I tried streaming a MPEG2 file from my Linux box using the MediaCloud functionality in the hacked Apple TV navigator, but it barfed on the file content for some reason.

Over the next few days I’ll try and figure out some way for my setup to be useful. Check back then.

\*\* Update — actually, the Apple TV NiTo program works well for all content. All the folders it shows are in /Users/frontrow/Movies. So to use it, I just created a symbolic link to my mounted Linux drive, which caused Apple TV to automatically show all that content in NiTo. That’s not as nice as a streaming solution, but it seems to work well enough. I just downloaded one of the m4v files I took at the Junos onto my Linux drive and watched it in Apple TV without problems, so I’m good to go I think. The next step will be to try and get a DVD ripped. Maybe I’ll try that right now.

\*\*\* Update 2 – I ripped a few chapters of Star Wars Episode III to a mp4 file using HandBrake and tossed that on my Linux drive. The quality was actually surprisingly awesome. Unfortunately, the video is a bit choppy in Sapphire for some reason, but seems to work fairly well in nitoTV.