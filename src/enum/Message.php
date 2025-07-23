<?php
namespace AppDAF\ENUM;


enum Message: string
{
    case REQUIRED = "Le champ :field est requis.";
    case EMAIL = "Le champ :field doit être un email valide.";
    case PHONE = "Le champ :field doit être un numéro de téléphone valide.";
    case MIN_LENGTH = "Le champ :field doit contenir au moins :length caractères.";
    case SAME = "Le champ :field doit être identique au champ :otherField.";
    case ALPHA_NUM = "Le champ :field doit être alphanumérique.";

    public function format(array $replacements = []): string
    {
        $message = $this->value;
        foreach ($replacements as $key => $value) {
            $message = str_replace(":$key", $value, $message);
        }
        return $message;
    }
}
