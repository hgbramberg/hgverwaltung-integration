# hgverwaltung-integration
Files to integrate hgvwerwaltung.ch on a Wordpress Site

Quelle der Original-Dateien: [https://www.hornusserverwaltung.ch/durchschnitt-auf-homepage/] (Merci viu mau!)

Diese Dateien werden in unserer Wordpress installation in die jeweilige Seite durch ein Plugin integriert.
So ist die Schnittstelle zur hgverwaltung relativ flexibel losgelöst von der Wordpress installation.
Und kann laufend optimiert werden ohne gross an WP etwas einzustellen/verändern.
Auch die history ist via GIT Repo ideal gelöst.

## Integration in Wordpress:
1. Plugin installieren
  Das Plugin "Include Me" installieren
  https://www.satollo.net/plugins/include-me

2. Dateien zu Wordpress hochladen
  Ich habe dazu den Ordner wordpress/wp-content/hgverwaltung gewählt

3. Seiten mit den includes anlegen

  * Resultate – Durchschnitt
  '[includeme file="wp-content/hgverwaltung/durchschnitt.php"]'

  * Resultate – Spielplan
  '[includeme file="wp-content/hgverwaltung/spiele.php"]'

  * Resultate – SpielplanDetail
  '[includeme file="wp-content/hgverwaltung/spieldetail.php"]'

  * Resultate - Streiche
  '[includeme file="wp-content/hgverwaltung/streiche.php"]'

  * Resultate - Rangpunkte
  '[includeme file="wp-content/hgverwaltung/rangpunkte.php"]'

tbc.
