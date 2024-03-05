<?php
declare(strict_types=1);

namespace FWS\Services;

use FWS\Singleton;

// phpcs:disable Inpsyde.CodeQuality.ArgumentTypeDeclaration.NoArgumentType -- using mixed parameter type
// phpcs:disabl Inpsyde.CodeQuality.ReturnTypeDeclaration.NoReturnType -- using mixed return type

/**
 * Service Validator provides tool for checking contents of provided variables.
 *
 * Usage:
 *   fws()->validator()->isValid($var, 'numeric'): bool
 * Inverted rule:
 *   fws()->validator()->isValid($var, '!numeric'): bool
 * Multiple validations:
 *   fws()->validator()->validate($var, ['numeric', 'min:1']): array
 * Validating multiple values (form validation):
 *   $instructions = [
 *          ['company', 'Company Name', ['Required', 'LenMax:60']],
 *        ['email', 'Email Address', ['Required', 'Email']],
 *   ];
 *   fws()->validator()->validateRequest($_POST, $instructions): array
 *
 * Validating functions accept three parameters:
 *   1. value which need to be validated
 *   2. optional parameter to influence comparison process
 *   3. instance of validator service to be accessed from external validators
 *
 * Context is buffer with all values needed to participate in validation process,
 * using context validator can compare its testing value (first param) against any
 * other input value within complex task,
 * for example to check are two password inputs are same in registration form.
 */
class Validator extends Singleton
{

    // list of registered rules
    protected $rules = [];

    // buffer for ValidateAll result
    protected $failures = [];

    // buffer for ValidateAll context
    protected $context = [];

    // internal flag
    protected $terminateChain = false;

    // internal flag
    protected $checkAllRules = false;


    // constants used to declare return type
    public const RETURN_MESSAGE = 1;
    public const RETURN_RULE = 2;
    public const RETURN_ALL = 3;


    /**
     * Constructor
     */
    protected function __construct()
    {
        // initialize
        $this->registerBuiltInRules();
    }


    /**
     * Register built-in rules.
     */
    protected function registerBuiltInRules(): void
    {
        // general validation rules
        $this->addRule('Equal', [$this, 'ruleEqual'], '%s has not correct value');
        $this->addRule('In', [$this, 'ruleIn'], '%s is not in set of allowed values');
        $this->addRule('Required', [$this, 'ruleRequired'], '%s is required');
        $this->addRule('SameValue', [$this, 'ruleSameValue'], '%s is not matched');

        // string validation rules
        $this->addRule('Alpha', [$this, 'ruleAlpha'], '%s can contain only alphabet characters');
        $this->addRule('Alnum', [$this, 'ruleAlnum'], '%s must be alpha-numerical');
        $this->addRule('Date', [$this, 'ruleDate'], '%s is not valid date');
        $this->addRule('Email', [$this, 'ruleEmail'], '%s is not valid email adress');
        $this->addRule('EmailNamed', [$this, 'ruleEmailNamed'], '%s is not valid email address');
        $this->addRule('FileName', [$this, 'ruleFileName'], '%s can contain: A-Z,0-9,~,_,!,|,.,-');
        $this->addRule('IP', [$this, 'ruleIP'], '%s is not valid IP address');
        $this->addRule('IPv4', [$this, 'ruleIPv4'], '%s is not valid IPv4 address');
        $this->addRule('Len', [$this, 'ruleLen'], '%s has incorrect length');
        $this->addRule('LenMin', [$this, 'ruleLenMin'], '%s has length below minimum allowed');
        $this->addRule('LenMax', [$this, 'ruleLenMax'], '%s has length above maximum allowed');
        $this->addRule('LenRange', [$this, 'ruleLenRange'], '%s has length out of allowed range');
        $this->addRule('URL', [$this, 'ruleURL'], '%s contains characters forbidden for URL address');
        $this->addRule('UTF8', [$this, 'ruleUTF8'], '%s is not valid UTF8 text');

        // numeric validation rules;
        $this->addRule('Decimal', [$this, 'ruleDecimal'], '%s is not valid or too big decimal number');
        $this->addRule('Digits', [$this, 'ruleDigits'], '%s can contain only numbers');
        $this->addRule('Numeric', [$this, 'ruleNumeric'], '%s can contain only numbers and decimal point');
        $this->addRule('Float', [$this, 'ruleFloat'], '%s is not valid decimal value');
        $this->addRule('Integer', [$this, 'ruleInteger'], '%s is not valid integer value');
        $this->addRule('Min', [$this, 'ruleMin'], '%s is below minimal allowed number');
        $this->addRule('Max', [$this, 'ruleMax'], '%s is higher then maximum allowed number');
        $this->addRule('InRange', [$this, 'ruleInRange'], '%s is not in allowed range');

        // heavy artillery
        $this->addRule('Exec', [$this, 'ruleExec'], '');
        $this->addRule('RegEx', [$this, 'ruleRegEx'], '%s is not correctly formatted content');

        // flow control rules
        $this->addRule('All', [$this, 'ruleAll'], '');
        $this->addRule('Stop', [$this, 'ruleStop'], '');
        $this->addRule('SkipIf', [$this, 'ruleSkipIf'], '');
    }


