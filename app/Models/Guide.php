<?php
/**
 * Created by PhpStorm.
 * User: FlaviusZichil
 * Date: 1/5/2019
 * Time: 7:24 PM
 */

namespace App\Models;


use Framework\Model;

class Guide extends Model
{
    protected $table = "guide";

    public function deleteGuideById($guideId){
        $this->deleteById($guideId, "guide_id");
    }

    public function addNewGuide($firstName, $secondName, $experience, $city){
        $db = $this->newDbCon();
        $stmt = $db->prepare("INSERT INTO $this->table(first_name, second_name, years_of_experience,city) VALUES(?, ?, ?, ?)");
        $stmt->execute([$firstName, $secondName, $experience, $city]);
    }
}