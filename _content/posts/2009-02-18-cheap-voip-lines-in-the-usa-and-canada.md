---
title: "Cheap VOIP Lines in the USA And Canada"
publishDate: "2009-02-18"
modifiedDate: "2009-02-18"
slug: "cheap-voip-lines-in-us-and-canada"
author: "Duane Storey"
category:
  - "Technology"
tag:
  - "DID"
  - "Voice Network"
  - "voice over ip"
  - "voip"
---

I’m always amazed when I see what the larger voice over IP (VOIP) companies are charging these days. Last time I looked at Vonage, I believe they were charging around $30/month, plus an activation fee and equipment fee of around $70 at the start. So while you do get a lot of bells and whistles included in that price (caller ID etc), it’s still rather expensive in my mind.

If you’re looking for something cheaper, and you have an Internet connection at home, here’s what you can do. First, you’ll need either a SIP softphone or an analog telephone adapter (ATA). A popular SIP softphone is CounterPath’s X-Lite which you can grab from [CounterPath’s main website](http://www.counterpath.com). Unfortunately, they only have a Windows version available, so Mac users need another option. At least for me, I’ve never really been a fan of using a headset to make calls on a computer. So instead, I use an ATA at home.

An ATA is a device that connects to a VOIP server over the Internet and also allows you to plug a traditional phone line into it. So while the device is actually doing VOIP under the hood, it seems like a traditional phone line in every other way, especially since you can hook any phone you want up to it. I have a [Sipura SPA 2100](http://www.google.com/products/catalog?hl=en&client=safari&rls=en-us&q=sipura+spa+2100&um=1&ie=UTF-8&cid=9578631485926416859#ps-sellers) at home. One side of it is plugged into my Airport Extreme, and the other side of it is hooked up to my cordless phone.

The nice thing about using an ATA is that you’re free to use it with whatever service you’d like. If you get a Vonage ATA (which comes with the service), it’ll be useless on anyone else’s network. So I’m free to move mine around and pick and choose the best services.

The first thing you’ll need is a DID (direct inbound number). It’s basically a phone number, but in the VOIP world, handles the translation from the phone system into the Internet realm. There are large racks of equipment that do PSTN (public switched telephone network) on one side and rely information over IP (internet protocol) on the other side. So the first order of business is finding a company that will allow you to purchase a DID.

I ended up trying quite a few last year, but ultimately settled on [Voice Network](http://www.voicenetwork.ca/). You can sign up for a free account and simply put money into your account using PayPal. From there, you can purchase DIDs and configure your account. So tonight I went in and purchased a Vancouver based DID.

There are typically two pricing models with DIDs. The first is where all your incoming calls are free, and the second is where your incoming calls are charged by the minute. Outbound calls in both cases are charged typically on a per minute basis (in VOIP terminology an outbound call requires TERMINATION and inbound calling requires ORIGINATION). In Canada, our DIDs are crazy expensive (compared to other countries) for some reason. For an unlimited incoming DID in Vancouver you’ll pay $8.95 per month with Voice Network. For a pay as you go DID in Vancouver, you’ll pay $4.50 per month.

In terms of the per minute charges, they are ridiculously cheap when using VOIP. The per minute rate (and Voice Network charges in 6 second increments) is 1 cent / minute anywhere in North America. So if I do 100 inbound minutes a month and 100 outbound minutes a month, I’ll be looking at a bill of around $6.50. Also included in the price is free voicemail (handled by voice network), called ID, call waiting, etc. The nice thing about VOIP is that you can effectively have multiple calls at the same time and their servers simply sort out the billing. So you could conceivably be using a phone attached to an ATA and talking on it, and also using a softphone on your computer and placing a call. You’d simply be charged 2 cents / minute in that scenario (assuming both phones had placed calls). That feature is typically quoted as the number of CHANNELS available on a VOIP line.

I won’t go into the details about configuring your ATA or Softphone, but it’s fairly straightforward. After you purchase a DID, you’ll ultimately get a set of credentials that you can use to log in (which associates that phone number with your IP:PORT), and you’re basically good to go.

So while Vonage charges $30/month or something stupid, you can easily get a phone number for around $5/month, plus a token amount for usage. Another added benefit is that you can even get a phone number in another part of the world if you want (for example, I used to have a local San Francisco number at home so people in San Francisco could call me for free), or even a 1-800 number at Voice Network. If you live in the United States, the DID situation is amazingly better – an unlimited incoming DID is around $3.95 a month, while a pay as you go DID is only 99 cents.