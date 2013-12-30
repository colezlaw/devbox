<?php
use owasp\csp\ContentSecurityPolicy as CSP;

class ContentSecurityPolicyTest extends PHPUnit_Framework_TestCase {
  public function testNew() {
    $csp = new CSP();
    $this->assertEquals('', $csp->toString());
  }

  public function testDefault() {
    $csp = new CSP();
    $csp->addSource(CSP::DEFAULT_SRC, CSP::SOURCE_NONE);
    $this->assertEquals("default-src 'none'", $csp->toString());
  }

  public function testMultiple() {
    $csp = new CSP();
    $csp->addSource(CSP::DEFAULT_SRC, CSP::SOURCE_NONE);
    $csp->addSource(CSP::IMG_SRC, 'https://images.google.com');
    $this->assertEquals("default-src 'none'; img-src https://images.google.com", $csp->toString());
  }

  public function testFluent() {
    $csp = new CSP();
    $csp->addSource(\owasp\csp\ContentSecurityPolicy::DEFAULT_SRC, \owasp\csp\ContentSecurityPolicy::SOURCE_NONE)
       ->addSource(\owasp\csp\ContentSecurityPolicy::IMG_SRC, \owasp\csp\ContentSecurityPolicy::SOURCE_SELF)
       ->addSource(\owasp\csp\ContentSecurityPolicy::IMG_SRC, 'https://images.google.com');
    $expected = "default-src 'none'; img-src 'self' https://images.google.com";
    $this->assertEquals($expected, $csp->toString());
  }

  /**
   * @expectedException          owasp\csp\CSPException
   * @expectedExceptionMessage   Invalid directive
   */
  public function testInvalidDirective() {
    $csp = new CSP();
    $csp->addSource("foo-src", CSP::SOURCE_SELF);
  }
}
