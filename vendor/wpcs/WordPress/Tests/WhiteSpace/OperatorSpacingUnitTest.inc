<?php

// Boolean not operator: All OK.
if ( 'bb' !== 'bb' ) {
	if (
		empty( $_GET['refid'] ) &&
		empty( $_GET['nolinks'] ) &&
		! is_page_template( 'page_strategy-center.php' ) &&
		! is_page_template( 'page_confirmation.php' ) &&
		! is_page_template( 'page_debartolo.php' ) &&
		! is_singular( 'offer' )
	) {
		hello();
	}
}

// Good.
if ( ! $var ) {
	// ...
}

// Bad.
if (!$var ) {
	// ...
}

// Bad.
if (  !   $var ) {
	// ...
}

// Logical operators: Ok.
if ( $a === $b && $b === $c ) {}
if ( $a === $b || $b === $c ) {}
if ( $a === $b and $b === $c ) {}
if ( $a === $b or $b === $c ) {}
if ( $a === $b xor $b === $c ) {}

// Logical operators: Too little space.
if ( $a === $b&&$b === $c ) {}
if ( $a === $b||$b === $c ) {}
if ( $a === {$b}and$b === $c ) {}
if ( $a === {$b}or$b === $c ) {}
if ( $a === {$b}xor$b === $c ) {}

// Logical operators: Too much space.
if ( $a === $b     &&     $b === $c ) {}
if ( $a === $b     ||     $b === $c ) {}
if ( $a === $b     and     $b === $c ) {}
if ( $a === $b     or     $b === $c ) {}
if ( $a === $b     xor     $b === $c ) {}

// Logical operators: Multi-line, OK.
if ( $a === $b
	&& $b === $c
) {}
if (
	$a === $b
	||
	$b === $c
) {}
if ( $a === $b
	and $b === $c ) {}

if ( $a === $b or
	$b === $c ) {}

// Instanceof
if ( MyClass    instanceof    SomeOtherClass ) {}
if ( MyClass instanceof SomeOtherClass ) {}