    /**
     * Registration of new rule.
     * Example:$validator->addRule('uniqueId', 'valid_unique_id', '%s is not valid id');
     *
     * @param string $ruleName case-insensitive name of rule
     * @param string|array|callable $callable function|callable|closure which return boolean as result
     * @param string $message error message
     * @return self
     */
    public function addRule(string $ruleName, $callable, string $message): self // phpcs:ignore Inpsyde.CodeQuality.ArgumentTypeDeclaration
    {
        $this->rules[strtolower($ruleName)] = [
            $callable,
            $message,
        ];
        return $this;
    }


    /**
     * Return list of all installed validator rules.
     */
    public function getRulesList(): array
    {
        return array_keys($this->rules);
    }


    /**
     * External rules can reach "context" by this method.
     *
     * @param string $key
     * @return mixed
     */
    public function getContextValue(string $key)
    {
        return $this->context[$key] ?? null;
    }


    /**
     * Check is input value valid or not using single specified rule.
     * Placing prefix "!" in front of rule name will invert result of checking.
     * To check against multiple rules use "validate" method.
     *
     * @param mixed $testVar input variable which need to be validated
     * @param string $ruleName case-insensitive name of rule
     * @param mixed $param optional
     * @return bool
     */
    public function isValid($testVar, string $ruleName, $param = null): bool
    {
        // extract optional prefix
        $prefix = $ruleName ? substr($ruleName, 0, 1) : '';
        if (in_array($prefix, ['!'], true)) {
            $ruleName = substr($ruleName, 1);
        }

        // find callable
        $ruleName = strtolower($ruleName);
        if (!isset($this->rules[$ruleName])) {
            return false; // for security reasons return false
        }
        $callable = $this->rules[$ruleName][0];

        // convert testVar to array if needed
        if (!is_array($testVar)) {
            $testVar = [$testVar];
        }

        // each item must pass
        $inverter = $prefix === '!' ? 1 : 0;
        $success = true;
        foreach ($testVar as $testItem) {
            $result = is_array($testItem)
                ? $this->isValid($testItem, $ruleName, $param)
                : call_user_func_array($callable, [$testItem, $param, $this]);
            if ($result ^ $inverter === 0) {
                $success = false;
            }
        }

        // return result
        return $success && !empty($testVar);
    }


    /**
     * Perform validation using multiple validation rules and return array of violated rules.
     * Input is valid if returned array is empty.
     * Note that validation will stop on first failed rule (unless you specify to check all rules by using rule "all").
     *
     * @param mixed $testVar input variable to be checked
     * @param array|string $rules array (or '|'-separated list) of rules
     * @param mixed $context optional, inputs of whole form, for referencing purposes
     * @return array  list of validators that are not passed
     */
    public function validate($testVar, $rules, $context = []): array
    {
        $this->failures = [];
        $this->context = $context;
        $this->terminateChain = false;
        $this->checkAllRules = false;

        // if rules are specified as string separate them by '|'
        if (is_string($rules)) {
            $rules = explode('|', $rules);
        }

        // loop
        foreach ($rules as $rule) {
            // each rule consist of name and optional parameter separated by ':'
            $parts = explode(':', $rule, 2);
            $name = strtolower(ltrim($parts[0], '!'));
            if (!isset($this->rules[$name])) {
                return ["no-rule:$name"];
            }

            // execute rule
            $result = $this->isValid($testVar, $parts[0], $parts[1] ?? null);

            // append failure to list
            if ($result === false) {
                $this->failures[$name] = true;
            }

            // should skip rest of rules?
            if ($this->shouldSkipValidationChain($result)) {
                break;
            }
        }

        // return list of failures
        return array_keys($this->failures);
    }


