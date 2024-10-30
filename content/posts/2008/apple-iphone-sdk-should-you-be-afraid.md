---
title: "Apple iPhone SDK - Should You Be Afraid?"
date: "2008-03-10T06:47:43.000Z"
categories: 
  - "journal"
tags: 
  - "apple-all-things-mac"
  - "carbon"
  - "cocoa"
  - "development"
  - "iphone"
  - "ipod-touch"
  - "sdk"
---

Going through Google reader tonight, I've encountered a lot of shared articles about the Apple iPhone SDK. And for the most part, every one of the articles praises the SDK and Apple's approach with it.

[![](images/2170718888_75bb3e63d9.jpg "Apple iPhone")](http://www.migratorynerd.com/2008/03/apple-iphone-sdk-whats-the-big-deal/2170718888_75bb3e63d9/)Photo by [John Biehler](http://johnbiehler.com)

But in pure Office Space style, I'm going to have to go ahead and, you know, sort of disagree with you all. In particular, I'm going to outline a few points from [one specific article](http://mobileopportunity.blogspot.com/2008/03/iphone-sdk-apple-gets-it-right.html) and give my take as a cross platform application developer who actually \*has\* developed software from scratch on the Mac using both Carbon and Cocoa.

First point:

> Overall, it is deeply impressive how many things Apple got right. We still need to see more details on terms and conditions, and a lot will depend on Apple's execution, but here are the problems they appear to have solved:
> 
> \--Mobile applications are hard for users to find and install, so Apple is building the applications store into every device. Apps are installed automatically when you buy them, and you can also be notified of upgrades when they're available.

Well, my iPod Touch is currently unlocked, and there's a whole open-source mechanism in place to view, download, install and upgrade applications, all for free. So why do I need a store that charges money to take care of that for me?

> \--Third party applications stores take far too much of a developer's revenue -- 60% or more. So the Apple store takes 30%. That's a bit high (20% would be better), but everyone else has been so greedy that Apple looks like a charity.

Honestly, if you can write a software application from scratch, you can sure as hell write a PHP script to send someone a download link when they purchase your application via PayPal for 10%. I realize Apple iTunes may make finding the applications a little easier (and I'll only buy this argument for \*commercial\* applications, since as I pointed out above, there's a whole system in place already for distributing free apps on the iPhone and iPod touch), but seriously, not every developer has been forced to be gouged by online stores.

> \-Getting applications certified for use on mobiles is expensive and time-consuming, so Apple has streamlined the process dramatically. Developers pay $99 a year, and apparently get automatic certification of all their apps. We need to learn more about how the app approval process will work, but if it's not burdensome this service alone justifies Apple's 30% cut of revenue. Apple takes responsibility for ensuring that iPhones remain secure and do not abuse the network, something that no one else has been willing to do.

Automatic certification is not equivalent to the type of certification you typically pay for when you do something like a Microsoft certification. Having experienced some of those, they take lots of time, and are extremely exhaustive when it comes to security, memory leaks, program function, etc. I'm not going to make a definitive statement here, but I seriously doubt Apple is going to exhaustively test every application for $99 a year. My understanding is that cost basically only gives you the ability to digitally sign your application and have it hosted on the iTunes store.

In terms of the last point, due to the nature of the iPhone and the restrictions on the API (and the glaring terms of service which say that as a developer you can't use any undocumented feature), I actually think it would be fairly difficult to do anything that abuses the device or the network (especially since parts os using Edge are already off limits).

Another thread on various blogs is how the Apple iPhone SDK will potentially limit enterprise apps. Since you basically have to digitally sign your applications and have to use iTunes to distribute it, there's a huge hole for the applications that are enterprise related but contain features not meant to be public, such as access to proprietary or private APIs and/or services. Do you have to use iTunes for those applications, or can you distribute them privately? If it's the later, do you really need to digitally sign those applications? Some big questions here.

There's a point I want to bring up that most people seem to be over looking. In a world where open source has become (or is in the process of becoming) one of the most dominant software distribution models (MySQL, Drupal, Wordpress, Android, etc), the reliance of the iTunes store and the requirement to digitally sign your applications (using a certificate provided by iTunes for a price) **effectively translates into the software equivalent of DRM**, and ultimately may lead to the same type of restrictions on software that Apple now enjoys thanks to Fairplay on music. And while most people only have one iPhone or one iPod touch today, it doesn't take much imagination to envision changes to Apple TV or other Apple products which may ultimately require you to purchase a separate version of each application for every device you want to use it on.

In software, it is generally considered a privacy violation to have your application "call home" periodically and report its status (in fact, many software projects have gone under for being flagged as Spyware for doing that very action without properly informing the user). Some applications bury information about this behavior in the fine print of the privacy policy (that thing everyone says yes to when they install stuff) or the terms of service, while others disguise it as a "check for updates" option, both of which give the developer of the application the means to see which users are using their software and also information (even in terms of their location based on IP address) about their users. In term of the latter, most applications allow you to disable checking for updates for privacy reasons. Now that the software distribution and upgrade process will be integral to iTunes, and iTunes is already required to synchronize these devices, there's the potential to have the system automatically expose information on an application level that most users would typically have the ability to disable with normal standalone applications.

That's not to say I don't realize there are pluses to having an application with mass appeal on iTunes so that everyone can download it and purchase it virally, but I'm starting to get a little worried about the closed world Apple is carving for itself. At what point do they become a huge monopoly like Microsoft was in the 90s, where the only consumer friendly option is to attempt to break them apart?

More evidence that Apple is trying to lock development down and keep others from playing in its sandbox can be found [in this information week article](http://www.informationweek.com/blog/main/archives/2008/03/iphone_sdk_deve.html):

> Turns out, though, that developers are limited in what iPhone functions that can tap for the apps they're building, according to Adam Houghton's post on his eponymous blog. Houghton characterizes as a "glaring" omission his discovery that developers can't access calendar appointments, music and videos from the phone's iPod app, nor phone and SMS functionality.
> 
> While the phone-function and SMS lockdowns are, as Houghton notes, likely due to security, one can't help but think that the other restrictions are because Apple wants to keep all the really good stuff to itself.

I couldn't agree more. In terms of the actual SDK itself, I've taken a look at it, and except for the Locations API, doesn't really provide a whole lot extra from the normal Apple desktop frameworks provide (and in fact, has some stuff removed).

So while I think every serious Apple developer needs to take a look at the iPhone SDK, I think Apple is bringing people further inside their closed little world with the iPhone SDK. As a developer and a consumer of Apple products, I am starting to get a bit worried.
