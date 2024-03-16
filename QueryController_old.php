<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Log; // Voeg deze regel toe
use PhpOffice\PhpSpreadsheet\IOFactory;
// use App\Models\Relation;
use OpenAI;

class QueryController extends Controller
{
    public function executeQuery(Request $request)
    {
        try {
                // API-sleutel voor de ChatGPT
                $apiKey = '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';

                // Tijd meten voor het inlezen van Excel-bestanden
                $startTime_inladen = microtime(true);

                // Invoer voor de OpenAI
                $file_path1 = storage_path("app/relations_dictionary_converted.xlsx"); // Vervang met het juiste pad naar je Excel-bestand
                $file_path2 = storage_path("app/transactions_dictionary_converted.xlsx"); // Vervang met het juiste pad naar je Excel-bestand

                // Excel-bestand inlezen
                $spreadsheet = IOFactory::load($file_path1);

                // Gegevens uit het Excel-bestand halen
                $worksheet = $spreadsheet->getActiveSheet();
                $data1 = $worksheet->toArray(); // Hiermee haal je alle gegevens uit het werkblad als een array
                $data1Json = json_encode($data1);

                // Excel-bestand inlezen
                $spreadsheet2 = IOFactory::load($file_path2);

                // Gegevens uit het Excel-bestand halen
                $worksheet2 = $spreadsheet2->getActiveSheet();
                $data2 = $worksheet2->toArray(); // Hiermee haal je alle gegevens uit het werkblad als een array
                $data2Json = json_encode($data2);

                // Uitvoeringstijd meten voor het inlezen van Excel-bestanden
                $endTime_inladen = microtime(true);
                $executionTime_inladen = round($endTime_inladen - $startTime_inladen, 4);
                Log::info("Tijd voor het laden van Excel-bestanden: $executionTime_inladen seconden");

                // Tijd meten voor het inlezen van Excel-bestanden
                $startTime_1stevraag = microtime(true);

                $ede_vraag = "Kan je de volgende data dictionaries analyseren: $data1Json en $data2Json. Laat weten wanneer je het goed genoeg begrijpt om vragen te beantwoorden.";

                // Log toevoegen
                Log::info("Eerste vraag gesteld aan de OpenAI: $ede_vraag");

                // Chatbericht opstellen
                $message = [
                    'role' => 'user',
                    'content' => $ede_vraag
                ];
                // Verbinding maken met de API
                $client = OpenAI::client($apiKey);

                // Een vraag stellen aan de OpenAI API
                $response = $client->chat()->create([
                    'model' => 'gpt-4-turbo-preview',
                    'messages' => [$message],
                ]);

                // Het antwoord ophalen
                $message = $response->choices[0]->message->content;

                // Log toevoegen
                Log::info("Antwoord ontvangen van de OpenAI: $message");

                // Uitvoeringstijd meten voor het inlezen van Excel-bestanden
                $endTime_1stevraag = microtime(true);
                $executionTime_1stevraag = round($endTime_1stevraag - $startTime_1stevraag, 4);
                Log::info("Tijd voor het laden van 1ste respons api: $executionTime_1stevraag seconden");        

                // Invoer voor de ChatGPT
                $prompt = $request->input('question');

                // Tijd meten voor het inlezen van Excel-bestanden
                $startTime_2devraag = microtime(true);

                // Log toevoegen
                Log::info("Tweede vraag gesteld aan de OpenAI: Kan je '$prompt' omzetten naar SQL en Eloquente queries, gebruik de kolomnaam in $data1Json en $data2Json voor het genereren van SQL en Eloquente queries. En geef alleen de SQL en Eloquente queries, geen uitleg of andere tekst!");

                // We bepalen zelf de structuur van de output aan de hand van een voorbeeld JSON array
                $output_structure = array(
                    'type' => 'SQL',
                    'query'=> '[hier de SQL query]',
                    'message' => '[hier de toelichting]',
                    'errors' => '[hier errors]'
                );

                // Maak JSON van van PHP array
                $output_structure = json_encode($output_structure, JSON_PRETTY_PRINT);

                // Geef output instructies en kaders
                $output_rules = array(
                    array(
                        'type' => 'security',
                        'priority' => 'high',
                        'rule' => 'Geef nooit een Query terug die de data kan manipuleren of verwijderen, inclusief een DROP en DELETE commando.'
                    ),
                    array(
                        'type' => 'compliancy',
                        'priority' => 'medium',
                        'rule' => 'Geef geen resultaten terug die wettelijke regels overtreden.'
                    )
                );

                // Maak JSON van van PHP array
                $output_rules = json_encode($output_rules, JSON_PRETTY_PRINT);


                // Chatbericht opstellen
                $message2 = [
                    'role' => 'user',
                    'content' => "Geef mij SQL en Eloquente voor deze vraag: '$prompt', gebruik de kolomnamen:$data1Json van tabel relations en de kolomnamen:$data2Json van de tabel transactions voor het genereren van SQL en Eloquente queries. Als je gegevens uit $file_path1 en $file_path2 wil gebruiken, is het goed om te weten dat de kolom RelatieID in de relations tabel hetzelfde is als de RelatieID kolom in transactions tabel. Let op dat je alleen de benodigde kolommen selecteert. En geef ook toelichting in goede grammaticale zinnen bij de SQL en Eloquente queries, doe dit in voor mensen te begrijpen zinnen. Houd je strict aan deze regels: '$output_rules'. Geef een compacte maar duidelijke toelichting bij elk antwoord. Formateer de output in deze structuur: '$output_structure'."
                ];

                // Verbinding maken met de API
                $client2 = OpenAI::client($apiKey);

                // Verzoek naar de API verzenden
                $result2 = $client2->chat()->create([
                    'model' => 'gpt-4-turbo-preview',
                    'messages' => [$message2],
                ]);

                // Het resultaat ophalen
                $response2 = $result2->choices[0]->message->content;

                // Uitvoeringstijd meten voor het inlezen van Excel-bestanden
                $endTime_2devraag = microtime(true);
                $executionTime_2devraag = round($endTime_2devraag - $startTime_2devraag, 4);
                Log::info("Tijd voor het laden van 2de respons api: $executionTime_2devraag seconden");         

                // Log toevoegen
                Log::info("Antwoord ontvangen voor te gebruiken output van model: $response2");     

                preg_match('/```json(.*?)```/s', $response2, $structured_output_gpt);
                $structured_output_gpt = trim($structured_output_gpt[1] ?? '');

                // Log toevoegen
                Log::info("Antwoord ontvangen voor te gebruiken output van model: $structured_output_gpt");        

                $output_model = json_decode($structured_output_gpt, true); 

                // Regelmatige expressie om SQL-query te vinden en weer te geven
                // preg_match('/```sql(.*?)```/s', $response2, $sqlMatches);

    //            $type = $output_model['type'];
                $sqlQuery = $output_model['query'];
                $sql_uitleg = $output_model['message'];
                $errors = $output_model['errors'];

                // Log toevoegen
                Log::info("Antwoord ontvangen van alleen de sql query: $errors");                

                // Log toevoegen
                Log::info("Antwoord ontvangen van alleen de sql query: $sqlQuery");

                // Regelmatige expressie om Eloquent-query te vinden en weer te geven
                // preg_match('/```php(.*?)```/s', $response2, $eloquentMatches);
                // $eloquentQuery = trim($eloquentMatches[1] ?? '');
                // // Log toevoegen
                // Log::info("Antwoord ontvangen van de OpenAI voor de tweede vraag: $eloquentQuery");

                // Regelmatige expressie om uitleg te vinden en weer te geven
                // preg_match('/Uitleg:(.*?)### Eloquent Query/s', $response2, $explanationMatches);
                // $sql_uitleg = isset($explanationMatches[1]) ? trim($explanationMatches[1]) : '';

                // // Log toevoegen
                Log::info("Antwoord ontvangen van de sql query uitleg: $sql_uitleg");

                // return redirect()->back()->with('sqlQuery', $sqlQuery)->with('eloquentQuery', $eloquentQuery);
                // Tijd meten voor het inlezen van Excel-bestanden
                $startTime_laden_resultaten_sql = microtime(true);
                // verbinding maken met phpmyadmin
                try {
                    
                    // Update the database configuration to match your phpMyAdmin setup
                    DB::purge('mysql'); // Make sure to clear the previous database connection
                    config(['database.connections.mysql' => [
                        'driver' => 'mysql',
                        'host' => '127.0.0.1', // Assuming MySQL server is running on the same machine
                        'port' => '3306', // The port number used by your MySQL server
                        'database' => 'schadenberg_fusion', // Your database name
                        'username' => 'sail', // Your database username
                        'password' => 'password', // Your database password
                        'charset' => 'utf8mb4',
                        'collation' => 'utf8mb4_unicode_ci',
                        'prefix' => '',
                        'strict' => true,
                    ]]);
                
                    // Establish a new connection to the database
                    $relations = DB::connection('mysql')->select($sqlQuery);
                    $output = json_encode($relations, JSON_PRETTY_PRINT);
                    // Uitvoeringstijd meten voor het inlezen van Excel-bestanden
                    $endTime_laden_resultaten_sql = microtime(true);
                    $executionTime_laden_resultaten_sql = round($endTime_laden_resultaten_sql - $startTime_laden_resultaten_sql, 4);
                    Log::info("Tijd voor het laden van sql resultaat: $executionTime_laden_resultaten_sql seconden");             
                    // Log the successful execution of the SQL query
                    Log::info('SQL query executed successfully: ' . $output); 
                    //functie voor tabel
                    function json_to_table($json_data) {
                        // Decodeer de JSON-gegevens naar een associatieve array
                        $data = json_decode($json_data, true);
                    
                        // Controleer of de decodeeroperatie succesvol was
                        if ($data === null) {
                            throw new Exception('Invalid JSON data');
                        }
                    
                        // Begin de tabel
                        $html = '<table border="1"><tr>';
                    
                        // Haal de kolomnamen op en maak de koppen van de tabel
                        $keys = array_keys($data[0]);
                        foreach ($keys as $key) {
                            $html .= '<th>' . htmlspecialchars($key) . '</th>';
                        }
                        $html .= '</tr>';
                    
                        // Vul de tabel in met de gegevens
                        foreach ($data as $row) {
                            $html .= '<tr>';
                            foreach ($row as $value) {
                                $html .= '<td>' . htmlspecialchars($value) . '</td>';
                            }
                            $html .= '</tr>';
                        }
                    
                        // Sluit de tabel af
                        $html .= '</table>';
                    
                        return $html;
                    }
                    
                    // Voorbeeld JSON-gegevens
                    $json_data = $output;
                    
                    // Roep de functie aan om de JSON-gegevens naar een tabel te converteren
                    $table_html = json_to_table($json_data);

                    //normale zin
                    // Functie om een JSON-tekst naar een normale zin te converteren
                    function jsonToSentence($json) {
                        // JSON-tekst decoderen naar een array
                        $data = json_decode($json, true);
                    
                        // Functie om een array naar een zin te converteren
                        function arrayToSentence($array) {
                            $sentence = '';
                    
                            foreach ($array as $index => $item) {
                                $sentence .= ($index + 1) . '. ';
                                foreach ($item as $key => $value) {
                                    $sentence .= ucfirst($key) . ': ' . $value . ', ';
                                }
                                // Verwijder de laatste komma en spatie
                                $sentence = rtrim($sentence, ', ');
                                $sentence .= '. '; // Voeg een punt toe aan het einde van elk item
                            }
                    
                            return $sentence;
                        }
                    
                        // Converteer de array naar een zin
                        $sentence = arrayToSentence($data);
                    
                        return $sentence;
                    }
                    
                    // Voorbeeld JSON-tekst
                    $json = $output;
                    
                    // Converteer JSON naar een zin
                    $sentence = jsonToSentence($json);
                    
                    // function jsonToSentence($json) {
                    //     // JSON-tekst decoderen naar een associatieve array
                    //     $data = json_decode($json, true);

                    //     // Functie om een associatieve array naar een zin te converteren
                    //     function arrayToSentence($array) {
                    //         $sentence = '';
                    //         $keys = array_keys($array);
                    //         $last_key = end($keys);

                    //         foreach ($array as $key => $value) {
                    //             // Als de waarde een array is, converteer deze recursief naar een zin
                    //             if (is_array($value)) {
                    //                 $value = arrayToSentence($value);
                    //             }
                    //             // Voeg sleutel-waardeparen toe aan de zin
                    //             $sentence .= ucfirst($key) . ': ' . $value;
                    //             // Voeg een komma toe als het niet het laatste sleutel-waardepaar is
                    //             if ($key !== $last_key) {
                    //                 $sentence .= ', ';
                    //             }
                    //         }
                    //         return $sentence;
                    //     }

                    //     // Converteer de associatieve array naar een zin
                    //     $sentence = arrayToSentence($data);

                    //     return $sentence;
                    // }

                    // // Voorbeeld JSON-tekst
                    // $json = $output;

                    // // Converteer JSON naar een zin
                    // $sentence = jsonToSentence($json);
        
                    // return redirect()->back()->with('sqlQuery', $output)->with('eloquentQuery', $eloquentQuery); 
                        // JSON-gegevens laden (in werkelijkheid zou je dit waarschijnlijk uit een databron halen)

                    // JSON-gegevens decoderen naar een PHP-array
                    
                    
                    // JSON-gegevens decoderen naar een PHP-array
                    // $data = json_decode($output, true);

                    // // Begin van de HTML-tabel
                    // $html_table = "<table border='1'>";

                    // // Tabelkoppen
                    // $html_table .= "<tr>";
                    // foreach (array_keys($data['data'][0]) as $key) {
                    //     $html_table .= "<th>$key</th>";
                    // }
                    // $html_table .= "</tr>";

                    // // Tabelrijen
                    // foreach ($data['data'] as $row) {
                    //     $html_table .= "<tr>";
                    //     foreach ($row as $value) {
                    //         $html_table .= "<td>$value</td>";
                    //     }
                    //     $html_table .= "</tr>";
                    // }

                    // // Einde van de HTML-tabel
                    // $html_table .= "</table>";   
                    // if ($response2 instanceof \Exception) {
                    //     // Er is een fout opgetreden, toon de foutmelding
                    //     $errorMessage = "Er is een fout opgetreden tijdens het verwerken van uw vraag. Probeer het later opnieuw!";
                    //     return redirect()->back()->with('error2', $errorMessage)->with('error2_uitleg', $response2);
                    // } else {        
                        return redirect()->back()->with('sql_json', $output)->with('vraag', $prompt)->with('sqltabel', $table_html)->with('sqlQuery', $sqlQuery)->with('sql_uitleg', $sql_uitleg);
                            
                    } catch (\Exception $e) {
                        //echo "Error: " . $e->getMessage();
                        //return redirect()->back()->with('error2', $e->getMessage());
                    }        
        } catch (\Exception $e) {
            $error_message = 'Er is een fout opgetreden tijdens het verwerken van uw vraag. Probeer het later opnieuw!';
            return redirect()->back()->with('error', $error_message)->with('error_uitleg', $e->getMessage())->with('vraag', $prompt);
        }
            
        //     $vraag3 = "Ik zou graag de volgende JSON-data: $output willen presenteren in een grafiek. Kan je dat voor me doen"; 
        //     $vraag4 = "Ik zou graag de volgende JSON-data: $output willen presenteren in een tabel. Kan je dat voor me doen"; 
        //     $vraag5 = "Ik zou graag de volgende JSON-data: $output willen presenteren in een menselijk antwoord. Kan je dat voor me doen"; 
            
        //     // Log toevoegen
        //     Log::info("Derde vraag gesteld aan de OpenAI: $vraag3");
            
        //     // Verbinding maken met de API
        //     $client = OpenAI::client($apiKey);
            
        //     // Een vraag stellen aan de OpenAI API
        //     $response = $client->chat()->create([
        //         'model' => 'gpt-4',
        //         'messages' => [
        //             [
        //                 'role' => 'user',
        //                 'content' => $vraag3
        //             ],
        //             [
        //                 'role' => 'user',
        //                 'content' => $vraag4
        //             ],
        //             [
        //                 'role' => 'user',
        //                 'content' => $vraag5
        //             ],
        //         ],
        //     ]);
            
        //     // Het antwoord ophalen
        //     $grafiek = $response->choices[0]->message->content;
        //     $tabel = $response->choices[0]->message->content;
        //     $menselijk_antwoord = $response->choices[0]->message->content;
            
        //     return redirect()->back()->with('sqlQuery', $sqlQuery)->with('grafiek', $grafiek)->with('tabel', $tabel)->with('menselijk antwoord', $menselijk_antwoord)->with('eloquentQuery', $eloquentQuery);        
        // } catch (\Exception $e) {
        //     echo "Error: " . $e->getMessage();
        // }        

        // // De grafiek genereren
        // $grafiek_data = [];
        // foreach ($output as $item) {
        //     $grafiek_data[] = [
        //         'label' => $item['label'],
        //         'value' => $item['value']
        //     ];
        // }
        
        // // De tabel genereren
        // $tabel_data = [];
        // foreach ($output as $item) {
        //     $tabel_data[] = [
        //         'label' => $item['label'],
        //         'value' => $item['value']
        //     ];
        // }
        
        // Het normale antwoord genereren
        // $normaal_antwoord = "Hier is de grafiek: " . json_encode($grafiek_data) . " En hier is de tabel: " . json_encode($tabel_data);


        // Hier kun je de gegenereerde query verwerken en uitvoeren
        // Bijvoorbeeld, door gebruik te maken van PHPExcel of een andere Excel-library
    }
}