    /**
     * Check is there a reason to skip rest of validation rules.
     *
     * @param bool $result
     * @return bool
     */
    protected function shouldSkipValidationChain(bool $result): bool
    {
        return $this->terminateChain || ($result === false && !$this->checkAllRules);
    }


    /**
     * Perform validation on whole set of data, typically on array of values received via form submission.
     * Example:
     *    $instructions = [
     *        ['company', 'Company Name', 'Required|LenMax:60'],
     *        ['email', 'Email Address', 'Required|Email']
     *    ];
     *    $errors = fws()->validator()->validateRequest($_POST, $instructions);
     *
     * @param array|null $requestData
     * @param array $instructions
     * @param int $return
     * @return array
     */
    public function validateRequest(?array $requestData, array $instructions, int $return = self::RETURN_MESSAGE): array
    {
        $violations = [];

        // execute all checks
        foreach ($instructions as $line) {
            [$key, $title, $rules] = $line;

            // perform validations
            $lineResult = $this->validate($requestData[$key] ?? '', $rules, $requestData);

            // just continue on success
            if (empty($lineResult)) {
                continue;
            }

            // add violation report
            $violations[$key] = [];
            foreach ($lineResult as $rule) {
                $errorTemplate = $this->rules[$rule][1] ?? '?';
                switch ($return) {
                    case self::RETURN_MESSAGE:
                        $violations[$key][$rule] = sprintf($errorTemplate, $title);
                        break;
                    case self::RETURN_RULE:
                        $violations[$key][] = $rule;
                        break;
                    default:
                        $violations[$key][] = [
                            'key' => $key,
                            'rule' => $rule,
                            'tpl' => $errorTemplate,
                            'message' => sprintf($errorTemplate, $title),
                        ];
                }
            }
        }

        // return list of validation messages
        return $violations;
    }


    //  ----------------------------------
    //      built-in validation rules
    //  ----------------------------------


    /**
     * Rule "All" (flow control rule).
     * It instructs validator to NOT terminate checking loop on first failure.
     */
    public function ruleAll(): bool
    {
        $this->checkAllRules = true;
        return true;
    }


    /**
     * Rule "Alpha".
     * Checks whether a string consists of alphabetical characters only.
     * Note: empty strings, empty array, null, false are valid because they does not contain forbidden char.
     */
    public function ruleAlpha(mixed $value): bool
    {
        return is_string($value)
            ? ($value ? preg_match('/^[\pL]+$/u', strval($value)) === 1 : true)
            : (!$value);
    }


    /**
     * Rule "AlNum".
     * Checks whether a string consists of alphabetical characters and numbers only.
     */
    public function ruleAlnum(mixed $value): bool
    {
        return is_string($value)
            ? ($value ? preg_match('/^[\pL\pN]+$/u', strval($value)) === 1 : true)
            : (!$value);
    }


    /**
     * Rule "Date".
     * Check can value be interpreted as valid date.
     * Note: non-string value and empty string are not valid dates.
     */
    public function ruleDate(mixed $value, mixed $format): bool
    {
        if (!is_string($value)) {
            return false;
        }
        $value = trim($value);
        if (!$value) {
            return false;
        }
        if (!$format) {
            return strtotime($value) !== false;
        }
        if (!function_exists('date_parse_from_format')) {
            return false;
        }
        $arr = date_parse_from_format($format, $value);
        return $arr['error_count'] === 0;
    }


