<?php

declare(strict_types=1);

include '../Models/Person.php';
include '../Database/Repositories/PersonRepository.php';

$call = new PersonController();

class PersonController
{
    private PersonRepository $personRepository;


    private $person;
    private $idCounter = 0;

    public function __construct()
    {

        $this->personRepository = new PersonRepository();
        $this->idCounter = $this->personRepository->getMaxId() + 1;
        echo $this->showHomePage();
        try {
            if (isset($_POST['firstName']) && isset($_POST['lastName']) && isset($_POST['phone'])) {
                $id = $this->idCounter++;
                $firstName = $_POST['firstName'];
                $lastName = $_POST['lastName'];
                $phone = $_POST['phone'];


                $this->person = new Person($id, $firstName, $lastName, [$phone]);
            }


            echo $this->handleFormData($this->person);
        } catch (Exception $e) {
            echo 'Грешка: ' . $e->getMessage(); // изписва текста на грешката
        }
    }

    public function showHomePage() //raboti
    {
        $file = file_get_contents('../Views/index.html');
        $result = sprintf($file);
        return $result;
        //        readfile('../Views/test.html');
    }

    private function handleFormData($person) // ush raboti
    {
        $personPhoneBook = $this->personRepository->getAllPeople();
        if (isset($_POST['submit'])) {
            $personFound = false;
            foreach ($personPhoneBook as $index => $search) {
                if ($search->getFirstName() === $person->getFirstName() && $search->getLastName() === $person->getLastName()) {
                    $this->editPhone($person);
                    $personFound = true;
                    break;
                }
            }
            if (!$personFound) {
                $this->createNewPerson($person);
            }
        } elseif (isset($_POST['newNumber'])) {
            $this->addPhoneToPerson($person);
        }
        echo $this->generateHtml($this->personRepository->getAllPeople());
    }

    private function editPhone($person)
    {
        $personPhoneBook = $this->personRepository->getAllPeople();
        foreach ($personPhoneBook as $index => $savedPerson) {
            if ($savedPerson->getFirstName() === $person->getFirstName() && $savedPerson->getLastName() === $person->getLastName()) {
                foreach ($savedPerson->getPhoneNumbers() as $phoneIndex => $phoneNumber) {
                    if (isset($_POST['phoneNumbersSelect']) && $phoneNumber === $_POST['phoneNumbersSelect']) {
                        $phoneNumbersSelect = $_POST['phoneNumbersSelect'];
                        $selectedPhoneNumberIndex = array_search($phoneNumbersSelect, $savedPerson->getPhoneNumbers());
                        $person->setId($savedPerson->getId()); // Задаване на идентификатора на записа на човека
                        $savedPerson->setPhoneNumbers(array_replace($savedPerson->getPhoneNumbers(), [$selectedPhoneNumberIndex => $person->getPhoneNumbers()[$selectedPhoneNumberIndex]]));
                        break;
                    }
                }
                break;
            }
        }
        return $this->personRepository->setAllPeople($personPhoneBook);
    }


    private function createNewPerson(Person $person) //raboti perfektno
    {
        $personPhoneBook = $this->personRepository->getAllPeople();
        array_push($personPhoneBook, $person);
        return $this->personRepository->setAllPeople($personPhoneBook);
    }



    private function addPhoneToPerson($person)
    {
        $personPhoneBook = $this->personRepository->getAllPeople();
        foreach ($personPhoneBook as $index => $savedPerson) {
            if ($savedPerson->getFirstName() === $person->getFirstName() && $savedPerson->getLastName() === $person->getLastName()) {
                $savedPerson->addPhoneNumber($_POST['phone']);
                break;
            }
        }
        return $this->personRepository->setAllPeople($personPhoneBook);
    }



    private function generateHtml($newPhoneBook) //raboti
    {
        $html = '';
        if (!empty($newPhoneBook)) {
            foreach ($newPhoneBook as $key => $person) {
                $color = 'red';
                if ($key % 2 == 0) {
                    $color = 'blue';
                }
                $html .= "<ul>";
                $html .= "<li>";
                $html .= "Name: ";
                $html .= " <span style='color: " . $color . ";'>" . $person->getFirstName() . "</span>";
                $html .= " <span style='color: " . $color . ";'>" . $person->getLastName() . "</span>";
                $html .= "<br>";
                $html .= "Phone: ";
                if ($person->getPhoneNumbers()) {
                    $html .= "<span style='color: " . $color . ";'>";
                    $html .=  implode(", ", $person->getPhoneNumbers());
                    $html .= "</span>";
                }
                $html .= "</li>";
                $html .= "</ul>";
            }

            return $html;
        }
    }
}
