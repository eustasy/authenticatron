<?php
declare(strict_types=1);
namespace eustasy;
require_once __DIR__ . '/../authenticatron.php';

use PHPUnit\Framework\TestCase;

final class AuthTest extends TestCase
{
    // Test that the secret is 16 characters long
    public function testSecretLength(): void
    {
        $auth = new Authenticatron();
        $secret = $auth->makeSecret();
        $this->assertEquals(16, strlen($secret));
    }

    // Test that the secret is a string
    public function testSecretType(): void
    {
        $auth = new Authenticatron();
        $result = $auth->makeSecret();
        $this->assertIsString($result);
    }

    // Test that the secret is a string
    public function testSecretChars(): void
    {
        $auth = new Authenticatron();
        $result = $auth->makeSecret();
        $this->assertMatchesRegularExpression('/^[A-Z2-7]+$/', $result);
    }


    // Test that the URL is a specific string
    public function testUrlChars(): void
    {
        $auth = new Authenticatron();
        $result = $auth->getUrl('test', 'test');
        $this->assertEquals('otpauth://totp/Example Site: test?secret=test&issuer=Example+Site', $result);
    }

    // Test that the qrCode is a string
    public function testQrCodeType(): void
    {
        $auth = new Authenticatron();
        $url = $auth->getUrl('test', 'test');
        $result = $auth->generateQrCode('url');
        $this->assertIsString($result);
    }

    // Test that the current code is a 6 digit number
    public function testCurrentCode(): void
    {
        $auth = new Authenticatron();
        $secret = $auth->makeSecret();
        $result = $auth->getCode($secret);
        $this->assertEquals(6, strlen($result));
    }

    // Test that there are 5 codes in the range
    public function testCodeRange(): void
    {
        $auth = new Authenticatron();
        $secret = $auth->makeSecret();
        $result = $auth->getCodesInRange($secret);
        $this->assertCount(5, $result);
    }

    // Test that the code is valid
    public function testCodeValid(): void
    {
        $auth = new Authenticatron();
        $secret = $auth->makeSecret();
        $code = $auth->getCode($secret);
        $result = $auth->checkCode($code, $secret);
        $this->assertTrue($result);
    }
}
