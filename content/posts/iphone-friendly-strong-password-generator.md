---
title: "iPhone Friendly Strong Password Generator"
date: "2013-10-01T15:01:59.000Z"
categories: 
  - "tips"
tags: 
  - "iphone"
  - "password"
  - "strong"
coverImage: "tracking-code.png"
---

I was recently generating some new server and email passwords, and realized that while most of the strong password generators available online work great for desktops, they aren't very user friendly for entering on iOS or mobile devices. Often you can find a few checkboxes and sliders for those tools to make it a bit more iPhone friendly, but they don't always work. Ideally a password that you frequently use on an iPhone or other iOS device should be "strong" (i.e. have possibly a symbol or two and a mix of upper and lower case characters), but should also be fairly easy to type.

So this morning I decided to write a bit of PHP to generate an iOS friendly strong password. Basically the idea is to only use mostly keys that are accessible without having to hit shift or the keyboard button that switches to symbol mode.

```
function generate_password( $length = 10 ) {
        // Enforce a minimum length of 10 symbols
        $length = max( 10, $length );

        $password = '';
        $available_symbols = array();

        // These only require one tap per symbol
        $available_symbols[] = 'qwertyuiopasdfghjklzxcvbnm';

        // These require two taps per symbol
        $available_symbols[] = 'QWERTYUIPASDFGHJKLZXCVBNM1234567890!$?';

        // These require three taps per symbol
        $available_symbols[] = '[]{}#%^*+=_/|~<>?!';

        // Remove this next line if you only want mostly easy-to-type passwords
        $password .= $available_symbols[2][ mt_rand( 0, strlen( $available_symbols[2] ) - 1 ) ];

        // These two require two keypresses each, but make the password much stronger
        $password .= $available_symbols[1][ mt_rand( 0, strlen( $available_symbols[1] ) - 1 ) ];
        $password .= $available_symbols[1][ mt_rand( 0, strlen( $available_symbols[1] ) - 1 ) ];

        for ( $i = 0; $i < $length - strlen( $password ); $i++ ) {
                $password .= $available_symbols[0][ mt_rand( 0, strlen( $available_symbols[0] ) ) - 1 ];
        }

        return str_shuffle( $password );
}
```

Basically I've included only one symbol that uses three taps to produce, two symbols that use two taps to produce, with the remainder being lowercase symbols that only take one tap to produce. That way these passwords are still essentially strong, but also minimize the number of taps it takes to reproduce them on mobile devices.

I removed a few symbols that were a bit ambiguous, and removed a few others that just impeded the readability of the password. I'm sure this code can be simplified, but I purposefully made it easy to read as opposed to really efficient.

This page is purposefully not cached, so if you are looking for an iPhone or iPad friendly strong password, you can use one of these:

- Password 10 Symbols Long: \[strongpw length="10"\]
- Password 12 Symbols Long: \[strongpw length="12"\]
- Password 14 Symbols Long: \[strongpw length="14"\]

If you find this useful, let me know.
