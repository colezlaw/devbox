<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
ob_start();
require '../vendor/autoload.php';
use owasp\csp\ContentSecurityPolicy as CSP;

$csp = (new CSP())
    ->addSource(CSP::DEFAULT_SRC, CSP::SOURCE_NONE)
    ->addSource(CSP::SCRIPT_SRC, 'code.jquery.com');
    # ->addSource(CSP::SCRIPT_SRC, CSP::SOURCE_UNSAFE_INLINE);
header("X-Webkit-CSP: {$csp->toString()}"
    . "; report-uri /report.php");
header("Content-Security-Policy: {$csp->toString()}"
    . "; report-uri /report.php");
?>
<!DOCTYPE html>
<html>
<head>
<script src="//code.jquery.com/jquery-1.10.1.min.js"></script>
<script>
    $(document).ready(function() {
        $('#thediv').html('Hello from jQuery');
    });
</script>
<title>OWASP CLT CSP</title>
</head>
<body>
<h1>Hello CSP</h1>
<div id="thediv"></div>
</body>
</html>
<?php
# vim: cc=80:sts=4:sw=4:ts=4

