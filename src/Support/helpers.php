<?php

if (! function_exists('starts_with')) {
    /**
     * Checks if a string starts with a given substring.
     */
    function starts_with(string $needle, string $haystack): bool
    {
        return substr($haystack, 0, strlen($needle)) === $needle;
    }
}

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

if (! function_exists('module11')) {
    /**
     * Calculates the module 11 for the given input.
     *
     * @example CPF: module11('input', 2, 12, true)
     * @example CNPJ: module11('input', 2, 9, true)
     * @example PIS,C/C,Age: module11('input', 1, 9, true)
     * @example RG SSP-SP: module11('input', 1, 9, false)
     *
     * @param string $input                   Input numbers without the verification digit
     * @param int    $amountOfDigits          Amount of verification digits to calculate
     * @param int    $multiplicationThreshold The max weight to multiply the numbers
     * @param bool   $timesTen                Whether or not multiply the sum by ten. True returns the digit, false the rest of the division
     */
    function module11(string $input, int $amountOfDigits = 1, int $multiplicationThreshold = 9, bool $timesTen = true): string
    {
        if (! $timesTen) {
            $amountOfDigits = 1;
        }

        for ($n = 1; $n <= $amountOfDigits; $n++) {
            $sum = 0;
            $weight = 2;
            for ($i = strlen($input) - 1; $i >= 0; $i--) {
                $sum += $weight * (int) (substr($input, $i, 1));
                if (++$weight > $multiplicationThreshold) {
                    $weight = 2;
                }
            }

            if ($timesTen) {
                $digit = fmod(fmod(($sum * 10), 11), 10);
            } else {
                $digit = fmod($sum, 11);
            }
            $input .= (string) $digit;
        }

        return substr($input, strlen($input) - $amountOfDigits);
    }
}
