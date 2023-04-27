<?Php

declare(strict_types=1);

class Person
{


    private int $id;

    private string $firstName;
    private string $lastName;
    private array $phoneNumbers;


    public function __construct(int $id, string $firstName, string $lastName, array $phoneNumbers)
    {
        $this->setFirstName($firstName);
        $this->setLastName($lastName);
        $this->setPhoneNumbers($phoneNumbers);
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value){
        $this->id = $value;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $value): void
    {

        if (strlen($value) == 0) {
            throw new Exception("Please fill  the FirstName field ");
        }
        $this->firstName = $value;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $value): void
    {
        if (strlen($value) == 0) {
            throw new Exception("Please fill  the LastName field ");
        }

        $this->lastName = $value;
    }

    public function getPhoneNumbers(): array
    {
        return $this->phoneNumbers;
    }

    public function setPhoneNumbers(array $value): void
    {
        foreach ($value as $phoneNumber) {

            if (strlen($phoneNumber) < 10) {
                throw new Exception("Phone Number cant be less than 10 symbols");
            }


            $this->phoneNumbers = $value;
        }
    }

    public function addPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumbers[] = $phoneNumber;
    }
}
