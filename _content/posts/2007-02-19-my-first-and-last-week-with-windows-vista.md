---
title: "My First And Last Week With Windows Vista"
publishDate: "2007-02-19"
modifiedDate: "2007-02-19"
slug: "a-week-of-windows-vista"
author: "Duane Storey"
category:
  - "Technology"
tag:
  - "microsoft"
  - "operating system"
  - "vista"
  - "windows"
  - "xp"
---

I’m officially going to blog about my frustration with Windows Vista so far. If you were following last week, I decided (stupidly) that I would upgrade my laptop from windows XP to windows Vista. First, the whole upgrade procedure [took on the order of four hours](http://www.migratorynerd.com/archives/276), and it wasn’t exact smooth all the time. The progress meters were hopelessly broken, and the pre-check that it does to make sure you can actually install vista properly got it wrong.

One thing that bothers me right away is that you can no longer install Vista on the same partition as another OS. I’m not really sure why this is, because with Windows XP, you could do this. For example, you could have one version of XP in C:\\windows, and another in C:\\winxp. What’s funny is this feature is really only useful for an operating system that you have to reinstall periodically, which is what XP was. But, if you had to do a reinstall, it was usually easier to put a new version in a different folder, slowly bring it up to working condition, and then toast your old version.

Unfortunately, Vista got rid of this. If you want to have it on the same machine as XP you basically need to make a new partition and put it there. Of course, Vista doesn’t come with any partition program to do this for you, so if you want to make this happen you have to shell out some money for something like Partition Magic which you can then use to resize your current partition and create a new one.

After four hours, I had Windows Vista installed, and I immediately regretted it. The machine I upgraded was a dual core 1.67 GHz Intel machine with 1 GB of ram and a pretty decent ATI graphics card. I would have thought that Vista would be able to run fine on it. However, it runs really sluggishly. For whatever reason, Vista is using around 85% of my physical memory, which means whenever I do anything, I’m probably causing page faults and causing the memory manager to swap stuff to disc. This really sucks because I don’t really feel like shelling out $300 to upgrade the RAM on my laptop.

Also, I can’t really tell why it took five years of work. Sure, Microsoft added the Aero windowing manager which is similar to the Mac OSX windowing system. However, it’s definitely not as nice. In fact, for the most part, you can’t even really tell it’s a 3D windowing system unless you press Windows-Key and Tab, which cycles through your active programs in a 3D view. Other than that, nothing else really indicates that this is 3D except for some subtle transitions and some translucency (which you could have had in Windows XP using layered windows). The windowing manager is also a big memory hog, which is surprising since I would have thought most of the memory it would use would be in the video card itself, not in physical RAM. I have a sense that they weren’t quite done cooking the graphical system when they launched.

Here’s another problem I have with Microsoft — how hard is it to make the task manager do what it’s supposed to do? How many OSes have they released already? Windows 2.0, 3.0, 3.1, 98, ME, XP, 2000, 2000 Server — and still, ***task manager runs at a relatively low priority***. How the hell am I supposed to kill a misbehaving process if I can’t get task manager to come up while it’s misbehaving? I can’t believe this is that hard. Also, if I do manage to bring task manager up and click “End Process”, it doesn’t always kill it right away.. Sometimes you have to wait 30 seconds or more while Vista calculates away trying to figure out how the hell to actually get rid of the process. Microsoft. Here’s an idea for what “End Process” should do.

1. Find the the scheduling table in memory
2. Find the process that corresponds to the one I just decided to end
3. Remove process task information from the scheduler

That’s it. Process now gone. When I click “End Process”, I don’t want it to gracefully shutdown. If I wanted that, I would have clicked that little X up in the top corner or gone “File/Close”.. But those fucking things didn’t work, so I got no other options here. I want that thing gone — now. I want what Linux gives you with “kill -9”.

What else sucks? Explorer seems to randomly hang all the time, at which point you’re left with an unresponsive process that says “Not Responding” in the title bar. In fact, Photoshop now says that all the time, even if I launch it from a clean reboot. If I go clean my kitchen or something, sometimes Photoshop is responsive when I get back, but not always. My first attempt to launch some movies didn’t work at all. If I double-clicked an AVI file, it would say “launching windows media player”, and then do absolutely nothing. After about ten reboots this problem has magically gone away for some reason.

Let’s talk about the new security model in Vista for a sec. They’ve tried to make this work alot better, but the problem is that they’ve also tried to remain backwards compatible with a whole bunch of software that was written assuming it had administrator privileges. This has created some weird half-breed of a security model that is more annoying than useful. Here are some examples. I use OpenVPN to connect to my work network when I’m at home. That broke immediately upon installing Vista. It appears that it needs to spawn a command prompt and update the routing tables within the kernel. Unfortunately, those commands require elevated privileges which OpenVPN doesn’t have by default, and for whatever reason, Vista doesn’t prompt me for them. I imagine I can somehow fuck around with some manifest file or something to make this work, but what a pain in the ass. Also, I opened a command prompt today and tried to flush my DNS cache while working on my web page. That requires elevated privileges too, which for some stupid reason, you can’t get while you’re in a DOS prompt. So, you have to muck around in the start menu with some cleverly placed right clicks to finally get a command prompt with the properly security levels.

  
  
How about the media stuff? Well, as a voice over IP person, I was really excited about the promise of tickless audio that the Vista sound engineers promised years ago. So, I fired up a few voip applications when I got everything worked and guess what.. Tick. Tick. Tick. Better luck next time. At least the sound mixer is a lot cooler. Instead of simply WAVE and MAIN, you actually get a proper slider for each application that uses audio. So finally, I have my music at a certain level and my voip calls at another level. They get a star for this one. Unfortunately, iTunes currently doesn’t work with Vista (something I found out after the fact), so none of my purchased iTunes music works anymore.

So.. Am I happy with Vista? Nope, not at all. It’s clearly not ready. Maybe me performing an upgrade has made this far worse than it would have been if I had done a clean install, but why offer an upgrade mechanism if it’s this shoddy? If you’re gonna require a separate partition for a clean install, then please include some kind of partition manager on the installation CD. It’s not really fair that consumers have to go spend another $50 program to pull this off. Also, 1 GB is arguably not enough memory these days, but it’s not exactly that bad either. So I’m pretty disappointed that it doesn’t run properly on my machine. Am I going to spend $300 and get more RAM, hoping that it gets better? I doubt it. Maybe I’ll just start saving for that Macintosh computer I’ve always wanted.