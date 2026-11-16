<?php

class LanguagePackModel
{
    private $pageName;

    public function __construct(string $pageName = '')
    {
        $this->pageName = $pageName;
    }

    public function loadLanguagePack(): array
    {
        session_start();
        $initialLanguage = $_SESSION["lang"] ?? null;

        $rootPath = $_SERVER['DOCUMENT_ROOT'];
        require_once realpath($rootPath . "/common/language/language.php");
        $lang = new Language();
        $lang->load($this->pageName);
        $rawLanguageArr = $lang->language;
        $currentLanguage = $lang->language_folder;
        $initialLanguage = $initialLanguage ?? $currentLanguage;

        foreach ($rawLanguageArr as $phraseKey => $phrase) {
            if (strpos($phraseKey, '.') !== false) {
                unset($rawLanguageArr[$phraseKey]);
            }
        }

        return [
            "initialLanguage" => $initialLanguage,
            "language" => $currentLanguage,
            "languagePhrases" => $rawLanguageArr
        ];
    }
}
