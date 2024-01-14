<?php

use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    public function testReadCSV()
    {
        $testFilePath = 'path/to/your/test.csv'; // Remplacez cela par le chemin de votre fichier de test CSV
        $expectedResult = [
            ['col1', 'col2', 'col3'],
            ['value1', 'value2', 'value3'],
            // ... Ajoutez d'autres lignes de données CSV d'exemple ici
        ];

        // Créez un fichier de test CSV avec des données d'exemple
        $this->createTestCSVFile($testFilePath, $expectedResult);

        // Appelez la fonction readCSV avec le chemin du fichier de test
        $result = readCSV($testFilePath);

        // Vérifiez que le résultat correspond à ce qui est attendu
        $this->assertEquals($expectedResult, $result);
    }

    private function createTestCSVFile($filePath, $data)
    {
        $handle = fopen($filePath, 'w');

        if ($handle === FALSE) {
            echo 'Erreur lors de l\'ouverture du fichier de test CSV.';
            return;
        }

        // Écrivez les données dans le fichier CSV de test
        foreach ($data as $row) {
            if (fputcsv($handle, $row) === FALSE) {
                echo 'Erreur lors de l\'écriture des données dans le fichier CSV de test.';
            }
        }

        fclose($handle);
    }
}
?>
