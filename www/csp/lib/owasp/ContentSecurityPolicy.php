<?php 
namespace owasp;

class ContentSecurityPolicy {
  const DEFAULT_SRC = 'default-src';
  const SCRIPT_SRC = 'script-src';
  const OBJECT_SRC = 'object-src';
  const STYLE_SRC = 'style-src';
  const IMG_SRC = 'img-src';
  const MEDIA_SRC = 'media-src';
  const FRAME_SRC = 'frame-src';
  const FONT_SRC = 'font-src';
  const CONNECT_SRC = 'connect-src';
  const SOURCE_NONE = "'none'";
  const SOURCE_SELF = "'self'";
  const SOURCE_UNSAFE_INLINE = "'unsafe-inline'";
  const SOURCE_UNSAFE_EVAL = "'unsafe-eval'";

  private $policy;

  public function __construct() {
    $this->policy = array();
	$this->setPolicySourceDirectives();
  }


  private function setPolicySourceDirectives(){
	  $refl = new ReflectionClass(__CLASS__);
      $srcPattern = '/.+_SRC$/';
      foreach ($refl->getConstants() as $constant => $value){
          if (preg_match($srcPattern, $constant)){
              $this->policy[constant(__CLASS__.'::'.$constant)] = array();
          }
      }
  }

  private function copy() {
    $retval = new ContentSecurityPolicy();
    foreach ($this->policy as $directive => $sources) {
      foreach ($sources as $source) {
        array_push($retval->policy[$directive], $source);
      }
    }

    return $retval;
  }

  function addSource($directive, $source) {
    if (!isset($this->policy[$directive])) {
      throw new CSPException("Invalid directive");
    }
    $this->policy[$directive][] = $source;
    return $this;
  }

  function toString() {
    $retval = array();
    foreach ($this->policy as $directive => $sources) {
      if (sizeof($sources) > 0) {
        $retval[] = join(' ', [$directive, join(' ', $sources)]);
      }
    }
    return join('; ', $retval);
  }
}

class CSPException extends \Exception {}
