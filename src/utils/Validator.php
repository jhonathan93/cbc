<?php
class Validator {
    private $errors = [];

    /**
     * @param array $data
     * @param array $rules
     *
     * @return void
     * @throws Exception
     */
    public function validate(array $data, array $rules) {
        foreach ($rules as $field => $ruleString) {
            $rulesArray = explode('|', $ruleString);

            foreach ($rulesArray as $rule) {
                $this->applyRule($field, $rule, $data[$field] ?? null);
            }
        }
    }

    /**
     * @param string $field
     * @param string $rule
     * @param $value
     *
     * @return void
     * @throws Exception
     */
    private function applyRule(string $field, string $rule, $value): void {
        switch ($rule) {
            case 'required':
                if (empty($value) && $value !== '0') throw new Exception("O campo {$field} é obrigatório");
            break;
            case 'string':
                if (!is_string($value)) throw new Exception("O campo {$field} deve ser uma string");
            break;
            case 'numeric':
                if (!is_numeric($value)) throw new Exception("O campo {$field} deve ser um numero");
            break;
            case 'positive_numeric':
                if (!is_numeric($value)) throw new Exception("O campo {$field} deve ser um número");

                if ($value < 0) throw new Exception("O campo {$field} deve ser um número positivo");
            break;
        }
    }
}