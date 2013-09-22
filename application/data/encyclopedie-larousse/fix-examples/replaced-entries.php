<?php return array(
    // examples of entries replacements
    // those entries will be replaced by extract-entries.php

    // replaces plural form by its singular so entries can be found by their singular form
    // letters between brackets will be ingnored by the parser when creating the index
    "pointes (pouvoir des)", // replaces "pointes (pouvoir des)" with "pointe[s] (pouvoir de[s])" in any page
    "poux" => 123,           // replaces "poux" with "pou[x]" in page 123 only

    // replaces entries with other entries
    // this is mainly used for truncated or incomplete entries or typos,
    // and occasionaly as a workaround to fix sorting issues
    "Polarisation par" => "Polarisation par biréfringence", // replaces "Polarisation par" with "Polarisation par biréfringence" in any pages
    "populaire" => array("populaire (littérature)", 8820),  // replaces "populaire" with "populaire (littérature)" in page 8820 only
    "Succession" => array(                                  // replaces "Succession" depending on the page
        array("Succession d'Autriche (guerre de la)", 10456),
        array("Succession d'Espagne (guerre de la)", 10458)),
);