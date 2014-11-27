<?php
$add3 = function($a, $b, $c) { return $a + $b + $c; };

function autoCurry(Closure $f) {
    $args = argsCount($f); var_dump($args);
    $argsTail = array();

    if (argsCount($f) < 2) {
        return $f;
    } else {
        return function($argsTail) {
            
        };
    }
}

curry($add3, 42);





$add2ReadyOld = curryPrepareOld($add3);
$add2Old = $add2ReadyOld(1);
echo $add2Old(13, 23) . "\n";

$add1ReadyOld = curryPrepareOld($add2Old);
$add1Old = $add1ReadyOld(2);
echo $add1Old(42) . "\n";

function autoCurry(Closure $f) {
    echo argsCount($f) . "\n";
}

function argsCount(Closure $f) {
    return (new ReflectionMethod($f, '__invoke'))->getNumberOfParameters();
}

function curryOld(Closure $f, $arg) {
    return function () use ($f, $arg) {
        $args = func_get_args();
        array_unshift($args, $arg);
        return call_user_func_array($f, $args);
    };
}
function curryPrepareOld(Closure $f) {
    return function($arg) use ($f) {
        return curryOld($f, $arg);
    };
};
function autoCurryOld(Closure $f) {
    //echo argsCount($f) . "\n";
    if (argsCount($f) < 2) {
        return $f;
    } else {
        return function($arg) use ($f) {
            autoCurryOld(curry($f, $arg));
        };
    }
}


