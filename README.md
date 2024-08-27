# SKRIME Plesk Lizenz Modul für WHMCS

## Installationsanleitung

1. **Modul hochladen:**

   Entpacke die ZIP-Datei und lade den Inhalt in das Hauptverzeichnis deiner WHMCS-Installation hoch.


2. **Server hinzufügen:**

    1. Gehe zu deinem WHMCS-Adminbereich: `deinedomain.de/admin/configservers.php`
    2. Klicke auf „Server hinzufügen“.
    3. Wähle unter „Modul“ das Modul **SKRIME Plesk License** aus.
    4. Gib als Hostname `skrime.eu` ein.
    5. Trage deinen API-Token in die Felder „Benutzername“, „Passwort“ und „Access Hash“ ein.


3. **Verbindung testen:**

    - Klicke auf „Test Connection“ und warte auf die Bestätigung, dass die Verbindung erfolgreich hergestellt wurde.
    - Verleihe dem Server einen eigenen Namen zur besseren Zuordnung (optional).


4. **Maximale Accounts einstellen (optional):**

    - Hier kannst du die maximale Anzahl der über Skrime beziehbaren Lizenzen festlegen.


5. **Einstellungen speichern:**

    - Klicke auf „Save Changes“, um die Einstellungen zu speichern.


6. **Produktgruppe und Produkt erstellen:**

    1. Navigiere zu `deinedomain.de/admin/configproducts.php`.
    2. Erstelle eine neue Produktgruppe und füge ein neues Produkt hinzu.
    3. Wähle beim Erstellen des Produkts „Shared Hosting“ als Produktart und als Modul **Skrime Plesk Lizenzen** aus.
    4. Entferne den Haken bei „Create as Hidden“, damit das Produkt sichtbar ist.
