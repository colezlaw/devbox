<?php
/**
 * Defines the ContentSecurityPolicy and CSPException classes.
 *
 * Defines a somewhat simple API for generating a Content
 * Security Policy.
 */
namespace owasp\csp;

/**
 * A Content Security Policy.
 *
 * This is the main class for generating a policy.
 */
class ContentSecurityPolicy {
    /* The default-src directive */
    const DEFAULT_SRC = 'default-src';
    /** The script-src directive */
    const SCRIPT_SRC = 'script-src';
    /** The object-src directive */
    const OBJECT_SRC = 'object-src';
    /** The style-src directive */
    const STYLE_SRC = 'style-src';
    /** The img-src directive */
    const IMG_SRC = 'img-src';
    /** The media-src directive */
    const MEDIA_SRC = 'media-src';
    /** The frame-src directive */
    const FRAME_SRC = 'frame-src';
    /** The font-src directive */
    const FONT_SRC = 'font-src';
    /** The connect-src directive */
    const CONNECT_SRC = 'connect-src';
    /** The 'none' source */
    const SOURCE_NONE = "'none'";
    /** The 'self' source */
    const SOURCE_SELF = "'self'";
    /** The 'unsafe-inline' source */
    const SOURCE_UNSAFE_INLINE = "'unsafe-inline'";
    /** The 'unsafe-eval' source */
    const SOURCE_UNSAFE_EVAL = "'unsafe-eval'";

    /** The policy itself */
    private $policy;

    /**
     * Creates a new ContentSecurityPolicy.
     *
     * Takes no arguments as the `addSource` method should
     * be used to add any policies.
     *
     * @return ContentSecurityPolicy a new ContentSecurityPolicy object
     */
    function __construct() {
        $this->policy = array();
        $this->setPolicySourceDirectives();
    }
    
    /**
     * Looks for *_SRC constants in this class, and
     * prepares this policy array for them.
     */
    private function setPolicySourceDirectives(){
        $refl = new \ReflectionClass(__CLASS__);
        $srcPattern = '/.+_SRC$/';
        foreach ($refl->getConstants() as $constant => $value){
            if (preg_match($srcPattern, $constant)){
                $this->policy[constant(__CLASS__.'::'.$constant)] = array();
            }
        }
    }

    /**
     * Copies an existing ContentSecurityPolicy.
     *
     * This is useful if you need to clone an existing policy
     * and modify it slightly - for example if doing a
     * `Report-Only` policy.
     *
     * @return ContentSecurityPolicy the cloned policy
     */
    private function copy() {
        $retval = new ContentSecurityPolicy();
        foreach ($this->policy as $directive => $sources) {
            foreach ($sources as $source) {
                array_push($retval->policy[$directive], $source);
            }
        }
    }

    /**
     * Adds a new source to a directive in the policy.
     *
     * Adds a new source to one of the directives in the policy
     * and returns the updated policy. This allows you to use
     * a fluent API for constructing the policies.
     *
     * @param string $directive the directive to add the source to
     * @param string $source the source to add to the directive
     * @return ContentSecurityPolicy the updated policy
     * @throws \owasp\csp\CSPException if the supplied directive is invalid.
     */
    function addSource($directive, $source) {
        if (!isset($this->policy[$directive])) {
          throw new CSPException("Invalid directive");
        }
        $this->policy[$directive][] = $source;
        return $this;
    }

    /**
     * String representation of the policy.
     *
     * This method generates the policy as a String suitable for adding
     * as the value for a header for `Content-Security-Policy` or
     * `X-Webkit-CSP`.
     *
     * @return string the policy in the form required for the header.
     */
    function toString() {
        $retval = array();
        foreach ($this->policy as $directive => $sources) {
          if (sizeof($sources) > 0) {
            $retval[] = join(' ', array($directive, join(' ', $sources)));
          }
        }
        return join('; ', $retval);
    }
    
    /**
     * Magic toString, allows typecasting the object
     * Now you can do things like:
     *   $csp = new CSP();
     *   echo $csp;
     *   echo (string)$csp;
     *
     * @return the stringified class
     */
    public function __toString(){
        $retval = array();
        foreach ($this->policy as $directive => $sources) {
        if (sizeof($sources) > 0) {
                $retval[] = join(' ', array($directive, join(' ', $sources)));
            }
        }
        return join('; ', $retval);
    }
}

/**
 * An exception for ContentSecurityPolicy
 *
 * The message will have the detailed reason for the failure.
 */
class CSPException extends \Exception {}

// vim: et:ts=4:sw=4:sts=4:cc=80
