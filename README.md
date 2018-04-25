# Authenticatron

[![Travis CI - Build Status](https://travis-ci.org/eustasy/authenticatron.svg?branch=master)](https://travis-ci.org/eustasy/authenticatron)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/670334725e9240d1beddb0b34f0d8c3c)](https://www.codacy.com/app/eustasy/authenticatron?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=eustasy/authenticatron&amp;utm_campaign=Badge_Grade)
[![Maintainability](https://api.codeclimate.com/v1/badges/9b7ab191d0c7f39b3471/maintainability)](https://codeclimate.com/github/eustasy/authenticatron/maintainability)
[![Bountysource](https://www.bountysource.com/badge/tracker?tracker_id=8106754)](https://www.bountysource.com/teams/eustasy/issues?tracker_ids=8106754)

A simple, procedural PHP script to create Google Authenticator secrets, corresponding QR links and code verification.

Based on the original BSD 2 Licensed work found at [PHPGangsta/GoogleAuthenticator](https://github.com/PHPGangsta/GoogleAuthenticator)

Heavily modified to improve security and suit our needs.



## How it Works

Rather than rely on expensive SMS (text messages) that lack global deliverability, Google Authenticator does not even require a network connection to generate it's codes. Instead, simply scan the generated QR code with your camera, and receive a new, 6 digit second factor of authentication from your phone every 30 seconds.

It does this by generating a 16 character secret, or seed, that is then encoded as a special URL, along with some identifying information, and outputted as a QR code. The phone reads the codes, and the Google Authenticator app runs the secret through a code generation process to output a time-restricted code. The website follows the same process to produce matching codes without actually having to communicate further.



## Potential Flaws & How to Avoid them


### Secret Capture

If you hand off the secret to a service like Google Charts as some demos show, then it would be trivial to compromise the second level of authentication from the start. To cure this, make sure you never send the secret in plain text to the user, or cache images such as the QR code. Instead, output it directly as a base64 encoded PNG, preferably served over HTTPS. [letsencrypt.org](https://letsencrypt.org) gives out SSL Certificates for free.


### Replay Attacks

Quickly re-using an intercepted token to gain access, by taking advantage of the plus/minus one minute rule.

> If a token is not marked as invalid as soon as it has been used an attacker who has intercepted the token may be able to quickly replay it to obtain access.

[Google TOTP Two-factor Authentication for PHP - idontplaydarts.com](https://www.idontplaydarts.com/2011/07/google-totp-two-factor-authentication-for-php/)

To fix this, log used codes and disallow them from being used a second time, at least for double the variation of your codes allowance.


### Brute Force

> If there is no upper limit on the number of attempts a user can make at guessing a token it may be possible to brute-force the one-time token.

> If the seed is too small and an attacker can intercept a few tokens it may be possible to brute-force the seed value allowing the attacker to generate new one-time tokens. For this reason Google enforces a minimum seed length of 16 characters or 80-bits.

[Google TOTP Two-factor Authentication for PHP - idontplaydarts.com](https://www.idontplaydarts.com/2011/07/google-totp-two-factor-authentication-for-php/)

Brute forcing of codes can be fixed in much the same way as brute forcing passwords, primarily with rate-limiting of some kind. Brute forcing of secrets, or seeds, can only be done with intercepted codes. Again, HTTPS is your friend.



## Improvements over [PHPGangsta/GoogleAuthenticator](https://github.com/PHPGangsta/GoogleAuthenticator)

- Procedural over Object Orientated to give faster responses and match [Puff](https://github.com/eustasy/puff-core).
- Fixes time-matching bug for better code recognition.
- Encodes URL to work best with Google Authenticator.
- Removes `rand` in favour of `random_bytes` or `mcrypt` or `openssl_random_pseudo_bytes` for improved security.
- Returns base64 PNG rather than Google Chart to better obscure secret from snoopers.

With thanks to [RebThrees bug report](https://github.com/PHPGangsta/GoogleAuthenticator/issues/11).



## How to Implement

Apart from our earlier warnings about things being intercepted without HTTPS and basic brute-force avoidance (limit attempts), there is very little you must avoid to keep second-factor authentication secure. Don't send the secrets to third parties, but store them yourself (you'll need them every time a user tries to log in), and only allow a code to be used once.

Allow fallbacks. Like password resets, users should be able to bypass second-factor by using their email address. Perhaps send a single use code there, or use the Acceptable function to give them one for two or three minutes in the future. Do NOT allow them to simply turn it off without logging in.



## References
- [About 2-Step Verification - Google](https://support.google.com/accounts/answer/180744)
- [Install Google Authenticator - Google](https://support.google.com/accounts/answer/1066447)
- [Install on Android](https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2)
- [Install on iOS](https://itunes.apple.com/us/app/google-authenticator/id388497605)
- [Install on Blackberry](https://m.google.com/authenticator)
