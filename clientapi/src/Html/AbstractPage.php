<?php
 
namespace App\Html;
 
use App\Interfaces\PageInterface;
 
abstract class AbstractPage implements PageInterface
{
 
    static function head(){
        echo '
        <!doctype html>
        <html lang="hu-hu">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Megyék</title>
            <link rel="stylesheet" href="style.css">
            <script src="script.js"></script>
        </head>
        ';
    }
    
 
    static function nav()
    {
        echo '
        <nav>
            <form name="nav" method="POST" action="index.php">
                <button type="submit" name="btn-home">Kezdőlap</button>
                <button type="submit" name="btn-counties">Megyék</button>
                <button type="submit" name="btn-cities">Városok</button>
            </form>
        </nav>';
    }
 
    static function footer()
    {
        echo '
        <footer>
            ---
        </footer>
        </html>';
    }
 
    abstract static function tableHead();
 
    abstract static function tableBody(array $entities);
 
    abstract static function table(array $entities);
 
    abstract static function editor();
 
    static function searchBar()
    {
        echo '
        <form method="POST" action="">
            <input
                type="search"
                name="needle"
                placeholder="Keres"
            >
            <button
                type="submit"
                id="btn-search"
                name="btn-search"
                title="Keres"
                >Keresés
            </button>
            </form>';
    }
}
