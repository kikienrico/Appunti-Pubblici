# PHP - Introduzione e Funzionamento

## 1. Presentazione di PHP

**PHP** (Hypertext Preprocessor) è un linguaggio di scripting, eseguito **lato server**, utilizzato e pensato per creare **pagine web dinamiche**.

### Caratteristiche:
- Viene interpretato dal server e **non è visibile dal client** (browser).
- Si inserisce tra i tag `<?php ... ?>` all’interno dell’HTML.
- Genera contenuti dinamici (es. personalizzati per l’utente o connessi ad un database).

### Vantaggi:
- Open Source e gratuito  
- Compatibile con molti sistemi (Linux, Windows, macOS)  
- Integrazione semplice con database (MySQL, PostgreSQL, etc...)  
- Ampio supporto e community  

### Esempio base:

```php
<?php
$nome = "Studente";
echo "Ciao, " . $nome . "! Benvenuto sul sito del Gastaldi.";
?>
```

**Output per il browser:**  
`Ciao, Studente! Benvenuto sul sito del Gastaldi.`

---

## 2. Scambio di Informazioni tra Server e Client

Il protocollo usato per la comunicazione web è **HTTP**. (modello **client-server**)
### Flusso di una richiesta:
1. Il browser invia una **richiesta HTTP (GET o POST)** al server.
2. Il server elabora la richiesta (esegue uno script PHP).
3. Il server invia una **risposta HTTP** con il contenuto (HTML, JSON, ecc.).
4. Il browser interpreta la risposta e mostra la pagina.

### Ruoli:
- **Client (browser)**: invia richieste, riceve risposte.
- **Server (es. Apache, Nginx)**: riceve richieste, esegue script e risponde.

### Esempio:

> L’utente visita `http://sito.it/pagina.php`  
> Il browser invia una richiesta GET per `pagina.php`  
> Il server esegue il file PHP  
> Il server risponde con HTML  
> Il browser mostra la pagina all’utente

---

## 3. PHP ed `echo`

### Cos’è `echo`?

`echo` è un comando di PHP che serve a **stampare dati in output** verso il browser (client).

### Tipi di stringa:
- **Statica**: testo fisso → `echo "Benvenuto!";`
- **Dinamica**: testo + variabili → `echo "Ciao " . $nome . "!";`

### Esempio:

```php
<?php
echo "<h2>Benvenuto!</h2>"; // stringa statica
$ora = date("H:i");
echo "<p>Sono le " . $ora . "</p>"; // stringa dinamica
?>
```

**Output (es. alle 14:30):**

```html
<h2>Benvenuto!</h2>
<p>Sono le 14:30</p>
```

### Sintassi:
- Le **virgolette doppie** permettono di interpretare variabili: `"Ciao $nome"`
- Le **virgolette singole** no: `'Ciao $nome'` stampa letteralmente `$nome`
- Le parti di stringa si **concatenano con il punto** `.`
