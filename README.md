# Authenticatron

A simple, procedural PHP script to create Google Authenticator secrets, corresponding QR links and code verification.

Based on the original BSD 2 Licensed work found at [PHPGangsta/GoogleAuthenticator](https://github.com/PHPGangsta/GoogleAuthenticator)

Heavily modified to improve security and suit our needs.



## How it Works

Rather than rely on expensive SMS (text messages) that lack global deliverability, Google Authenticator does not even require a network connection to generate it's codes. Instead, simply scan the generated QR code with your camera, and receive a new, 6 digit second factor of authentication from your phone every 30 seconds.



## Potential Flaws & How to Avoid them


### Secret Capture

If you hand off the secret to a service like Google Charts as some demos show, then it would be trivial to compromise the second level of authentication from the start. To cure this, make sure you never send the secret in plain text to the user, or cache images such as the QR code. Instead, output it directly as a base64 encoded PNG, preferably served over HTTPS. [SSL certificates can be really cheap.](https://www.ssls.com/comodo-ssl-certificates/positivessl.html)


### Replay Attacks

Quickly re-using an intercepted token to gain access, by taking advantage of the plus/minus two-minute rule.

> If a token is not marked as invalid as soon as it has been used an attacker who has intercepted the token may be able to quickly replay it to obtain access.

[Google TOTP Two-factor Authentication for PHP - idontplaydarts.com](https://www.idontplaydarts.com/2011/07/google-totp-two-factor-authentication-for-php/)


### Brute Force

> If there is no upper limit on the number of attempts a user can make at guessing a token it may be possible to brute-force the one-time token.

> If the seed is too small and an attacker can intercept a few tokens it may be possible to brute-force the seed value allowing the attacker to generate new one-time tokens. For this reason Google enforces a minimum seed length of 16 characters or 80-bits.

[Google TOTP Two-factor Authentication for PHP - idontplaydarts.com](https://www.idontplaydarts.com/2011/07/google-totp-two-factor-authentication-for-php/)




## Improvements over [PHPGangsta/GoogleAuthenticator](https://github.com/PHPGangsta/GoogleAuthenticator)

- Procedural over Object Orientated to give faster responses and match [Simplet](https://github.com/eustasy/simplet).
- Fixes time-matching bug for better code recognition.
- Encodes URL to work best with Google Authenticator.
- Removes `rand` in favour of `openssl_random_pseudo_bytes` for improved security.
- Returns base64 PNG rather than Google Chart to better obscure secret from snoopers.
With thanks to [RebThrees bug report](https://github.com/PHPGangsta/GoogleAuthenticator/issues/11).



## References
- [About 2-Step Verification - Google](https://support.google.com/accounts/answer/180744)
- [Install Google Authenticator - Google](https://support.google.com/accounts/answer/1066447)
- [Install on Android](https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2)
- [Install on iOS](https://itunes.apple.com/us/app/google-authenticator/id388497605)
- [Install on Blackberry](https://m.google.com/authenticator)