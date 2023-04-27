<?php
declare(strict_types=1);



class PersonRepository
{
    public function getAllPeople()
    {
        $json_data = file_get_contents('../Database/data.json');
        $data = json_decode($json_data, true);
        $people = [];

        foreach ($data as $row) {
            $id = isset($row['id']) ? $row['id'] : '';
            
            $firstName = isset($row['firstName']) ? $row['firstName'] : '';
            $lastName = isset($row['lastName']) ? $row['lastName'] : '';
            $phoneNumbers = isset($row['phoneNumbers']) ? $row['phoneNumbers'] : [];

            $person = new Person($id,$firstName, $lastName, $phoneNumbers);
            $people[] = $person;

        }
       

        //prevrushta gi v modeli
        return $people;
    }

    public function setAllPeople(array $people)
    {
        $formattedPeople = [];
        foreach ($people as $person) {
            $formattedPerson = [
                'id'=>$person->getId(),
                'firstName' => $person->getFirstName(),
                'lastName' => $person->getLastName(),
                'phoneNumbers' => $person->getPhoneNumbers(),
            ];
            $formattedPeople[] = $formattedPerson;
        }

        $json_data = json_encode($formattedPeople);
        file_put_contents('../Database/data.json', $json_data);
    }

    public function getMaxID()
    {
        $json_data = file_get_contents('../Database/data.json');
        $data = json_decode($json_data, true);
    
        $maxID = 0;
        foreach ($data as $row) {
            $id = isset($row['id']) ? $row['id'] : 0;
            $maxID = max($maxID, $id);
        }
    
        return $maxID;
    }
}