    /**
     * Rule "Decimal".
     * Checks if a string is a proper decimal format.
     * The format to specify 4 digits and 2 decimal places should be '4.2'.
     * Note: mysql limit for formatting is '65.30'.
     */
    public function ruleDecimal(mixed $value, string $params): bool
    {
        if (!is_string($value)) {
            $value = strval($value);
        }
        if (preg_match('/^[0-9]+(\.)?[0-9]*$/', $value) !== 1) {
            return false;
        }
        list($len1, $len2) = explode('.', "$params."); // ensure at least one '.'
        list($man, $dec) = explode('.', "$value.");
        $man = ($man) ? strlen(strval(intval($man))) : 0;
        $dec = ($dec) ? strlen(strval(intval($dec))) : 0;
        return $man + $dec <= intval($len1) && $dec <= intval($len2);
    }


    /**
     * Rule "Digits".
     * Checks if a string contains only numbers,
     * it is similar to "Integer" rule but handles huge numbers, but it's much slower.
     */
    public function ruleDigits(mixed $value): bool
    {
        if (!is_string($value)) {
            $value = strval($value);
        }
        return preg_match('/^[+-]?\d+$/', $value) === 1;
    }


    /**
     * Rule "Email".
     * Validate email address.
     */
    public function ruleEmail(mixed $email): bool
    {
        $pattern = '[-_a-z0-9\'+*$^&%=~!?{}]++(?:\.[-_a-z0-9\'+*$^&%=~!?{}]+)*+@'
            . '(?:(?![-.])[-a-z0-9.]+(?<![-.])\.[a-z]{2,6}|\d{1,3}(?:\.\d{1,3}){3})(?::\d++)?';
        if (!is_string($email)) {
            $email = strval($email);
        }
        return strlen($email) > 256
            ? false // prevent DoS
            : preg_match("/^$pattern$/iD", $email) === 1;
    }


    /**
     * Rule "EmailNamed".
     * Checks is string valid named email adress like 'My name<myname@myhost.com>'.
     */
    public function ruleEmailNamed(mixed $email): bool
    {
        $pattern = '[-_a-z0-9\'+*$^&%=~!?{}]++(?:\.[-_a-z0-9\'+*$^&%=~!?{}]+)*+@'
            . '(?:(?![-.])[-a-z0-9.]+(?<![-.])\.[a-z]{2,6}|\d{1,3}(?:\.\d{1,3}){3})(?::\d++)?';
        if (!is_string($email)) {
            $email = strval($email);
        }
        return strlen($email) > 256
            ? false // prevent DoS
            : preg_match("/^[^@]*<$pattern>$/iD", $email) === 1;
    }


    /**
     * Rule "Equal".
     * Compare value with provided constant value.
     */
    public function ruleEqual(mixed $value1, mixed $value2): bool
    {
        return $value1 === $value2;
    }


    /**
     * Rule "Exec".
     * Call external function to determine validity.
     * Callable can be function name (ex. "is_string"), static method (ex. "MyValidator::userExist"),
     * dynamic method (ex. [$obj, 'method']) or closure.
     * Note: callable receives only one param.
     */
    public function ruleExec(mixed $value, mixed $callable): bool
    {
        if (!$callable) {
            return false;
        }
        if (is_string($callable)) {
            $callable = explode('::', $callable);
        }
        if (!isset($callable[1]) && is_callable($callable[0])) {
            return $callable[0]($value);
        }
        if (isset($callable[1]) && is_callable($callable)) {
            return call_user_func_array($callable, [$value]);
        }
        return false;
    }


    /**
     * Rule "FileName".
     * Checks if a string contains only safe characters for URLs, file names or directories.
     * This is not applicable for path validation.
     */
    public function ruleFileName(mixed $value): bool
    {
        if (!is_string($value) || strlen($value) > 256 || in_array($value, ['', '.', '..'], true)) {
            return false;
        }
        // note: '&', '=', ' ' may produce problems when linking them via URL
        $purified = preg_replace('/[^A-Za-z0-9~_.!\|-]/', '', $value);
        return $purified === $value;
    }


    /**
     * Rule "Float".
     * Checks whether a string is a valid float
     * Untrimmed string is allowed.
     */
    public function ruleFloat(mixed $value): bool
    {
        $value = trim(strval($value));
        return preg_match('/^-?(?:\d+|\d*\.\d+)$/', $value) === 1;
    }


    /**
     * Rule "In".
     * Check is value in set of allowed comma-separated values.
     */
    public function ruleIn(mixed $value, string|array $values): bool
    {
        if (!is_array($values)) {
            $values = explode(',', $values);
        }
        return in_array($value, $values, true);
    }


