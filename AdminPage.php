<?php
require_once 'Page.php';

class AdminPage extends Page
{
    public function ShowContent()
    {
        include "Parts/admin_dashboard.phtml";
    }

    public function ShowModerationPanel()
    {
        include "Parts/moderation_panel.phtml";
    }
}
?>
