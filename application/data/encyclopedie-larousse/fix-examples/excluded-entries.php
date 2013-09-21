<?php return array(
    // examples of excluded entries
    // those entries will be ignored by extract-entries.php
    // note that the entries will be converted to upper case ascii and sliced to the first 10 words before use

    "Le XIXe et le XXe s.",                      // excludes the entry "Le XIXe et le XXe s." in any page
    "réglementation des prix" => 8708,           // excludes the entry "réglementation des prix" in page 8708 only
    "préfabrication de la" => array(8913, 8914), // excludes the entry "préfabrication de la" in pages 8913, 8914 only
);