    /**
     * Rule "InRange".
     * Validate that value is IN range of values, supplied in form "min..max", for example: 'InRange:0..100'.
     * Works both for numbers and strings.
     * Note: empty string, null, false will fail validation.
     */
    public function ruleInRange(mixed $value, string $limits): bool
    {
        if (strval($value) === '') {
            return false;
        }
        $bounds = explode('..', $limits);
        if (count($bounds) !== 2) {
            return false;
        }
        list($min, $max) = $bounds;
        if (is_numeric($min) && is_numeric($max)) {
            // cast in numeric comparison
            $min = floatval($min);
            $max = floatval($max);
            $value = floatval($value);
        }
        return ($value >= $min) && ($value <= $max);
    }


    /**
     * Rule "Integer".
     * Checks whether a string is a valid integer.
     * Untrimmed string is allowed.
     */
    public function ruleInteger(mixed $value): bool
    {
        return ctype_digit(trim(strval($value)));
    }


    /**
     * Rule "IP".
     * Confirm that value represent valid IPv4 or IPv6 address.
     */
    public function ruleIP(mixed $value): bool
    {
        $value = strval($value);
        if ($value === 'localhost') {
            return true;
        }
        if (strlen($value) > 45) {
            return false;
        }
        return filter_var($value, FILTER_VALIDATE_IP) !== false;
    }


    /**
     * Rule "IPv4".
     * Confirm that value represent valid IPv4 only address.
     */
    public function ruleIPv4(mixed $value): bool
    {
        $value = strval($value);
        if ($value === 'localhost') {
            return true;
        }
        $arr = array_map('intval', explode('.', $value));
        if (count($arr) !== 4) {
            return false;
        }
        foreach ($arr as $int) {
            if ($int < 0 || $int > 255) {
                return false;
            }
        }
        return implode('.', $arr) === $value;
    }


    /**
     * Rule "Len".
     * Compare length of string with supplied value.
     * Note, this is not UTF-8 aware method.
     */
    public function ruleLen(mixed $value, mixed $length): bool
    {
        return strlen(strval($value)) === intval($length);
    }


    /**
     * Rule "LenMax".
     * Check is length of string equal or less than specified value.
     * Note, this is not UTF-8 aware method.
     */
    public function ruleLenMax(mixed $value, mixed $num): bool
    {
        return strlen(strval($value)) <= intval($num);
    }


    /**
     * Rule "LenMin".
     * Check is length of string equal or greater than specified value.
     * Note, this is not UTF-8 aware method.
     */
    public function ruleLenMin(mixed $value, mixed $num): bool
    {
        return strlen(strval($value)) >= intval($num);
    }


    /**
     * Rule "LenRange".
     * Check is length of string in allowed range, like 'LenRange:3..12'.
     * Note, this is not UTF-8 aware method.
     */
    public function ruleLenRange(mixed $value, string $limits): bool
    {
        $range = array_map('intval', explode('..', $limits));
        $length = strlen(strval($value));
        if (count($range) === 1) {
            $range[1] = $range[0];
        }
        return $range[0] <= $length && $length <= $range[1];
    }


    /**
     * Rule "Max".
     * Check is value "less than or equal" specified value.
     * Both operands will be cast to integer before comparison.
     */
    public function ruleMax(mixed $value, mixed $num): bool
    {
        return intval($value) <= intval($num);
    }


    /**
     * Rule "Min".
     * Check is value "greater than or equal" specified value.
     * Both operands will be cast to integer before comparison.
     */
    public function ruleMin(mixed $value, mixed $num): bool
    {
        return intval($value) >= intval($num);
    }


    /**
     * Rule "Numeric".
     * Checks if a string contains only numbers and decimal point.
     * If you need to allow only numbers use "Digits" rule.
     * TODO: can "Float" rule can cover this case?
     */
    public function ruleNumeric(mixed $value): bool
    {
        return is_numeric(trim(strval($value)));
    }


    /**
     * Rule: "RegEx".
     * Validate value against regular expression.
     * Note: this method is raw regex and therefore not UTF8 aware.
     * Note: ensure limiting length of $value to prevent DoS.
     */
    public function ruleRegEx(mixed $value, string $pattern): bool
    {
        return preg_match($pattern, strval($value)) === 1;
    }


