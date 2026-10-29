<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
use eustasy\Authenticatron;
use PHPUnit\Framework\TestCase;

final class AuthTest extends TestCase
{
    // Test that the secret is 16 characters long
    public function testSecretLength(): void
    {
        $secret = Authenticatron::makeSecret();
        $this->assertEquals(16, strlen($secret));
    }

    // Test that a custom length secret is the correct length
    public function testSecretCustomLength(): void
    {
        $secret = Authenticatron::makeSecret(32);
        $this->assertEquals(32, strlen($secret));
    }

    // Test that the secret is a string
    public function testSecretType(): void
    {
        $result = Authenticatron::makeSecret();
        $this->assertIsString($result);
    }

    // Test that the secret contains only valid Base32 characters
    public function testSecretChars(): void
    {
        $result = Authenticatron::makeSecret();
        $this->assertMatchesRegularExpression('/^[A-Z2-7]+$/', $result);
    }

    // Test that two generated secrets are not identical
    public function testSecretUniqueness(): void
    {
        $this->assertNotEquals(Authenticatron::makeSecret(), Authenticatron::makeSecret());
    }


    // Test that the URL is a specific string
    public function testUrlFormat(): void
    {
        $result = Authenticatron::getUrl('accountName', 'secret', 'issuer');
        $this->assertEquals('otpauth://totp/issuer: accountName?secret=secret&issuer=issuer', $result);
    }

    // Test that special characters are stripped from the URL
    public function testUrlStripsSpecialChars(): void
    {
        $result = Authenticatron::getUrl('account:Name', 'secret', 'issu:er');
        $this->assertEquals('otpauth://totp/issuer: accountName?secret=secret&issuer=issuer', $result);
    }


    // Test that the QR code is a string
    public function testQrCodeType(): void
    {
        $url = Authenticatron::getUrl('accountName', 'secret', 'issuer');
        $result = Authenticatron::generateQrCode($url);
        $this->assertIsString($result);
    }

    // Test that the QR code is a PNG data URI
    public function testQrCodeDataUri(): void
    {
        $url = Authenticatron::getUrl('accountName', 'secret', 'issuer');
        $result = Authenticatron::generateQrCode($url);
        if (! is_string($result)) {
            $this->fail('Expected generateQrCode() to return a data URI string.');
        }
        $this->assertStringStartsWith('data:image/png;base64,', $result);
    }

    // Test that the QR code scans back to the exact URL it was made from
    public function testQrCodeDecodesToUrl(): void
    {
        $url = Authenticatron::getUrl('Member Name', Authenticatron::makeSecret(), 'Example Site');
        $result = Authenticatron::generateQrCode($url);
        if (! is_string($result)) {
            $this->fail('Expected generateQrCode() to return a data URI string.');
        }
        $png = base64_decode(substr($result, strlen('data:image/png;base64,')), true);
        if (! is_string($png)) {
            $this->fail('Expected the data URI to hold valid base64.');
        }
        $decoded = (new \chillerlan\QRCode\QRCode())->readFromBlob($png);
        $this->assertSame($url, $decoded->data);
    }

    // Test that $Size and $Margin change the image as before: pixels per module, and modules of margin
    public function testQrCodeSizeAndMargin(): void
    {
        $url = Authenticatron::getUrl('accountName', 'secret', 'issuer');
        $small = Authenticatron::generateQrCode($url, 2, 0);
        $large = Authenticatron::generateQrCode($url, 4, 2);
        if (! is_string($small) || ! is_string($large)) {
            $this->fail('Expected generateQrCode() to return data URI strings.');
        }
        $smallSize = getimagesizefromstring((string) base64_decode(substr($small, 22)));
        $largeSize = getimagesizefromstring((string) base64_decode(substr($large, 22)));
        if ($smallSize === false || $largeSize === false) {
            $this->fail('Expected both data URIs to hold readable PNGs.');
        }
        // Without margin, the width is modules * 2. Doubling the scale and adding
        // 2 modules of margin on each side gives (modules + 4) * 4.
        $modules = intdiv($smallSize[0], 2);
        $this->assertSame(($modules + 4) * 4, $largeSize[0]);
    }


    // Test that the current code is 6 characters long
    public function testCurrentCodeLength(): void
    {
        $secret = Authenticatron::makeSecret();
        $result = Authenticatron::getCode($secret);
        $this->assertEquals(6, strlen($result));
    }

    // Test that the current code contains only digits
    public function testCurrentCodeIsNumeric(): void
    {
        $secret = Authenticatron::makeSecret();
        $result = Authenticatron::getCode($secret);
        $this->assertMatchesRegularExpression('/^\d{6}$/', $result);
    }

    // Test that getCode is deterministic for the same secret and timestamp
    public function testCodeDeterminism(): void
    {
        $secret = Authenticatron::makeSecret();
        $timestamp = time();
        $this->assertEquals(
            Authenticatron::getCode($secret, $timestamp),
            Authenticatron::getCode($secret, $timestamp),
        );
    }


    // Test that getCodesInRange returns 5 codes with default variance
    public function testCodeRangeCount(): void
    {
        $secret = Authenticatron::makeSecret();
        $result = Authenticatron::getCodesInRange($secret);
        $this->assertCount(5, $result);
    }

    // Test that getCodesInRange respects a custom variance
    public function testCodeRangeCustomVariance(): void
    {
        $secret = Authenticatron::makeSecret();
        $this->assertCount(3, Authenticatron::getCodesInRange($secret, 1));
        $this->assertCount(1, Authenticatron::getCodesInRange($secret, 0));
    }

    // Test that getCodesInRange returns the expected signed integer keys
    public function testCodeRangeKeys(): void
    {
        $secret = Authenticatron::makeSecret();
        $result = Authenticatron::getCodesInRange($secret);
        $this->assertArrayHasKey(-2, $result);
        $this->assertArrayHasKey(-1, $result);
        $this->assertArrayHasKey(0, $result);
        $this->assertArrayHasKey(1, $result);
        $this->assertArrayHasKey(2, $result);
    }


    // Test that a current code is accepted
    public function testCheckCodeValid(): void
    {
        $secret = Authenticatron::makeSecret();
        $code = Authenticatron::getCode($secret);
        $this->assertTrue(Authenticatron::checkCode($code, $secret));
    }

    // Test that a code from a historical timestamp is rejected
    public function testCheckCodeInvalid(): void
    {
        $secret = Authenticatron::makeSecret();
        // Unix timestamp 30 = 30 seconds after epoch, will never be within variance of now
        $oldCode = Authenticatron::getCode($secret, 30);
        $this->assertFalse(Authenticatron::checkCode($oldCode, $secret));
    }


    // Test that new() returns an array with the expected keys
    public function testNewReturnsExpectedKeys(): void
    {
        $result = Authenticatron::new('John Smith', 'Example');
        $this->assertArrayHasKey('Secret', $result);
        $this->assertArrayHasKey('URL', $result);
        $this->assertArrayHasKey('QR', $result);
    }

    // Test that new() URL contains the generated secret
    public function testNewUrlContainsSecret(): void
    {
        $result = Authenticatron::new('John Smith', 'Example');
        $this->assertStringContainsString($result['Secret'], $result['URL']);
    }
}
