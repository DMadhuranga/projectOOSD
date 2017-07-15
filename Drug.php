<?php
include('drugbatch.php');
class Drug{
    var $drug_name;
    var $serial_number;
    var $type;
    var $description;
   // $batches  = array('' => , );
 //   var $email;
 //   var $password;
 //   var $last_name;
    /**
     * @return mixed
     */
    public function getDName()
    {
        return $this->drug_name;
    }

    /**
     * @param mixed $u_name
     */
    public function setDName($drug_name)
    {
        $this->drug_name = $drug_name;
    }

    /**
     * @return mixed
     */
    public function getSerialNumber()
    {
        return $this->serial_number;
    }

    /**
     * @param mixed $first_name
     */
    public function setSerialNumber($serial_number)
    {
        $this->serial_number = $serial_number;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $role_id
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $u_id
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param mixed $email
     */
    public function setDeleted($deleted)
    {
        $this->email = $email;
    }

    

}

?>