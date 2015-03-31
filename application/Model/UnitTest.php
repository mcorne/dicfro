<?php
/**
 * Dicfro
 *
 * @author     Michel Corne <mcorne@yahoo.com>
 * @copyright  2015 Michel Corne
 * @license    http://opensource.org/licenses/gpl-3.0.html GNU GPL v3
 */

class Model_UnitTest
{
    public $class;
    public $testCases;
    public $testResults;

    public function run()
    {
        $results =  [];

        foreach ($this->testCases as $testCase) {
            $args = isset($testCase['args']) ? (array) $testCase['args'] : [];
            $testCase['result'] = call_user_func_array([new $this->class, $testCase['method']], $args);
            $results[] = $testCase;
        }

        return $results;
    }
}
