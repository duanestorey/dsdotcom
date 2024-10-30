---
title: "Moving IMAP Folders With ImapSync"
date: "2009-11-03T18:14:31.000Z"
categories: 
  - "technology"
tags: 
  - "imap"
  - "mail"
  - "servers-6"
  - "sync"
---

We're in the process of moving some servers around right now, and one of the things we need to do is move mail accounts around. I've been a big fan of IMAP over POP for a few years now, mainly because the messages are kept up on the server, which ultimately keeps every machine looking roughly the same. The unfortunate aspect of that is that when you move servers you have to find a way to get the messages over to their new home.

One of the more obvious ways to move messages is to proxy them through your own personal machine. To do that, you simply need to set up both accounts in a mail program like Apple Mail and start moving things around. You can create a local folder on your machine, and simply drag all the folders from the source mail account into your new folder. Depending on how much mail you have, that may take a while. Once that is done you can simply do the same for the destination account, and hopefully everything will look roughly the same when you're all done.

This time I wanted to try another method, that is using the popular [imapsync](http://freshmeat.net/projects/imapsync/) project. Basically you can give it a source account and a destination account, and it'll do the rest. The main benefit of it is that you can run it on a server, removing the need to proxy anything through your local machine. In addition, it works similarly to rsync -- if a message has already been moved, it'll be skipped the next time. So if the process quits half way through, you don't have to redo all the previously moved messages -- it's smart enough to know what's been moved and what hasn't been.

There's one obvious downside, and that is all the new messages end up with a "Received Date" of today using the second method. That's because the received date isn't a real IMAP field -- it's kept by local mail programs. So while Apple Mail preserves it when it's involved in a copy, doing a server to server synchronization ends up clearing that field. That being said, IMAP has a "Sent Date" field which should in almost all cases match the received date, so you can still tell when the message was sent that way.
