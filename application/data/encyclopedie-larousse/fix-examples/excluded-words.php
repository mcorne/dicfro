<?php return array(
    // examples of excluded words
    // entries beginning with these words will be ignored by extract-entries.php
    // note that the words will be converted to upper case ascii before use

    "Principaux",                            // excludes entries beginning with the word "Principaux" in any page
    "Précipitations" => 8689,                // excludes entries beginning with the word "Précipitations" in page 8689 only
    "Propriétés" => array(8691, 8835, 8953), // excludes entries beginning with the word "Propriétés" in page 8691, 8835, 8953 only
);
