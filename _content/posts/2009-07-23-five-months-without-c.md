---
title: "Five Months Without C++"
publishDate: "2009-07-23"
modifiedDate: "2009-07-23"
slug: "five-months-without-c"
author: "Duane Storey"
category:
  - "Journal"
tag:
  - "c++"
  - "coding"
  - "php"
  - "Threading"
---

I spent the last five years pretty much exclusively coding in C++. Strangely enough, I haven’t really touched a C++ compiler since leaving Vancouver, and instead have been pretty much coding non-stop in PHP.

Obviously C++ is a compiled language, and PHP is an interpreted one. In terms of enjoyment, I think I like the challenge of coming up with cool C++ code, but without a doubt, PHP is far more enjoyable overall. The main reason, at least for me, is because I can write some code and instantly see the results of that code in PHP. With C++, you have to compile your code, and even with incremental linking, it can take you a while before you can actually execute your program.

The last C++ code base I worked on was probably on the order of 400,000 lines of code, and made use of about 10 third party libraries. Unfortunately, each compiler has its own idiosyncrasies, the main one in Microsoft Visual Studio being that incremental linking is sometimes skipped whenever any .LIB file changes. That caused a pretty massive linking headache whenever a library changed, causing most developers to update less frequently, and basically go out of their way not to change any libraries.

My C++ experience is pretty in depth — I’ve written many cross-platform multi-threaded programs, and even wrote most of the audio and video portions of a voice over IP engine that was competitive against the voice engine used in Skype at one point, GIPS (in fact, it’s still used by Cisco Systems, British Telecom, and a host of other communications companies). That being said, I probably wouldn’t really miss it at all if I never coded in C++ again. The language is just so complicated that it’s often a pain, and fighting compiler tools all day is a pretty unsatisfying way to spend your time when you’re a software developer.