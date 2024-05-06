# Bakalářský projekt pro zadavatele Do psí misky s.r.o.

Webová aplikace umožňující nákup pomocí telefonu a skenování kódu v bezkontaktních samoobslužných prodejnách pro zadavatele Do psí misky, vytvořená v PHP s frameworkem Laravel a doplňky v JavaScriptu.
Integruje jednoduchý proces pro uživatele a administraci, jak pro správu, tak pro samotný nákup.
Informace o cílech projektu jsou blíže popsané v bakalářské práci (odkaz bude doplněn).

## Instalace

Pro instalaci potřebných závislostí se ujistěte, že máte nainstalované apache2, PHP, Composer, MariaDB, imagick, a pro uživatelský komfort i phpMyAdmin. Poté spusťte v složce, kde je stažený git projekt:
```
composer install
```
Nastavte práva aplikace
```
sudo chown -R www-data:www-data /cesta/k/aplikaci/storage /cesta/k/aplikaci/bootstrap/cache
sudo chmod -R 775 /cesta/k/aplikaci/storage /cesta/k/aplikaci/bootstrap/cache
```
Webový server by měl odkazovat do 
```
/cesta/k/aplikaci/public
```
Zkopírujte .env.example do .env a nastavte potřebné údaje
```
cp .env.example .env
```
Propojení storage probíhá pomocí 
```
php artisan storage:link
```

Následně je potřeba vyplnit databázi, je potřeba využít export ze souboru STRUCTURE_REQUIRED.SQL, přičemž název databáze je laravel.
Pro spuštění scheduleru použijte příkaz
```
* * * * * cd /cesta/k/aplikaci && php artisan schedule:run >> /dev/null 2>&1
```








## Použité knihovny

Kromě samotného Laravelu aplikace využívá následující knihovny, jejichž kódy jsou dostupné na GitHubu:

- **[barryvdh/laravel-dompdf](https://github.com/barryvdh/laravel-dompdf)**: Laravel balíček pro generování PDF dokumentů.
- **[gopay/payments-sdk-php](https://github.com/gopaycommunity/payments-sdk-php)**: Integrace GoPay Plateb pro PHP.
- **[laravel/sanctum](https://github.com/laravel/sanctum)**: Laravel Sanctum poskytuje jednoduché a lehké autentizační řešení pro SPAs, mobilní aplikace a jednoduchá API.
- **[laravel/socialite](https://github.com/laravel/socialite)**: Laravel služba poskytující OAuth autentizaci.
- **[laravel/tinker](https://github.com/laravel/tinker)**: Výkonný REPL pro framework Laravel.
- **[laravel/ui](https://github.com/laravel/ui)**: Laravel UI nástroje a presety.
- **[simplesoftwareio/simple-qrcode](https://github.com/SimpleSoftwareIO/simple-qrcode)**: Jednoduchý generátor QR kódů.
- **[html5-qrcode](https://github.com/mebjas/html5-qrcode)**: Knihovna JavaScriptu pro práci s QR kódy.
- **[quaggajs](https://github.com/serratus/quaggaJS)**: JavaScriptová knihovna pro čtení čárových kódů.

## Licence

Tato aplikace je licencována pod [MIT licencí](https://opensource.org/licenses/MIT).
