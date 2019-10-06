<?php

namespace Model\Contact;

class Contact
{
    private $id;
    private $firstName;
    private $lastName;
    private $email;
    private $content;

    public function GetId()
    {
        return $this->id;
    }

    public function SetId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function GetFirstName()
    {
        return $this->firstName;
    }

    public function SetFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function GetLastName()
    {
        return $this->lastName;
    }

    public function SetLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function GetEmail()
    {
        return $this->email;
    }

    public function SetEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    public function GetContent()
    {
        return $this->content;
    }

    public function SetContent($content)
    {
        $this->content = $content;

        return $this;
    }
}