    /**
     * Rule "Required".
     * Validate that variable cannot be empty string or empty array.
     */
    public function ruleRequired(mixed $value): bool
    {
        return is_array($value) ? !empty($value) : strval($value) !== '';
    }


    /**
     * Rule "SameValue".
     * Compare value with value of another field.
     * This validator is expected to be used in form processing (multiple validations).
     * Comparison is strict.
     *
     * @param mixed $value input string
     * @param string $referenceName name of field which will be compared with $value
     * @return bool
     */
    public function ruleSameValue(mixed $value, string $referenceName): bool
    {
        if (!isset($this->context[$referenceName])) {
            return false;
        }
        return $value === $this->context[$referenceName];
    }


    /**
     * Rule "SkipIf" (flow control rule).
     * Skip validation chain depending on value of another form field.
     * Example:
     *   form has fields: UserType, Name, Ages, ParentName
     *   rule for ParentName: ['SkipIf:UserType:Equal:firm', 'SkipIf:Ages:Min:18', 'Required']
     *   ParentName will not be checked for "Required" if UserType==="firm" OR Ages>=18
     */
    public function ruleSkipIf(mixed $value, string $rule): bool
    {
        // unpack rule
        list($referenceName, $ruleName, $ruleOpts) = explode(':', $rule, 3) + ['', '', ''];
        $referenceValue = $this->context[$referenceName] ?? '';  // control without value (unsuccessful control) returns empty string
        $value = null;  // silence IDE

        // signal error for unknown validator
        if ($ruleName === '') {
            return false;
        }

        // call validation
        $result = $this->isValid($referenceValue, $ruleName, $ruleOpts);

        // skip rest of rules if result is true
        if ($result) {
            $this->terminateChain = true;
        }

        // silently return to loop
        return true;
    }


    /**
     * Rule "Stop" (flow control rule).
     * This will terminate ValidateAll's chain of validations if failures occurs.
     */
    public function ruleStop(): bool
    {
        if (!empty($this->failures)) {
            $this->terminateChain = true;
        }

        // silently return to loop
        return true;
    }


    /**
     * Rule "URL".
     * Checks if a string contains valid URL structure.
     */
    public function ruleURL(mixed $value): bool
    {
        // using filter_var($value, FILTER_VALIDATE_URL) is not good enough because it passes "http://http://example.com"
        // based on: http://flanders.co.nz/2009/11/08/a-good-url-regular-expression-repost/
        $urlPattern = '`^(?#Protocol)(?:(?:ht|f)tp(?:s?)\:\/\/|~\/|\/)?(?#Username:Password)(?:\w+:\w+@)?'
            . '(?#Subdomains)(?:(?:[-\w\d]+\.)+'
            . '(?#TopLevel Domains)(?:com|org|net|gov|mil|biz|info|mobi|name|aero|jobs|museum|travel|[a-z]{2}))'
            . '(?#Port)(?::[\d]{1,5})?(?#Directories)(?:(?:(?:\/(?:[-\w~!$+|.,=]|%[a-f\d]{2})+)+|\/)+|\?|#)?'
            . '(?#Query)(?:(?:\?(?:[-\w~!$+|.,*:]|%[a-f\d{2}])+=?'
            . '(?:[-\w~!$+|.,*:=]|%[a-f\d]{2})*)'
            . '(?:&(?:[-\w~!$+|.,*:]|%[a-f\d{2}])+=?(?:[-\w~!$+|.,*:=]|%[a-f\d]{2})*)*)*'
            . '(?#Anchor)(?:#(?:[-\w~!$+|/.,*:=]|%[a-f\d]{2})*)?$`';
        $value = strval($value);
        return strlen($value) > 1200
            ? false // prevent DoS
            : preg_match($urlPattern, $value) === 1;
    }


    /**
     * Rule "UTF8".
     * Validates that content is valid UTF-8 string.
     */
    public function ruleUTF8(mixed $value): bool
    {
        $value = strval($value);
        return $value === ''
            ? true
            : preg_match('/^./us', $value) === 1;
    }

}
