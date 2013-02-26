<?php
/***********************************************************************
 ************* D A T A B A S E   C L A S S *****************************
 * *********************************************************************
 *
 * Filename:    db.class.php
 * Version:     1.1
 * Author:      Mr.DoT <admin@mrdot.it>
 * License:     GNU/GPL
 * Release:     16th November, 2012
 * Description: Questo script consente una gestione più semplificata del
 *              database MySQL da script PHP
 *
 * Changelog:
 *      1.1     - Privatizzate le variabili utilizzate per connettersi
 *                al database (utente, password, ecc.);
 *              - Eliminato le variabili globali, snellendo di parecchio
 *                tutto il codice;
 *             
 *      1.0 - Versione iniziale
 * ********************************************************************/
 
class DataBase
{
    private $connected = false; // Variabile di controllo che rileva se
                                // la connessione al
                                // database è attiva oppure no
    private $lastConnection;    // Variabile che identifica l'ultimo
                                // collegamento al database
    private $dbhost,            // Indirizzo server MySQL
            $dbuser,            // Nome utente MySQL
            $dbpwd,             // Password MySQL
            $dbname;            // Nome database
 
 // Costruttore di classe
    public function __construct()
    {
        // Includo il file contenente le informazioni sul database
        include_once(dirname(__FILE__)."/gw_config.php");
 
        // Inizializzo le variabili di classe, per accedere a MySQL
        $this->dbhost = HOST;
        $this->dbuser = USER;
        $this->dbpwd = PASSWORD;
        $this->dbname = DBNAME;
    }
 
    // Apre la connessione con il database
    public function OpenConnection()
    {
        // Se è già connesso, esco dalla funzione
        if ($this->connected)
            return $this->lastConnection;
 
        // Effettuo la connessione al database
        $link = mysql_connect($this->dbhost, $this->dbuser, $this->dbpwd);
 
        // Se non ci sono stati errori di connessione:
        if ($link)
        {
            // Attivo la connessione
            $this->connected = true;
            // Memorizzo l'ultima connessione
            $this->lastConnection = $link;
            // Esco dalla funzione
            return $link;
        }
        // Se ci sono stati errori di connessione restituisco FALSE
        return false;
    }
 
    // Chiude la connessione con il database
    public function CloseConnection($link=null)
    {
        // Se è già disconnesso, esco dalla funzione
        if (!$this->connected)
            return true;
 
        // Se non viene specificato $link uso l'ultimo collegamento
        if (!$link)
            $link = $this->lastConnection;
 
         // Se la disconnessione dal database è avvenuta senza errori:
        if (mysql_close($link))
        {
            // Disattivo la connessione ed esco dalla funzione
            $this->connected = false;
            return true;
        }
        // Se invece ci sono stati errori esco dalla funzione con FALSE
        return false;
    }
 
    // Seleziona un database
    public function SelectDatabase($db_name=null, $link=null)
    {
        // Imposto il nome del database di default, se non specificato
        if (!$db_name)
            $db_name = $this->dbname;
 
        // Imposto la connessione di default, se non viene specificato
        if (!$link)
            $link = $this->lastConnection;
 
        // Seleziono il database. Se non ci sono errori esco dalla funzione
        if (mysql_select_db($db_name, $link))
            return true;
 
        // Se invece ci sono errori esco dalla funzione con FALSE
        return false;
    }
 
    // Esegue una query SQL
    public function Query($query, $returnID = false,$db_name=null)
    {
        // Mi connetto al database (se disconnesso)
        $link = $this->OpenConnection();
        if (!$link)
            return false;
 
        // Seleziono il database
        if (!$this->SelectDatabase($db_name))
            return false;
 
        // Eseguo la query
        $result = mysql_query($query, $link);
		if( $returnID) { $result = mysql_insert_id($link); }
        if (!$result)
            return false;
 
        // Mi disconnetto dal database (se connesso)
        if (!$this->CloseConnection())
            return false;

		
		// Infine, se non ci sono problemi, esco dalla funzione
        // restituendo il risultato della query
        return $result;
}
 
    // Calcola il numero di righe di una determinata query
    public function NumRows($query)
    {
        // Esegue una query
        $result = $this->Query($query);
 
        // Restituisce il numero di righe della query presa in oggetto
        return mysql_num_rows($result);
    }
 
    // Ottiene un array di valori di una singola riga (la prima trovata),
    // in base alla query,
    // oppure un singolo valore, specificando $key (indice della riga)
    public function GetRow($query, $key=null)
    {
        // Ottengo i risultati della query
        $result = $this->Query($query);
 
        // Ottengo l'array della prima riga, dai risultati di prima
        $row = mysql_fetch_array($result);
 
        // Se è specificato il nome della colonna ($key) restituisco
        // il valore specifico, altrimenti tutta la riga ($row)
        if ($key)
            return $row[$key];
        else
            return $row;
    }

    public function Clean($value,$db_name=null) {
        // Mi connetto al database (se disconnesso)
        $link = $this->OpenConnection();
        if (!$link)
            return false;
 
        // Seleziono il database
        if (!$this->SelectDatabase($db_name))  return false;
 
        $value = mysql_real_escape_string($value);
 
        // Mi disconnetto dal database (se connesso)
        if (!$this->CloseConnection())
            return false;

        
        // Infine, se non ci sono problemi, esco dalla funzione
        // restituendo il risultato della query
        return $value;
        }

}
?>