<?php
declare(strict_types=1);

require_once __DIR__ . '/../authenticatron.php';
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

    // Test that the secret is a string
    public function testSecretType(): void
    {
        $result = Authenticatron::makeSecret();
        $this->assertIsString($result);
    }

    // Test that the secret is a string
    public function testSecretChars(): void
    {
        $result = Authenticatron::makeSecret();
        $this->assertMatchesRegularExpression('/^[A-Z2-7]+$/', $result);
    }


    // Test that the URL is a specific string
    public function testUrlChars(): void
    {
        $result = Authenticatron::getUrl('accountName', 'secret', 'issuer');
        $this->assertEquals('otpauth://totp/Example Site: test?secret=test&issuer=Example+Site', $result);
    }

    // Test that the qrCode is a string
    public function testQrCodeType(): void
    {
        $url = Authenticatron::getUrl('accountName', 'secret', 'issuer');
        $result = Authenticatron::generateQrCode('url');
        $this->assertIsString($result);
    }

    // Test that the current code is a 6 digit number
    public function testCurrentCode(): void
    {
        $secret = Authenticatron::makeSecret();
        $result = Authenticatron::getCode($secret);
        $this->assertEquals(6, strlen($result));
    }

    // Test that there are 5 codes in the range
    public function testCodeRange(): void
    {
        $secret = Authenticatron::makeSecret();
        $result = Authenticatron::getCodesInRange($secret);
        $this->assertCount(5, $result);
    }

    // Test that the code is valid
    public function testCodeValid(): void
    {
        $secret = Authenticatron::makeSecret();
        $code = Authenticatron::getCode($secret);
        $result = Authenticatron::checkCode($code, $secret);
        $this->assertTrue($result);
    }
}
