# Authenticatron

[![Normal](https://github.com/eustasy/authenticatron/actions/workflows/normal.yml/badge.svg)](https://github.com/eustasy/authenticatron/actions/workflows/normal.yml)
[![Maintainability](https://api.codeclimate.com/v1/badges/9b7ab191d0c7f39b3471/maintainability)](https://codeclimate.com/github/eustasy/authenticatron/maintainability)

A simple PHP script to create HOTP / TOTP / Google Authenticator secrets, corresponding QR links and code verification.

Based on the original BSD 2 Licensed work found at [PHPGangsta/GoogleAuthenticator](https://github.com/PHPGangsta/GoogleAuthenticator)

Heavily modified to improve security and suit our needs.

## Requirements

- A [supported version](https://www.php.net/supported-versions.php) of PHP.
- PHP GD extensions like `php8.x-gd` for QR Code generation.

## Installation

If you already use Composer then the [eustasy/Authenticatron](https://packagist.org/packages/eustasy/authenticatron) package can be easily installed.

```bash
composer require eustasy/authenticatron
```

Require the class in your PHP code:

```php
////    Import eustasy\Authenticatron with Composer
require_once __DIR__ . '/vendor/autoload.php';
use eustasy\Authenticatron;
```

## Quick Implementation

```php
////    Create a new account
// Returns a secret (to be stored) a URL (to be clicked on) and a QR Code (to be scanned)
Authenticatron::new($accountName, $issuer);
//  array(3) {
//    ["Secret"]=>
//    string(16) "6MZYWOOFVAKL7LQB"
//    ["URL"]=>
//    string(83) "otpauth://totp/Example Site: John Smith?secret=6MZYWOOFVAKL7LQB&issuer=Example+Site"
//    ["QR"]=>
//    string(630) "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAJQAAACUAQMAAABP8pKXAAAABlBMVEUAAAD///+l2Z/dAAAACXBIWXMAAA7EAAAOxAGVKw4bAAABZklEQVRIia2WUY7DQAhDuQH3vyU3YP1MN+p33EnVJC9ShgGbSVVV66iZ1W/rM16z3u1pjdWobXDEyvc6MUl7vpRpFv2Pjv0N003pUBJy5nid0lYKnry8ZZTkazx1e8sojYKdGiaiUhOxZvmKWuGWNEQWIqYx3LB8zaPrjA36Lr97WAFpiBiyRpaFiKzMiGEYHkF0LusgYT281AK/CUNGBkjlWN7OScIkH5YPx4vSecYkG6RNd5i5gmXMvRBrY2p8nbGiQdBcB+NQ8IxhExLb7j5oIGN0RAKn5aDOj6dfs7Vf3GAdsXUZMN4odZNMp7M7ZGuIa/DzukcEzD0VFTWTrGNOGBsx5rMc6badMVd4bO6yOEO2t+7d6z63LwSMy/NLnx5dqPeMt6LvfR5m7PbQ67Ff2+hrZp9Qa9qO++xkzDUaWo/101sxOzFeydB4yrx9OtTJGd+Yrrc3gfsGCdiJ3NvKv6sT9gdy9gHcop2cdQAAAABJRU5ErkJggg=="
//  }
```

```php
////    Check a code
// When a code is entered, just retrieve the secret and check them both.
Authenticatron::checkCode($code, $secret)
//  bool(true) - successful auth
//  bool(false) - failed auth
```

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

- Fixes time-matching bug for better code recognition.
- Encodes URL to work best with Google Authenticator.
- Removes `rand` in favour of `random_bytes` or `openssl_random_pseudo_bytes` for improved security.
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
