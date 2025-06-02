<?php
require_once 'Page.php';

class UserProfilePage extends Page
{
    private $userId;

    public function __construct($title, $userId)
    {
        parent::__construct($title);
        $this->userId = $userId;
    }

    public function ShowContent()
    {
        $userId = $this->userId;
        include "Parts/user_profile.phtml";
    }
}
?>
