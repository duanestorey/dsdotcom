---
title: "Raid 5 & Cheap Storage"
publishDate: "2008-03-30"
modifiedDate: "2008-03-30"
slug: "raid-5-cheap-storage"
author: "Duane Storey"
category:
  - "Technology"
tag:
  - "cheap"
  - "drives"
  - "hard drives"
  - "raid"
  - "raid1"
  - "raid5"
  - "storage"
  - "tips"
---

I finally got around to finishing off a little project of mine at home. As everyone knows, I take a pile of photos. So many in fact that I have a hard time finding places to store them all. In the old days I would just make a DVD every few weeks and be done with it, but now that my digital camera is 10.2 MP, I can easily fill up a DVDs worth of photos in a few hours.

Of course, I don’t need to keep all of these, and routinely I don’t. But for the ones I do want to keep it’s getting hard to manage them all. About six months ago I picked up a 320 GB external drive to use for backups and started using that. Now that I have time machine going as well, that drive is pretty much full.

There are various off-site storage facilities I looked into, but many of them become cost-prohibiting once you start talking the amount of data I have. So, since I have a linux box running at home, I decided to make myself a RAID array for storage.

For those of you not familiar with the term, RAID stands for “redundant array of inexpensive drives.” It is a way to organize data such that the risk of your data being destroyed due to a mechanical failure is practically zero.

There are several different ways to do this. One popular technique is known as RAID 1, or drive mirroring. In this system, data is duplicated automatically between two disks. Should one of them fail, you are left with a complete working copy on the other disk. Once you pop in a new drive, most RAID 1 systems (either hardware or software) will rebuild the second drive automatically and put you back into a “safe” state by having duplicate data.

There are several disadvantages to a RAID 1 system. First, the cost of storage is 50%. That is, if you want 500 GB of RAID 1 storage, you have to have 1 TB worth of drives. Secondly, writing to the drives can potentially be slow since data has to be mirrored. On the plus side, reads have the potential to be faster since the controller can read have the data from each drive to help speed things up.

RAID 1 is typically used for mission critical areas such as root operating systems and boot partitions. Since the drives are simply mirrored, there’s no real logic needed to boot the OS from a RAID 1 partition, which is why they are popular for operating system installs. Apple’s Time Machine is also fairly similar to a RAID 1 system, although it’s more like RAID 1 over time.

Next, let’s talk about the bad boy of the RAID group, RAID 5. RAID 5 (sometimes called striping) requires at least three drives to obtain. Instead of duplicating content on each drive like RAID 1, what it does is split the blocks up and adds error correcting information to them. The end result is that in a 3 drive RAID 5 configuration, you can rebuild the entire data set with **any** two out of the three drives. So if a drive fails, you can not only continue to access your data as usual, but you can also simply replace it and have the controller or the operating system rebuild the drive automatically using the data from the other ones. Pretty cool stuff.

![](http://upload.wikimedia.org/wikipedia/commons/thumb/6/64/RAID_5.svg/675px-RAID_5.svg.png)RAID 5, From Wikipedia

As you can see in the photo, the parity blocks (denoted with the subscript p) are distributed over all the drives. In this way, a rebuild is possible if any one particular drive fails.

Doing this at home in the old days would typically have required expensive hardware. But thanks to the falling price of computers and the increasing complexity of operating systems, it’s fairly simple these days. In fact, while my motherboard supports RAID, I purposefully choose to do it in software, since most forum postings seem to think this is the better approach these days (if you use hardware, you’re often relying on proprietary methods for doing RAID, where as if you do it in software, your drives should work with any Linux flavor, not locking you into a particular vendor).

So I went out last week and picked up three 500 GB drives for about $90/ea. I put them into my Linux box in the corner, and configured them as RAID 5. I now seem to have 1 full TB available for all my photos (and probably some movies). If I run out of space, I can simply pop in another $90 drive and the RAID array will adjust itself to compensate. The added benefit is that I no longer have to think about backing anything up (of course, one could argue about needing full offsite backups, but I simple don’t have the time to handle that, and have never, cross my fingers, had an incident that required data offsite).

The first thing I did when it was all configured was to simulate a full drive failure. You can do this by physically removing the drive, but I did it via software. If you mark the drive as dead using the RAID configuration tools, you’ll get notification (via email) that a drive has failed and you are now operating in a degraded mode (since one drive is totally gone, the operating system has to use the error information to reconstruct the data on each read, so this will bog your system down — but at least you’re still running). I then brought the drive back online and added it back into the RAID array. Linux, being the smart beast that it is, immediately started reconstructing the drive from scratch. In about 90 minutes the entire drive was rebuilt, and my data was safe once again.

So, for the cost of around $270, I now have 1 TB of fault tolerant, RAID 5 storage at home. Every drive I bought (and I purposefully picked different drives to avoid batch manufacturing problems) has a five year warranty on it, so the cost of this data is $54/year (neglecting energy costs), or about $4.50/mo (one big Starbucks latte people).

Not bad if you think about it.