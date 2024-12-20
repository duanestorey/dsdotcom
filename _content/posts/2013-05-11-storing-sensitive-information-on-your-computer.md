---
title: "Storing Sensitive Information on Your Computer"
publishDate: "2013-05-11"
modifiedDate: "2013-08-01"
slug: "storing-sensitive-information-on-your-computer"
author: "Duane Storey"
featuredImage: "_images/storing-sensitive-information-on-your-computer-featured.jpg"
category:
  - "Technology"
  - "Tips"
tag:
  - "Drive"
  - "Encryption"
  - "TrueCrypt"
---

Other than the odd time when I’ll go down to Future Shop and make a purchase, I do almost all of my shopping online these days. Part of that reason is that I live in a small town that really doesn’t have a great selection for many items. The other reason is that I’m often busy, and so I can simply make an order online and have it show up a week to ten days later.

The same can be said when I travel too. Other than the markets that you find all over the word, and the odd mall, some things on the road are still best to purchase online. A good example of that is when I bought a new DVD drive for my Macbook Air while in Thailand. I simply logged into Thai version of the Apple store and had the item shipped to me in Thailand. Since Singapore is one of their main hubs, it only took two days for that item to get to me.

[![matrix](_images/storing-sensitive-information-on-your-computer-1.jpg)](_images/storing-sensitive-information-on-your-computer-1.jpg)

To facilitate eCommerce, it is often helpful to have your credit card information on your computer in a text file. The problem with that of course is that if anyone gets access to your computer, they can easily get access to all your accounts. If you keep scans of your identify information (such as a Passport, which I often do), then it’s possible for someone accessing your computer to have all of your identify information as well, including where you live.

### Using an Encrypted Drive

To best solution for storing sensitive information on your computer that I’ve found is to use [TrueCrypt](http://www.truecrypt.org/) to store encrypted information on your computer. Basically TrueCrypt creates a file on your computer that when accessed looks and feels like simply another hard drive on your computer. The difference is that a TrueCrypt volume is encrypted with government-grade encryption that cannot be accessed without entering the proper password. And like a hard-drive, you can simply mount or unmount it at will – whenever you mount it, you will need to supply the password, which hopefully only you know.

### Layering TrueCrypt on DropBox

I’ve actually taken this a step further and put my TrueCrypt volume onto DropBox. This means that I can access my encrypted information from any computer I have, and have that information reside up in the cloud. Since the volume is encrypted, it is simply impossible to access that information without knowledge of the password (which you choose when creating the volume).

I basically keep all of my credit card information, personal account numbers, bank transit numbers, scans of void cheques, and copies of my passport inside my encrypted volume. If someone were to steal my computer, and access it before my screensaver lock kicked in, they would be unable to see any of that sensitive information or forcibly access it by pulling the hard-drive out.

Some people may be content simply storing this information on their computer and trusting that the login password is enough, and that’s fair. But I much prefer using TrueCrypt (which is free) to add another layer of security for that type of information, ensuring that there really is no possible way that information can be extracted without knowledge of the password.