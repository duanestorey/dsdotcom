---
title: "Entry #23: Acid3 Tests And Web Browsing"
publishDate: "2008-07-26"
modifiedDate: "2008-07-26"
slug: "entry-23-acid3-tests-and-web-browsing"
author: "Duane Storey"
category:
  - "Journal"
tag:
  - "acid3 tests"
  - "blogathon"
  - "browsers"
  - "camino"
  - "firefox"
  - "opera"
---

Anyone who has attempted to design a website before knows that not all browsers are created equal. In terms of web development, a lot of my time goes into making a website work on Internet Explorer 6, since many people still used it even though it is hopeless broken with regards to being standards compliant.

To help make browsers more compliant, several initiates have emerged over the last few years. The first is an effort by the Worldwide Web Consortium (W3C) to have the source code for websites validate against W3C standards. While several exist, most developers attempt to conform to TRANSITIONAL or STRICT XHTML document types in their code.

While that definitely helps cross-browser support, it still does not account for browsers that inherently perform differently. To address that problem, a group of developers came up with the [Acid3 Tests](http://acid3.acidtests.org/). These tests are meant to do an exhaustive measure of a browsers performance in many real-world like situations. Around March of this last year it became a virtual fight amongst web developers to attempt to hit 100% on the Acid3 tests. And while several of them managed to hit that mark, none of those browsers are available to end users yet.

I did a quick test of all the browsers on the Mac last week, and here are the results.

![Safari](_images/entry-23-acid3-tests-and-web-browsing-1.jpg)

Safari 3

![Firefox](_images/entry-23-acid3-tests-and-web-browsing-2.jpg)

Firefox 2

![Firefox 3.0](_images/entry-23-acid3-tests-and-web-browsing-3.jpg)

Firefox 3.0.1

![Opera](_images/entry-23-acid3-tests-and-web-browsing-4.jpg)

Opera

As you can see, the best browser on the Mac (with regards to Acid3 test compliance) is Opera, which I actually found a bit surprising (thinking that it would obviously be Safari). Hopefully most of these will converge on 100% as they continue doing updates. If you want to test the score of your current browser, feel free to head on [over to the Acid3 tests](http://acid3.acidtests.org/). If you do, post a comment and let everyone know what browser you were on, and what score it achieved.