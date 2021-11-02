<?php

// преобразует числа 1-288885 в 'A0001'-'ZZZZZ'
class Converter {
    const CODE_LENGTH = 5;
    protected $upperArr;//массив символов A-Z
    protected $countUpperArr;//количество символов в массиве upperArr
    protected $coefficientMap = [];//массив коэффициентов для получения символов кода
    protected $number;//полученное число
    protected $code;//возвращаемый код

    function __construct($number) {
        $this->validate($number);
        $this->number = $number;
        $this->upperArr = range('A', 'Z');
        $this->countUpperArr = count($this->upperArr);
        
        for ($i = 0; $i < self::CODE_LENGTH; $i++){
            $exponent = self::CODE_LENGTH - $i - 1;
            $this->coefficientMap[$i] = pow(10, $exponent);
        }
    }

    public function getCode() {
        $this->setFirstLetter();
        if ($this->number < $this->coefficientMap[0]){
            $this->addZerosAndNumber();
            return $this->code;
        }
        
        $this->setOtherLetters();
        return $this->code;
    }
    // валидация полученного числа
    protected function validate($number) {
        if (gettype($number) !== 'integer'){
            throw new Exception('получено не число');
        }
        if ($number < 1){
            throw new Exception('число меньше 1');
        }
    }
    // получение первого символа кода
    protected function setFirstLetter() {
        $quotient = intdiv($this->number, $this->coefficientMap[0]);
        if ($quotient >= $this->countUpperArr){
            $this->code .= end($this->upperArr);
            $quotient = $this->countUpperArr - 1;
        } else {
            $this->code .= $this->upperArr[$quotient];
        }
        $this->number = $this->number - $quotient*$this->coefficientMap[0];
    }
    // получение остальных символов кода
    protected function setOtherLetters() {
        for ($i = 1; $i < self::CODE_LENGTH; $i++){
            $coefficient = $this->coefficientMap[$i];
            $quotient = intdiv($this->number, $coefficient);
            $quotient = $quotient - 10;
            if ($quotient >= $this->countUpperArr){
                $this->code .= end($this->upperArr);
                $quotient = $this->countUpperArr - 1;
            } else {
                $this->code .= $this->upperArr[$quotient];
            }
            $this->number = $this->number - ($quotient+10)*$coefficient;
            
            if ($this->number < $coefficient){
                $this->addZerosAndNumber();
                return $this->code;
            }
        }
        throw new Exception('число больше максимально допустимого');
    }
    // добавление нулей и остатка числа в конец кода
    protected function addZerosAndNumber() {
        if (strlen($this->code) === self::CODE_LENGTH){
            return;
        }
        $countZeros = self::CODE_LENGTH - strlen($this->code) - strlen((string)$this->number);
        $this->code = $this->code . str_repeat('0', $countZeros) . $this->number;
    }
} 
