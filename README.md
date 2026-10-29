# Authenticatron

[![Normal (PHP)](https://github.com/eustasy/authenticatron/actions/workflows/php.yml/badge.svg)](https://github.com/eustasy/authenticatron/actions/workflows/php.yml)
[![Normal (Security)](https://github.com/eustasy/authenticatron/actions/workflows/security.yml/badge.svg)](https://github.com/eustasy/authenticatron/actions/workflows/security.yml)
[![Test (PHP)](https://github.com/eustasy/authenticatron/actions/workflows/test-php.yml/badge.svg)](https://github.com/eustasy/authenticatron/actions/workflows/test-php.yml)
[![Maintainability](https://qlty.sh/gh/eustasy/projects/authenticatron/maintainability.svg)](https://qlty.sh/gh/eustasy/projects/authenticatron)
[![Code Coverage](https://qlty.sh/gh/eustasy/projects/authenticatron/coverage.svg)](https://qlty.sh/gh/eustasy/projects/authenticatron)

A simple PHP script to create HOTP / TOTP / Google Authenticator secrets, corresponding QR links and code verification.

Based on the original BSD 2 Licensed work found at [PHPGangsta/GoogleAuthenticator](https://github.com/PHPGangsta/GoogleAuthenticator)

Heavily modified to improve security and suit our needs.

## Requirements

- PHP >= 8.2
- `ext-gd` (e.g. `php8.x-gd`) for QR Code generation.
- `ext-mbstring`, required by the QR Code library.
- [`chillerlan/php-qrcode`](https://packagist.org/packages/chillerlan/php-qrcode) 6.x — installed automatically via Composer.

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
//    string(1622) "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAKQAAACkCAIAAACW1xIWAAAACXBIWXMAAA7EAAAOxAGVKw4bAAAEYUlEQVR4nO2dwW4EOQgFd1b7/7+cvXYOSCB4PZGq6jjpxp48IcY24M/Pz88/wuDfb09A3kOxQSg2CMUGodggFBuEYoP4r/rD5/M5H6xa03fGer579fx0j+Fpp/NdOvbf/D/r2SAUG4Rigyhj9pPN/vk0vlbvTuNlx371bvX89HfDlPT/Wc8GodggFBtEK2Y/2cTghM0q1k7Xu9P5bOZ2NYepTT0bhGKDUGwQ45h9RSfWJmx21tAb+9N330TPBqHYIBQbxNdidoerWL45R+/Y+da59RQ9G4Rig1BsEOOYfbVGTOeUJfLa3iSxFtezQSg2CMUG0YrZiXg23Wfe7Esnnv8L5+hT9GwQig1CsUF8/kJPlXRO+MZOIgZ7ni1xFBuEYoMY12dfnd1uYuTVPvkmj6xjJ1G7tbGpZ4NQbBCKDWJcnz3do+7EmISd6Vjp73L1W2czNz0bhGKDUGwQ5d74pudXOv8rsbc8PZ/erLkTn1dzeKJng1BsEIoNolxnp9fHib4lm3eveqVNv9ebddt6NgjFBqHYIM76jV/ljl3lo037nnbmkO7XNp3ndG56NgjFBqHYIFY9Va72zDfnuJu5bewneptf5dNV6NkgFBuEYoMYn2dvzqo3n1dz63AVRzd5ZG/mDXieLYpNQrFBnPVU2eRMXa2zE+fBU5uJOu+rfm16NgjFBqHYIFY9VTbr3fQZcyIfu2P/6vnOu9P/p54NQrFBKDaISA7aVe+w57vT/iqJ/O2O/atc+qv1/RM9G4Rig1BsEPE7QqYxuGNnQ7qn97Q/+ZN0vNezQSg2CMUGMe6DNuXqnHuTn3V1HpzOQZuONUXPBqHYIBQbxDgHbdOj+yp2PrmK/dXz1VjT569quzvPV+jZIBQbhGKDaNV6TbnKa7vKNavsVyT6pk3td+Y2nY+eDUKxQSg2iLP67HSdd2WzQyJfvfP8FVf/Nz0bhGKDUGwQrVqvdP5zuv9XZ9x0r+83z9GN2aLYJBQbROT+7Kva6E2c+wu/G67i9NVvCz0bhGKDUGwQq37jFYn88Om4nc83cfHq3Sn2G5cWig1CsUGMa73S9VqdzxP9Rqpxq5ryqzh9Vbft3rj8QrFBKDaIVU+Vq3s+0mvTKZu97vSeQYXrbPmFYoNQbBDjmH21Lpza3PCtu7k2/VOrcb0jRFooNgjFBrG6I2Q18Iv3cCRy0K7u/NjMZ4qeDUKxQSg2iLM7Qjps7vlI3P3VmeemHrqaW/X51Xf0PFsUm4Rig4j3G79ag75Ze9bhzTV39bx90KREsUEoNojVeXbFt/Ko0/eIXNWRJ+rOXWfLLxQbhGKDiNRnT5nG6Y6dzVhX76Z7qkzt69kgFBuEYoP4EzF7sx9+1d/0zbtDppg3LmMUG4Rig1jVZ29InHNPe49Mx+o8v9mH3/wm6HwvPRuEYoNQbBDj+7Pf5KpvWmUzcXfnVY+2xNpdzwah2CAUG8TX6rPlffRsEIoNQrFBKDYIxQah2CAUG8T/Kk6siEF0bCoAAAAASUVORK5CYII="
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

Rather than rely on expensive SMS (text messages) that lack global deliverability, Google Authenticator does not even require a network connection to generate its codes. Instead, simply scan the generated QR code with your camera, and receive a new, 6-digit second factor of authentication from your phone every 30 seconds.

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
>
> If the seed is too small and an attacker can intercept a few tokens it may be possible to brute-force the seed value allowing the attacker to generate new one-time tokens. For this reason Google enforces a minimum seed length of 16 characters or 80-bits.

[Google TOTP Two-factor Authentication for PHP - idontplaydarts.com](https://www.idontplaydarts.com/2011/07/google-totp-two-factor-authentication-for-php/)

Brute forcing of codes can be fixed in much the same way as brute forcing passwords, primarily with rate-limiting of some kind. Brute forcing of secrets, or seeds, can only be done with intercepted codes. Again, HTTPS is your friend.

## Improvements over [PHPGangsta/GoogleAuthenticator](https://github.com/PHPGangsta/GoogleAuthenticator)

- Fixes time-matching bug for better code recognition.
- Encodes URL to work best with Google Authenticator.
- Removes `rand` in favour of `random_bytes` for improved security.
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
