<?php
/*
    Random User Generator CLI interface.
*/

function echo_exception_handler($e) {
  echo sprintf('Uncaught Exception %s: "%s" at %s line %s', get_class($e), $e->getMessage(), $e->getFile(), $e->getLine());
}
set_exception_handler('echo_exception_handler');

$exception_log = new Logger('exception');

require_once("Dataset.class.php");

// Read in arguments
$args = $argv[1] ?? null;

// Extract paramters
$results = regexMatch("/results=(\d*),?/", $args);
$seed    = regexMatch("/seed=(\w*),?/", $args);
$lego    = regexMatch("/(lego)/", $args);
$gender  = regexMatch("/gender=(\w*),?/", $args);
$format  = regexMatch("/fmt=(\w*),?/", $args);
$nat     = regexMatch("/nat=(\w*),?/", $args);

$dataset = new Dataset($seed);

// Only accept numeric results and use default if not provided
if ($results === null || !is_numeric($results)) {
    $results = 1;
}

// Sanitize gender input. If it isn't valid, then it is null (random)
// A set seed will override the gender
if ($gender !== "male" && $gender !== "female" || $seed !== null) {
    $dataset->setGender(null);
} else {
    $dataset->setGender($gender);
}

// Set format
$dataset->setFormat($format);

// Choose random nat if not provided or invalid
// Lego mode overrides ALL
if (isset($lego)) {
    $dataset->setNat("lego");
} else if ((($nat === null || !$dataset->validNat($nat)) && $dataset->getNat() === null)) {
    $dataset->chooseRandomNat();
} else {
    if ($dataset->getNat() === null) {
        $dataset->setNat($nat);
    }
}

$dataset->generate($results);
echo $dataset->output() . "\n";

function regexMatch($regex, $subject) {
    preg_match($regex, $subject, $matches);
    return $matches[1] ?? null;
}
?>
