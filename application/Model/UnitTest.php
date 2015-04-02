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
    public $object;
    public $testCases;
    public $testResults;

    public function run()
    {
        $results =  [];

        foreach ($this->testCases as $testCase) {
            if (! isset($testCase['status'])) {
                $args = isset($testCase['args']) ? (array) $testCase['args'] : [];
                $object = $this->setObject();

                if (isset($testCase['properties'])) {
                    $this->setProperties($object, $testCase['properties']);
                }

                $testCase['result'] = call_user_func_array([$object, $testCase['method']], $args);
            }

            $results[] = $testCase;
        }

        return [$results, $this->class];
    }

    public function setObject()
    {
        $object = new $this->class();

        return $object;
    }

    /**
     * @param object $object
     * @param array $properties
     */
    public function setProperties($object, &$properties)
    {
        foreach ($properties as $name => &$value) {
            if (isset($value['callback'])) {
                $value = call_user_func([$this, $value['callback']]);
            }

            $exported = var_export($value, true);
            $code = sprintf('$object->%s = %s;', $name, $exported);
            eval($code);
        }
    }
}
