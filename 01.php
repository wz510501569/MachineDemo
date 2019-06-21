<?php

for ($i = 1; $i <= 100; $i++){
	$output = '';

	if ($i % 3 == 0){
		$output = 'Fizz';
	}

	if ($i % 5 == 0){
		$output = 'Buzz';
	}

	if ($i % 3 == 0 && $i % 5 == 0){
		$output = 'Fizz Buzz';    //watch out! the question is "Fizz Buzz" , not "FizzBuzz"
	}

	if (!$output){
		$output = $i;
	}

	echo $output . "<br>";  //  the line break can use PHP_EOL or \n 
}


?>