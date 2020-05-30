<?php

if (! function_exists('module10')) {
    /**
     * Calculates the module 10 for the given input.
     */
    function module10(string $input): string
    {
        $reversedInput = strrev($input);
        $sum = 0;

        for ($inputSize = strlen($reversedInput), $i = 0; $i < $inputSize; $i++) {
            $value = (int) (substr($reversedInput, $i, 1));
            $productResult = $value * (0 == $i % 2 ? 2 : 1);

            if ($productResult > 9) {
                // Sum the number digits before add to the total sum.
                $sum += array_sum(str_split($productResult));
            } else {
                $sum += $productResult;
            }
        }

        return (ceil($sum / 10) * 10) - $sum;
    }
}
