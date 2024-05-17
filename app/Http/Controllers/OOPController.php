<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OOPController extends ExampleAbstract implements UserInterface, HumanInterface
// class TestController extends Controller implements UserInterface, HumanInterface
{   
    // public function __construct(public ChildClassInterface $childClass)
    // {
    // }
    private $dog;
    private $cat;
    private $fish;

    public function __construct(Animal $dog){
        $this->dog = $dog;
      
    }
    public function index(){
        $this->test();
    }

    public function test(){
        // $this->getUsers();
        // $this->introduction();
        // dd( $this->childClass->getOccupation());
        // $this->abstractMethod();
        // $this->protectedMethod();
        $this->dog->makeSound();
    }
    public function getUsers(){
        // dd(UserInterface::AEZAKMI);
    }

    public function introduction(){
        dd('HELLO IM KEVIN');
    }

    public function abstractMethod(){
        dd('ABSTRACT METHOD');
    }
    public function protectedMethod(){
        echo 'Protected method OVERRIDE';
    }
}

interface UserInterface {
    const AEZAKMI = 'MONEY MONEY MONEY';
    public function getUsers();
}

interface HumanInterface {
    public function introduction();
}


class ParentClass {
    public function __construct(protected $name, protected $age) {}
}

interface ChildClassInterface
{
    public function getOccupation();
    // Add other methods as needed
}
class ChildClass extends ParentClass implements ChildClassInterface{
    public function __construct($name, $age, private string $occupation) {
        parent::__construct($name, $age);
    }


    public function getOccupation()
    {
        return $this->occupation;
    }
}

// $childClassInstance = new ChildClass('John Doe', 30, 'Engineer');
// $testController = new TestController($childClassInstance);
// $testController->index();

abstract class ExampleAbstract{
    public string $name ='Example ABSTRACT';
    abstract public function abstractMethod();

    protected function protectedMethod(){
        echo 'Protected method';
    }
}

interface Animal{
    public function makeSound();
}

class Dog implements Animal{
    public function makeSound(){
        echo 'AW AW AW';
    }
}
class Cat implements Animal{
    public function makeSound(){
        echo 'Meow Meow Meow';
    }
}
class Fish implements Animal{
    public function makeSound(){
        echo 'IM UNDER THE WATER';
    }
}

$dog = new Dog();
// $fish = new Fish();
// $cat = new Cat();
$testController = new TestController($dog);
$testController->index();