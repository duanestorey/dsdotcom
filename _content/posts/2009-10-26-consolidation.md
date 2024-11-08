---
title: "Consolidation"
publishDate: "2009-10-26"
modifiedDate: "2009-10-26"
slug: "consolidation"
author: "Duane Storey"
category:
  - "Technology"
tag:
  - "Dropbox"
  - "hosting"
  - "Shared"
  - "Wordpress"
---

This weekend was pretty uneventful. I wasn’t feeling overly ambitious, so I hung out close to home mostly and rented a few movies. I’m not sure if there really are no movies, or if Apple TV is exceptionally crappy right now, but I hardly found anything to watch at all.

Back in [September](http://www.migratorynerd.com/blog/2009/server-beach-dedicated-hosting/) I wrote a post about switching to [Server Beach](http://serverbeach.com). For the most part, it’s been pretty rock solid. We have a dual core server there with cPanel on it, and it’s been relatively pain-free. One of the reasons we opted for a dedicated server was to make our lives a lot easier. Prior to setting it up, Dale and I realized we had about four or five hosting accounts combined, many of which had old sites on them. Unfortunately, keeping everything running smoothly in that scenario is a challenge, especially when you’re also trying to develop (and backup) sites for clients. So, we decided to migrate everything onto one server.

We’re about 80% of the way now, and have shut down a few hosting accounts already (including my old Rimu hosting VPS). I’m about to finally pull the plug on my Media Temple account as well, which I’ve been trying to do for about six months now.

One of the two tools Dale and I use fairly religiously is [DropBox](https://www.getdropbox.com/referrals/NTI2NzQ3Mjk5). DropBox allows Dale and I to share a virtual hard drive up in the cloud. Whenever either of us updates a file, it automatically synchronizes the other person’s machine. Last night I also found a Linux based client for it, so I set that up on our server and tied it into Apache. That means Dale and I can basically modify live sites just by dragging files into folders on our local machines. Conversely, I configured some of our backup scripts to store various files (such as MySQL backups) in DropBox as well, which means Dale and I both automatically get server backups on our local machines every night. I think it works pretty slick actually, and I think we’ll be able to do a few more cool things with that setup.

My personal goal is to make it so that developing a site on my local machine is nearly identical to developing a site on a remote machine. Having everything on one server facilitates that quite nicely. For example, the Server Beach MySQL server is configured as a master, and my personal laptop machine is configured as as MySQL slave. So whenever I do a posting to duanestorey.com (which resides on Server Beach), the database is automatically synchronized with my laptop. The next step will be to synchronize the files (through some combination of SVN and DropBox), at which point I’ll have pretty much an exact replica of the live sites on my laptop (and so will Dale hopefully), which means I can develop without an internet connection, and simply synchronize everything to the live server whenever I’m plugged in.

So, hopefully by the end of this week we’ll have shut down all our other hosting accounts. If anyone is considering moving to Server Beach, feel free to use our referral code: SXHH4BQ82M. I believe you’ll save $100 on your first month’s bill, and we’ll also get a kickback to help us out as well.