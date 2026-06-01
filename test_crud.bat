@echo off
:: C:\xampp\htdocs\gestion-evenements\test_crud.bat
echo =======================================================
echo          TEST AUTOMATIQUE DU CRUD VIA cURL
echo =======================================================
echo.

:: 1. Authentification en tant qu'admin
echo [1/5] Authentification de l'administrateur...
curl -s -c cookie.txt -d "email=admin@events.ga&mot_de_passe=admin123" "http://localhost/gestion-evenements/index.php?page=auth&action=login" > nul
if exist cookie.txt (
    echo    =^> Succes : Cookie de session enregistre dans cookie.txt
) else (
    echo    =^> Erreur : Impossible de s'authentifier. Verifiez que Apache et MySQL sont lances.
    exit /b
)
echo.

:: 2. Creation d'un evenement
echo [2/5] Creation d'un nouvel evenement...
curl -s -b cookie.txt -c cookie.txt -d "titre=Seminaire Tech Libreville&description=Un seminaire professionnel cree par requete cURL.&date=2026-10-15T09:00&lieu=Hotel Nomade, Libreville&capacite=150&prix_billet=35000&image_url=&organisateur_id=2" "http://localhost/gestion-evenements/index.php?page=evenements&action=creer" > nul

:: Verifier dans la BDD
C:\xampp\php\php.exe -r "require 'config/database.php'; $stmt = $pdo->query('SELECT id, titre, prix_billet FROM evenement WHERE titre=\'Seminaire Tech Libreville\''); $event = $stmt->fetch(); if ($event) { echo '   => Succes : Evenement cree avec l\'ID #' . $event->id . ' (Prix : ' . $event->prix_billet . ' FCFA)' . PHP_EOL; } else { echo '   => Erreur : L\'evenement n\'a pas ete trouve dans la base.' . PHP_EOL; exit; }"
echo.

:: Recuperer l'ID pour la suite
for /f "tokens=*" %%i in ('C:\xampp\php\php.exe -r "require 'config/database.php'; $stmt = $pdo->query('SELECT id FROM evenement WHERE titre=\'Seminaire Tech Libreville\''); $event = $stmt->fetch(); echo $event ? $event->id : '';"') do set EVENT_ID=%%i

if "%EVENT_ID%"=="" (
    echo Erreur lors de la recuperation de l'ID.
    del cookie.txt
    exit /b
)

:: 3. Modification de l'evenement cree
echo [3/5] Modification de l'evenement #%EVENT_ID%...
curl -s -b cookie.txt -c cookie.txt -d "titre=Seminaire Tech Libreville (Modifie)&description=Description mise a jour par cURL.&date=2026-10-15T09:00&lieu=Hotel Nomade, Libreville&capacite=200&prix_billet=40000&image_url=&organisateur_id=2" "http://localhost/gestion-evenements/index.php?page=evenements&action=modifier&id=%EVENT_ID%" > nul

:: Verifier dans la BDD
C:\xampp\php\php.exe -r "require 'config/database.php'; $stmt = $pdo->prepare('SELECT titre, capacite, prix_billet FROM evenement WHERE id=?'); $stmt->execute([$argv[1]]); $event = $stmt->fetch(); if ($event && $event->titre === 'Seminaire Tech Libreville (Modifie)') { echo '   => Succes : Donnes modifiees (Nouveau titre : ' . $event->titre . ', Capacite : ' . $event->capacite . ', Prix : ' . $event->prix_billet . ' FCFA)' . PHP_EOL; } else { echo '   => Erreur lors de la modification de l\'evenement.' . PHP_EOL; }" %EVENT_ID%
echo.

:: 4. Suppression de l'evenement
echo [4/5] Suppression de l'evenement #%EVENT_ID%...
curl -s -b cookie.txt -c cookie.txt "http://localhost/gestion-evenements/index.php?page=evenements&action=supprimer&id=%EVENT_ID%" > nul

:: Verifier dans la BDD
C:\xampp\php\php.exe -r "require 'config/database.php'; $stmt = $pdo->prepare('SELECT COUNT(*) FROM evenement WHERE id=?'); $stmt->execute([$argv[1]]); $count = $stmt->fetchColumn(); if ($count == 0) { echo '   => Succes : L\'evenement #' . $argv[1] . ' a bien ete supprime de la base de donnees.' . PHP_EOL; } else { echo '   => Erreur : L\'evenement est toujours present.' . PHP_EOL; }" %EVENT_ID%
echo.

:: 5. Nettoyage
echo [5/5] Nettoyage des fichiers temporaires...
if exist cookie.txt del cookie.txt
echo    =^> Succes : Fichier cookie.txt supprime.
echo.
echo =======================================================
echo          FIN DU TEST : TOUT FONCTIONNE !
echo =======================================================
