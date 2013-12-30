<?php
error_reporting(E_ALL);
ob_start();
ini_set('display_errors', '1');
require '../vendor/autoload.php';
use owasp\csp\ContentSecurityPolicy as CSP;

$csp = (new CSP())
  ->addSource(\owasp\csp\ContentSecurityPolicy::DEFAULT_SRC, \owasp\csp\ContentSecurityPolicy::SOURCE_NONE)
  ->addSource('script-src', 'code.jquery.com')
  ->addSource('script-src', \owasp\csp\ContentSecurityPolicy::SOURCE_UNSAFE_INLINE);
header("Content-Security-Policy: {$csp->toString()}");
?>
<!DOCTYPE html>
<html>
<head>
<script src="//code.jquery.com/jquery-1.10.1.min.js"></script>
<script>
$(document).ready(function() {
  alert('1');
});
</script>
<title>OWASP CLT CSP</title>
</head>
<body>
<h1>ZOMG It's working!</h1>
</body>
</html>

