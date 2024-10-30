---
title: "Server Beach Dedicated Hosting"
date: "2009-09-05T06:14:07.000Z"
categories: 
  - "journal"
tags: 
  - "hosting"
  - "rimu-hosting"
  - "server-beach"
  - "servers-5"
---

About a year ago, I decided to slowly migrate away from Media Temple and move to something a bit better. Truthfully, Media Temple's service was one of the worst I've experienced, especially since it's priced at more than double what comparable packages are charging. I still think there's lots of promise with their grid service, but I don't think their paying cliental should be used as guinea pigs on technology.

Unfortunately, I never completely got away from Media Temple, mainly because I have a few things there that need to be moved carefully, and I just haven't had the inclination to do it. But I'm hoping to shut it down completely in a few weeks and stop paying that $20/month.

For the last year or so I've been hosting a few sites at Rimu hosting, which is a company that specializes in VPS systems. The VPS market is a bit strange to be honest. You definitely get more resources than what you'd get from a shared hosting provider, but the downside is your have to manage your site yourself, which can be a pretty big pain. And since you're paying only around $30 - $40 a month for the service, it's hard to justify the cost with putting a web admin panel on it (such as cPanel), since those typically run another $25/month. I know several web hosting companies that stay away from VPS completely, primarily for these reasons. Sure, if you know what you're doing on a Linux box they are pretty fun to have as mini-development servers. But I think their use is limited for most companies.

Between Dale and myself, we probably have close to 30 - 40 sites in various stages that we host on our servers. Most of them are various snapshots of developments that we've completed (or are about to complete), but some of them are live sites that receive daily traffic. Given that we've had them spread between about three or four hosting accounts, we finally decided to take some action and merge them all together.

To that end, we've decided to try out a fully dedicated server for a while. I asked the question on Twitter the other day about good providers, and about 75% of the responses came back for [Server Beach](http://www.serverbeach.com/). So I headed on over there, and priced things out.

I was hoping to stay at or near the $100/month mark, which apparently doesn't get you that much in terms of a machine. Thankfully Server Beach had a sale on for some of their servers, and we managed to pick up a 2.66 GHz dual-core server with 2-160GB drives for around $100/month (discounted from their regular price of $209/month). It has a dedicated 10 mbps pipe with around 3TB of bandwidth a month (basically whatever you can do at full throttle for a 10 mbps connection). Because I'm not the greatest at administering Linux machines, we also tacked on a cPanel interface to the server, which vastly simplifies the management of the server, especially when it comes to things like email.

All in all we'll probably be paying around $125/month for the server, which I think is actually a pretty good deal, especially since I was paying around $40/mo for a resource limited machine at Rimu. Once Dale and I both shut down our Media Temple accounts and I ditch Rimu, the difference for the new server is only about $40/month extra, and gives us a lot more flexibility.

The one item I'm looking forward to the most is having a lot of our web code put into a centralized SVN repository. Hopefully we'll be able to set it up so that Dale and myself have complete copies of the server code on our own machines that we can work on via MAMP, and simply check into the repository from time to time (which gets pushed to our development server). We've sort of been trying to make this work now, but given how everything is spread out and how the credentials for all the databases are different, it's a real pain trying to make it work. Having one server with one master database should make everything a lot easier, and will be far easier to move in the future should we want to change hosts/servers again.

The signup process was fairly painless at Server Beach. Everyone seemed really helpful, and all the emails I received were polite. Unfortunately it took more than 24 hours to provision the server, which goes against their 24 hour guarantee. I actually didn't really care about the time, but I decided to call them on it to see what they'd do. They actually gave us a full month's worth of hosting credits, which is around $125 US, so that was great.

I've spend a bit of time over the last 48 hours moving sites around, and I think I have all my Rimu sites moved over (including this site). I also implemented two backup strategies -- first, I mirror the first drive onto the second once a day, and second, I do a full offsite encrypted dump to Amazon S3. So in the event of a disaster, hopefully we'll be covered.

The only other really big site of ours that needs to be handled delicately is [BraveNewCode](http://www.bravenewcode.com), so we'll probably hold off on that one until near the end. But probably in a few weeks we'll have everything consolidated, which will be a nice change from the norm.

If anyone is thinking about switching hosts and wants to try our Server Beach, feel free to use our referral code: SXHH4BQ82M. I think you'll get a $100 discount after 3 months.
