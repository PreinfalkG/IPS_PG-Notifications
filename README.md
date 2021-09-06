
**FORK** form https://github.com/IQLiving/IQLProwlNofitication
https://www.prowlapp.com/

# IQLProwlNotification

Ein Modul zum Versand von Push Nachrichten über den Dienst Prowl.

**Konfigurationsformular:**

* **Applicationsname** steht in der Push Notification über der Betreffzeile, dieser kann individuell angepasst werden.
* **Benutzername** dieser dient nur als Alias zum Versand von Push Notifications über die Funktion IQLPN_SendEx. Der Benutzername darf nicht doppelt vergeben werden und darf kein Komma enthalten!
* **API-Key** dies ist der von Prowl zur Verfügung gestellte API-Key. Der API-Key darf nicht doppelt vergeben werden!


**Befehlsreferenz:**

**IQLPN_Send** dient zum Versand von Push Notifications
```php
<?php
IQLPN_Send( interger $InstanceID, string $subject, string $message, integer $priority );
?>
```
* **InstanceID** die ID der Instanz
* **subject** die Betreffzeile der Push Notification (darf maximal **1024** Zeichen enthalten)
* **message** Inhalt der Push Notification (darf maximal **10000** Zeichen enthalten)
* **priority** Wert von -2 bis 2
    * -2 = sehr niedrig
    * -1 = mittelmäßig
    * 0 = normal
    * 1 = hoch
    * 2 = Notfall


**IQLPN_SendEx** dient zum Versand von Push Notifications an mehrere Benutzer
```php
<?php
IQLPN_SendEx( interger $InstanceID, string $username, string $subject, string $message, integer $priority );
?>
```
* **InstanceID** die ID der Instanz
* **username** der Benutzername, für den Versand an mehrere Benutzer werden diese einfach durch Komma separiert angegeben ("user1,user2,user3")
* **subject** die Betreffzeile der Push Notification (darf maximal **1024** Zeichen enthalten)
* **message** Inhalt der Push Notification (darf maximal **10000** Zeichen enthalten)
* **priority** Wert von -2 bis 2
    * -2 = sehr niedrig
    * -1 = mittelmäßig
    * 0 = normal
    * 1 = hoch
    * 2 = Notfall