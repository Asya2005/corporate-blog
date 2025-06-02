<?php
class Korysuvach {
    public $name;
    public $email;

    public function __construct($name, $email) {
        $this->name = $name;
        $this->email = $email;
    }

    public function pryvitannya() {
        echo "Вітаю, $this->name!<br>";
    }

    public function chySluzhbovaPochta() {
        // службова пошта закінчується на @company.com
        if (strpos($this->email, '@company.com') !== false) {
            echo "Це службова пошта.<br>";
        } else {
            echo "Це НЕ службова пошта.<br>";
        }
    }
}
?>
