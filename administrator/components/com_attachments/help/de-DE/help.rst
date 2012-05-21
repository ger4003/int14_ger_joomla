.. header:: 

    .. raw:: html

	<h1 class="title">Attachments Dokumentation</h1>

.. class:: version

**Version 2.2-RC1 - December  3, 2010**

.. contents::
    :depth: 1


Einf�hrung
===============

Die 'Attachments' Erweiterung f�r Joomla! erlaubt es, Dateien hochzuladen und an Artikel oder andere Inhalte anzuh�ngen.
'Attachments' beinhaltet ein Plugin zum Anzeigen der Anh�nge und eine Komponente f�r den Upload und das Datei-Management.
Optionen sind enthalten, die Berechtigungen der Sichtbarkeit und des Uploads betreffen, neben weiteren, die die Flexibilit�t und das Nutzungsverhalten optimieren.
Hinweis: Alle Einstellungsoptionen findet man in der Komponente. 

.. warning:: **Diese Erweiterung l�uft nur mit Joomla! 1.5. Sie wurde nicht mit Version 1.6 getestet.**

'Attachments' wurde in viele verschiedene Sprachen �bersetzt. In der Liste `�bersetzungen`_ section for the list of available translations.  finden Sie alle verf�gbaren Sprachen. Die meisten der Sprachen sind im Prozess des Updates f�r die Version 'Attachments' 2.2.

Sie k�nnen sich für Ank�ndigungen in folgende Mailing-Liste eintragen:

* `Attachments E-Mail Liste ( http://jmcameron.net/attachments/email-list.html )
  <http://jmcameron.net/attachments/email-list.html>`_


New features in Version 2.0
===========================

* Es k�nnen nun auch URLs genauso wie Dateien angeh�ngt werden.
* Anh�nge k�nnen �berall im Artikel (oder anderem Inhalt) angezeigt werden.
* Neu sind auch optionale Felder, die zum Beispiel den Namen des Uploaders anzeigen.
* Das Multi-Installer-Paket beinhaltet alle Plugins, die automatisch ver�ffentlicht werden, und die Erweiterung selbst. Installieren Sie nur einmal und 'Attachments' kann sofort genutzt werden. Der Multi-Installer macht das Installieren des Paketes und seiner sieben Plugins also sehr einfach. 

  .. hint:: Hinweis: Die Joomla!-Update Methode ist nun auch Standard f�r 'Attachments'. Das bedeutet, dass Sie nichts deinstallieren m�ssen bei Installation einer neuen Version der Komponente.

* Anhangsdateien werde in Pfade wie diesen konvertiert::

        attachments/article/23/file.txt

  Diese Funktion eliminiert alle Dateipr�fixe vergangener Versionen. Das bedeutet, dass jeder einzelne Inhalt ohne doppelte Dateinamen versehen wird. In der Praxis ist damit eine Limitierung entfallen.
* Im Code realisiert wurde das Zuf�gen von Dateien durch die "Attachments plugins".  Diese Plugins h�ngen Dateien an jeglichen Inhalt an, die das ``onPrepareContent`` Plugin aufrufen. Zum Beispiel kann man also Anh�nge auch an Sektionen oder Kategoriebeschreibungen anheften. Mit etwas Aufwand kann ein Entwickler zudem problemlos neue 'Attachments' Plugins zur Unterst�tzung anderer Komponenten erstellen. Beispiele k�nnten Community Builder Profil-Informationen sein oder auch Anh�nge f�r VirtueMart-Beschreibungen. Weitere Details finden Sie in der Sektion `Wo k�nnen Dateien angeh�ngt werden?`_.
* Im administrativen Backend:
     - Anh�ngen w�hrend des Erstellens von Artikeln 
     - Unterdr�ckung von Listenanh�ngen die zu unver�ffentlichten oder im Papierkorb liegenden Artikeln zugeordnet sind (ebenfalls nat�rlich eine Option, diese Dateien einfach zu sehen)
     - Filterung zur Liste der Artikel zugef�gt
     - Tab-Listen zum Sortieren nach verschiedenen Kriterien im Backend �berarbeitet
     - Verschiedene neue administrative Kommandos zugef�gt
* Verbesserte Unicode Handhabung der Dateinamen
* Mehr Flexibiblit�t der Optionen "Wer darf sehen" und "Wer darf aktualisieren".
* Code optimiert hinsichtlich css-Problemen w�hrend der Installation, wenn der Joomla!-Cache eingeschalten ist. 

Upload Begrenzungen
===================

Nicht alle Dateitypen k�nnen hoch geladen werden, sondern nur solche, die im Joomla!-Medienmanager genehmigt sind.
Um diese Einstellungen zu sehen oder zu �ndern, gehen Sie auf die **Globale Konfiguration**  in den **System** Reiter.  In den *Medien Einstellungen* finden Sie Optionen zur Kontrolle der erlaubten Dateeindungen und Medientypen f�r Uploads. 
'Attachments' respektiert diese Limitierung.  Dennoch werden die dortigen Einstellungen der Dateitypen f�r Bilder ignoriert.

Attachments Einstellungen
=========================

Alle Einstellungen f�r 'Attachments' werden in der Komponente gesteuert. Im Backend begeben Sie sich zum Men�-Punkt "Attachments", den Sie unter dem "Komponenten"-Reiter finden.
In den Parametern finden Sie eine Reihe an Einstellungen der Erweiterung. Darin sind enthalten:

* **Wer kann Anh�nge betrachten:** Hier stellen Sie ein, wer die Links zu den Anh�ngen sehen darf. Drei M�glichkeiten gibt es:

  1.  '*Niemand*' - Ist diese Option gew�hlt, werden die Liste selbst und die Dateilinks nicht f�r normale Nutzer der Webseite (im Frontend) angezeigt. Im sicheren Modus gew�hrleistet dies, dass Anh�nge nicht im Frontend herunter geladen werden k�nnen. Administratoren haben immer alle Rechte.
  2.  '*Alle eingeloggten Nutzer*'. - Ist diese Option gew�hlt, werden die Listen und Links nur eingeloggten Nutzern angezeigt.
  3.  '*Jeder*' - Ist diese Option gew�hlt, werden normalen und eingeloggten Besuchern der Seite alle Listen und Links angezeigt.

* **Wer kann Anh�nge zuf�gen:** Diese Einstellung kontrolliert die Zuf�geberechtigungen f�r Artikel oder andere Inhaltselemente. Vier Optionen gibt es: 

  1.  '*Niemand*' - Wenn diese Option gew�hlt wird, erscheint der "Add Attachments" Link zum Uploaden NICHT f�r normale Nutzer der Seite (im Frontend), auch wenn diese eingeloggt sind. Im sicheren Modus sorgt diese Option f�r den Schutz vor Uploads. Administratoren sehen den Link dennoch und haben alle Rechte.
  2.  '*Nur der Artikel-Autor*' - Die Links zum Uploaden und Editieren sieht nur der Autor eines Eltern-Artikels oder einer Kategorie etc. Wie immer in Joomla! haben aber auch h�here Nutzer (Editoren, Publisher etc.) diese Option.
  3.  '*Jeder eingeloggte Nutzer*' - Die Links zum Upload sind f�r jeden eingeloggten Nutzer ersichtlich.
  4.  '*Editoren und dar�ber*' - Die Links zum Upload zu Artikeln werden nur von Nutzern mit dem Editor-Status oder dar�ber hinaus gesehen.

* **Anh�nge standardm��ig ver�ffentlichen:** Diese 'Auto-Publikation' kann Anh�nge automatisch ver�ffentlichen, wenn sie hinzugef�gt werden. Wenn aktiviert, werden sie umgehend im Artikel oder anderen Inhalt angezeigt! Ist sie deaktiviert, muss sie ein Administrator zuvor im Backend freischalten.
* **Auto-Publikations-Warnung:** Wenn Auto-Publikation angeschalten ist, m�chten Sie Ihre Nutzer eventuelle dar�ber informieren - dazu dient diese Funktion. Tragen Sie einen Text ein, der immer beim Upload eines Anhangs im Frontend angezeigt wird. Bleibt das Feld leer, wird stattdessen eine Standard-System-Nachricht zum selben Zweck angezeigt und mit dem Hinweis, dass man den Administrator kontaktieren m�ge nach dem Upload.
* **Titelanzeige:** Wenn auf 'Ja' gesetzt, zeigt eine separate Reihe den Titel der entsprechenden Spalte an.
* **Anhangsbeschreibung anzeigen:** Diese Einstellung zeigt bei aktivem Status die Beschreibung der Datei im Frontend an.
* **Anhangs-Uploader anzeigen:** Zeigt den Nutzernamen an, von dem die Datei hinauf geladen wurde.
* **Dateigr��e anzeigen:** Mit dieser Option k�nnen Sie die Dateigr��e im Frontend anzeigen lassen.
* **Downloadcounter anzeigen:** Wenn aktiviert, zeigt diese Spalte die Anzahl der erfolgten Downloads im Frontend an.

  .. warning:: Diese Option arbeitet nur im Sicherheitsmodus ('secure mode'). Ist dieser deaktiviert, werden Dateien als statische Dateien behandelt und direkt zug�nglich, ohne dass sie von Joomla!-Code beachtet werden. Dabei ist es nicht m�glich, die Anzahl der Downloads zu aktivieren. Die Anzeige funktioniert nur im Sicherheitsmodus 'Ja'.
* **Dateimodifizierungsdatum anzeigen:** Mit dieser Einstellung k�nnen Sie das Datum der letzten Modifizierung im Frontend anzeigen lassen. Bei 'Nein' wird nichts angezeigt.
* **Datumsformat des Modifizierungsdatums:** Sie wollen evt. das Datum anpassen in seinem Format - daf�r nutzen Sie hier die PHP strftime() Funktion. Durchsuchen Sie das Web f�r
  'PHP strftime' Beispiele.  Das Standard-Format (%x %H:%M) zeigt Daten im 24-Stunden-Rhythmus an, z.B. 4/28/2008 14:21.  Um die Tagesdetails zu entfernen und nur das Datum anzuzeigen, entnehmen Sie  "%H:%M".  Beachten Sie auch, dass MS Windows und Linux PHP-Implementierungen dieser Form Unterschiede aufweisen k�nnen - das h�ngt vom unterst�tzten Code ab.

* **Anhangs-Listen Reihenfolge:** Diese Option erlaubt es, die Reihenfolge nach Kriterien zu sortieren. Die meisten sind selbsterkl�rend: 

  1.  '*Dateiname*' - Mit dieser Funktion ordnen Sie die Ansicht alfabetisch.
  2.  '*Dateigr��e (kleinste zuerst)*' 
  3.  '*Dateigr��e (gr��te zuerst)*' 
  4.  '*Beschreibung*' 
  5.  '*Anzeige des Dateinamens oder der URL*' - Alle Anh�nge ohne konkreten Titel werden entweder mit ihrem Dateinamen im Titelfeld im Frontend angezeigt oder als URL und danach sortiert.
  6.  '*Uploader*' - Ordnet die Liste nach dem Namen des Uploaders alfabetisch.
  7.  '*Erstellungsdatum (�teste zuerst)*' 
  8.  '*Erstellungsdatum (neueste zuerst)*' 
  9.  '*Modifizierungsdatum (�testes zuerst)*' 
  10. '*Modifizierungsdatum (neueste zuerst)*' 
  11. '*Anhangs-ID*' - Die Aktivierung dieser Option ordnet die Anh�nge gem�� ihrer ID, also dem Verlauf des Uploads. 
  12. '*Nutzerdefiniertes Feld 1*' 
  13. '*Nutzerdefiniertes Feld 2*' 
  14. '*Nutzerdefiniertes Feld 3*' 

* **Titel des nutzerdefinierten Feldes 1-3:** Sollten Sie zus�tzliche Anzeigefelder generieren wollen, haben Sie an dieser Stelle die M�glichkeit. Bis zu drei Felder k�nnen hier generiert werden, die Ihren Listen zugef�gt werden. Vergeben Sie einen Titel und es wird in den Uploadformularen angezeigt - lassen Sie das Feld leer und es wird nicht erscheinen. Die Reihenfolge wird im Frontend gewahrt. Das Maximum der nutzerdefinierten Titel liegt bei 40 Zeichen. Die Datenformularfelder fassen 100 Zeichen.

  .. hint:: Wenn Sie ein Sternchen ans Ende des Feldnamens setzen, wird das Feld im frontend nicht angezeigt. Es ist nur beim Upload und im Backend sichtbar und kann als verstecktes Feld die Liste ordnen. 

* **Maximale Dateinamen L�nge:**
  Das Maximum wird hier bestimmt und alles Dar�berliegende abgeschnitten und in den Anzeigenamen umgewandelt (f�r Anzeige wird der Dateiname nicht ge�ndert). Inkludieren Sie die Zahl 0 und das Limit ist nach oben offen (das Dateinamenfeld der Attachments-Datenbanktabelle ist auf 80 Zeichen beschr�nkt). Hinweis: Wenn Dateinamen von dieser Option abgeschnitten werden, wird der abgeschnittene Dateiname auch im "Anzeige des Dateinamens"-Feld gek�rzt. Diese Option greift nur bei neuen Dateien, nicht bei bereits hoch geladenen.
* **Wo sollen Anh�nge platziert werden?** Diese Option kontrolliert den Ort der Anzeige Ihrer Anh�nge. Alle Arten von Listen sind von der hier eingestellten Option betroffen:

     - '*Am Anfang*'
     - '*Am Ende*'
     - '*Manueller Ort*' -  Hiermit k�nnen Sie die Anh�nge Ihrer Artikel oder Inhaltsanzeigen manuell an einen bestimmten Platz �bergeben. Nutzen Sie den Spezial-Tag {attachments} um die Liste zu platzieren.    

       .. warning::  Wird der Tag nicht genutzt, setzt sich die Liste automatisch ans Ende des Inhaltes.   

       In diesem Modus erscheint beim Editieren eines Artikels, einer Kategorie oder Sektion ein Button [Insert {attachments} token].
       Platzieren Sie den Cursor, wo der Anhang erscheinen soll und bet�tigen Sie den Button. Der Button f�gt HTML Code hinzu, der den Token verbirgt, wenn nichts angezeigt werden soll (z.B. wenn der Anhang nicht �ffentlich lesbar ist). in HTML sieht das dann so aus::

         <span class="hide">{attachments}</span>

       Im Editor sieht man den {attachments} Tag aber nicht den 'span' Code, es sei denn Sie wechseln zur HTML Version. Im Frontend sieht man den {attachments} Tag, es sei denn das insert_attachments_tag
       Plugin ist deakiviert.  Wenn Sie den {attachments} Token entfernen wollen, kontrollieren Sie auch die "HTML" Modus, um sicherzugehen dass der "span"-Tag ebenfalls entfernt wurde.
     - '*Deaktiviert (Filter)*' - Diese Option deaktiviert die Anzeige und unterdr�ckt den {attachments} Tag in jedem Inhalt.
     - '*Deaktiviert (Ohne Filter)*' - Normale Listen werden deaktiviert, der {attachments} Tag-Inhalt wird jedoch angezeigt.
* **CSS Stile f�r  Attachments Tabellen:** Um eigene CSS-Stile zu nutzen, �berschreiben Sie hier den Standard 'attachmentsList'.  Weitere Informationen zur Anpassung der CSS finden Sie im Kapitel `CSS Anpassungen der Anhangs-Listen`_.
* **URL zur Registrierung:** Wenn eine spezielle URL vonn�ten ist, tragen Sie diese hier ein, z.B. wenn Sie eine Komponente nutzen oder eine eigene Loginseite erstellt haben.
* **Dateilink Verweis:**
  Diese Option definiert das �ffnen von Dateien. 'Im selben Fenster' �ffnet im gleichen Browsertab /-fenster. 'Neues Fenster' �ffnet in neuem Fenster oder Tab, je nach Voreinstellung des Nutzerbrowsers.
* **Unterkategorie f�r Uploads:** Der 'Attachments' Code wird Dateien in diese Kategorie in den Joomla!-Root legen. Standard ist 'attachments'. Beachten Sie, dass eine �nderung der Kategorie alle neuen Dateien betrifft, bereits bestehende werden nicht automatisch �bernommen. Diese k�nnen dort verbleiben, die Eintr�ge der Datenbank verweisen weiterhin korrekt. Wollen Sie die Dateien ebenfalls in den neuen Ordner laden, aktualisieren Sie hernach auch die Eintr�ge der Dateien in der Komponente manuell f�r eine korrekte Darstellung.
* **Nutzertitel f�r Anhangs-Listen:** Der Standardtitel lautet "Anh�nge:" und erscheint �ber der Liste in den Inhaltsanzeigen (Artikel etc.), wenn Dateien vorhanden sind. M�chten Sie dies anpassen, geschieht dies auf individueller Basis, z.B.: Wollen Sie Artikel 211 mit dem Anhangs-Titel "Downloads:" anzeigen lassen, tragen Sie '211 Downloads' (ohne Anf�hrungszeichen) ein. Andere Arten von Inhalt nutzen diese Form: 'category:23 Titel der Kategorie 23' - 'category' kann mit dem Namen ersetzt werden. Das obige Beispiel kann also auch mit 'article:211 Downloads' gel�st werden.  Achten Sie darauf, dass Eintr�ge ohne ID am Anfang alle Inhaltslistentitel betrifft. Es ist also praktischer, zun�chst einen generell passenden Standard zu definieren und hernach einzelne Titel manuell anzupassen.
   
  Hinweis: Wenn Sie den Titel global anpassen wollen, �ndern Sie am besten den Eintrag 'ATTACHMENTS TITLE' Ihrer Sprachdatei::

      administrator/language/qq-QQ/qq-QQ.plg_frontend_attachments.ini

  wobei qq-QQ Ihre Sprachk�rzel ist, z.B. de-DE f�r deutsch. 
  (Wenn Sie das noch nicht gemacht haben: Finden Sie den Eintrag 'ATTACHMENTS TITLE' auf der linken Seite des Gleichzeichens und bestimmen Sie die Anzeige rechterhand. �ndern Sie linkerhand nichts!)
* **Anh�nge verbergen f�r:**
  Kommaseparierte Liste von Schl�sselworten oder Sektionen / Kategorien in denen Anh�nge nicht angezeigt werden sollen. 5 Keywords k�nnen Sie nutzen: 'frontpage' um Anh�nge auf der Startseite zu verbergen, 'blog' f�r das Verbergen in Blogseiten, 'all_but_article_views' um nur in Artikelansichten Anh�nge anzuzeigen, 'always_show_section_attachments' um die Anzeige in Sektionsansichten zu gestatten wenn 'all_but_article_views' ebenfalls vergeben ist oder 'always_show_category_attachments' um Anh�nge in Kategorien anzuzeigen, wenn 'all_but_article_views' ebenfalls aktiviert ist. (Lassen Sie die Anf�hrungszeichen beim Kopieren weg.) 
  
  **Die 'frontpage' Option verhindert alle anderen Ansichten, aber Inhaltstypen, die nicht Artikel, Kategorien oder Sektionen sind, k�nnten Inhalte dennoch anzeigen - auch wenn 'all_but_article_views' oder eine der anderen Optionen eingestellt sind.** Article
  Artikel, Sektions- oder Kategorie-IDs sollten numerisch eingetragen und Kategorien mit einem Slash (/) getrennt werden: Section#/CategoryNum,SectionNum/CategoryNum. Nutzen Sie 'SectionNum' um alle Kategorien aus der Sektion auszuw�hlen. Beispiel: 23/10, 23/11, 24
* **Timeout f�r das Pr�fen von Links:**
  Timeout in Sekunden.  Wann immer ein Link zugef�gt wird als Anhang, wird er direkt gepr�ft. Diese Zeit definiert, bis wann er erreichbar gewesen sein muss (man kann dies manuell im Formular deaktivieren). Wird der Link vor dem Timeout erreicht, werden Dateiinformationen eingeholt, andernfalls wird die generische Information genutzt. Um diese Funktion zu deaktivieren, tragen Sie 0 ein.
* **�berlagern von URL Link Icons:**
  �berlagern Sie Ihre Linkicons am Anhang, die sie als Links erkennbar machen, mit einem speziellen Icon. Valide (funktionierende) Links werden mit einem Pfeil, invalide mit einem roten Strich von links unten nach rechts oben durch den Dateityp gekennzeichnet.
* **Unterdr�cken von abgelaufenen Dateien (im Backend):**
  Setzen Sie hier einen Standard f�r  <em>abgelaufene</em> Dateien f�r das administrative Backend. Abgelaufene Dateien sind jene, die zu unver�ffentlichten oder in den Papierkorb verschobenen Artikel etc. geh�ren. Sie k�nnen den Standard aber direkt in der Dateiliste �berschreiben, indem Sie sie rechts oben in der Men�leiste (neben dem Filter) �ber das dortige Dropdown-Feld manuell aktivieren. Nutzen Sie diese Funktion, bleibt sie bestehen bis zu Ihrem n�chsten Login.
* **Sichere Dateidownloads:** Standardm��ig werden Anh�nge der 'Attachments' Komponente in einem Ordner Ihrer Domaininstallation Joomlas gespeichert. Auch wenn die Links im Frontend keine direkten Links sind, bleiben es �ffentliche Ordner, deren Inhalte theoretisch gefunden werden k�nnen. Die Links im Frontend garantieren, dass nur bestimmte Nutzergruppen Zugriff erhalten. Ist die Option der *Sicheren Dateidownloads* deaktiviert, werden die Links also wie in den obigen Optionen eingestellt, dargestellt - ist der beherbergende Ordner jedoch mit entsprechenden Datei-Rechten ausgestattet, kann, kennt man die richtige URL, trotzdem Dateizugriff erlangen - solange der Ordner �ffentlich ist. Die *Sichere Downloads* Option verhindert dies, auch wenn die URL zum Server-Unterordner, in dem die 'Attachments'-Dateien liegen, bekannt sein sollte.
* **Auflisten der Anh�nge im Sicheren Modus:**
  Listet Anh�nge im Sicheren Modus auf, auch wenn Nutzer nicht eingeloggt sind. Es sei denn, Sie haben 'Niemand' eingestellt bei 'Wer darf die Dateien sehen', dies kontrolliert dann also auch noch, ob Dateien herunter geladen k�nnen. Nur im Sicheren Modus greift diese Listen-Funktion, andernfalls bleibt sie unbeachtet.
* **Download Modus f�r sichere Downloads:**
  Hier k�nnen Sie einstellen, ob Dateien im Browser (wenn m�glich) angezeigt werden - oder aber direkt herunter geladen werden sollen. Zwei Optionen sind verf�gbar:

     - *'Inline'* - In diesem Modus werden Dateien im Browser angezeigt, wenn Sie unterst�tzt werden (wie Textdateien oder Bilder).
     - *'Anhang'* -Im 'Anhang' Modus werden Dateien immer direkt als separate Dateien herunter geladen.

	Erkennt der Browser eine Datei nicht oder kann sie nicht anzeigen, werden diese stets direkt herunter geladen.

Anzeige des Dateinamens oder der URL
====================================

Normalerweise werden hochgeladene oder verlinkte Dateien mit ihrem vollen Namen in der Anhangs-Liste angezeigt. Machmal ist der Name jedoch zu lang, um angezeigt zu werden (bei manchen URLs z.B.). Im Upload-Feld steht eine Option zur Verf�gung, die "Anzeige des Dateinamens oder der URL" hei�t. In dieses kann ein alternativer Name eingetragen werden, der dann stattdessen angezeigt wird. In den Optionen im Backend finden Sie zudem eine Option der "Beschr�nkung der maximalen Zeichenzahl", die Namen ggf. automatisch abschneidet. In diesem Fall w�rde dann der gek�rzte Name angezeigt werden.

URLs anh�ngen
==================

Dies ist ein neues Feature von 'Attachments" Version 2.0. Sie k�nnen neben Dateien auch URLs anh�ngen. Ein Button zum Wechseln zu dieser Funktion findet sich im Backend als auch im Frontend im Upload-Formular. Klicken Sie darauf und erhalten Sie diese zwei Optionen:

* **URL Existenz verifizieren?** - Um einen g�ltige Datei zu best�tigen (und ein Icon und die Gr��e anzuzeigen), pr�ft der Code Basisinformationen �ber die Datei auf dem fremden Server ab. Manchmal reagiert ein Server jedoch nicht, auch wenn die Datei existiert. Als Standard akzeptiert 'Attachments' solche Dateien jedoch nicht. Deaktivieren Sie die Option der Verifizierung, wird diese Datei dennoch angezeigt - Sie sollten sie dann manuell pr�fen. Es gibt dann jedoch auch keine Garantie auf die Richtigkeit der Zuweisungen (Icon und Gr��e der Datei). Die Abfrage an den Server erfolgt auch dann, wenn diese Option deaktiviert wurde.

* **Relative URL?** -Normalerweise gibt man Dateien aus dem Netz mit dem Pr�fix 'http...' an, um intuitiv auf eine andere Webseite zu verweisen. Mit dieser Option deaktivieren Sie dies, dann werden URLs in Ihre eigene Joomla-Installation verweisen.

Die URLs werden mit einem Dateitypenicon und einem Pfeil (um zu zeigen dass es ein funktionierender Link ist) oder einem roten Querstrich (zeigt, dass die Existenz nicht verifiziert werden konnte) angezeigt. Sie k�nnen dies beim Bearbeiten der Datei �ndern. Au�erdem k�nnen Sie in der Option **�berlagern von URL Link-Icons** einen Standard definieren. Diverse andere URL-Funktionen finden Sie auch in den "Hilfsmitteln" Men�punkt im Backend.

Wo k�nnen Dateien angeh�ngt werden?
=============================================

Neben dem obligatorischen Artikelanhang k�nnen Dateien nun auch an andere Inhaltstypen wie Sektionen oder Kategorien (siehe auch weiter unten) geheftet werden. Wenn die zugeh�rigen 'Attachments' Plugins installiert sind, stehen weitere M�glichkeiten offen - zum Beispiel das Zuf�gen an Profile, Webshop-Produktbeschreibungen oder andere. Kurz gesagt: An jede Erweiterung, die den  ``'onPrepareContent'`` Event unterst�tzt (in der Regel all jene, die Textinhalte anzeigen), kann Anh�nge aus 'Attachments' anzeigen, wenn ein passendes Plugin installiert ist. 

Anh�ngen von Dateien oder URLs an Sektions- oder Kategoriebeschreibungen
-----------------------------------------------------------------------------

Neu in der aktuellen Attachments-Version ist diese Funktion, die Anh�nge an Sektionen oder Kategoriebeschreibungen heften kann. In der Regel sind die Beschreibungen nur im Blogschema ersichtlich, und wenn die Anzeige der Beschreibung auf *Anzeigen* (im Men�editor) eingestellt ist. Beachten Sie dass Sie die Anh�nge nur in der Komponente 'Anh�nge' oder im Frontend zuf�gen k�nnen, nicht in den Beschreibungsfeldern (im Editor) selbst.

Wenn Sie lernen m�chten, wie man ein Plugin f� 'Attachments' entwickelt, finden Sie eine englische Anleitung als Teil der Komponenteninstallation:

* `Attachments Plugin Creation Manual 
  <plugin_manual/html/index.html>`_ (in englisch)


CSS Anpassungen der Anhangs-Listen
==================================

Die Listen im Frontend nutzen f�r die Anzeige eine spezielle 'div'-Tabelle. Verschiedene CSS-Klassen sind vordefiniert, um ein passendes Layout zu erreichen. Beispiele und vorinstallierte Optionen finden Sie im Verzeichnis der Plugins (in plugins/content/attachments.css). 
Sie k�nnen auch eigene hinzu schreiben, die Sie im Joomla-Backend in der Komponente definieren - ersetzen Sie 'attachmentsList' mit Ihrer Klasse im 'Attachments'-Manager-Bereich. Sie finden diese Option unter *Anhangs-Tabellen-Stil*. Modifizieren Sie die Klassendefinitionen in der kopierten Sektion der CSS entsprechend Ihren W�nschen. Das Kopieren garantiert ein einfaches Zur�cksetzen, falls ben�tigt. Dies hat auch den Vorteil, dass die Sektion der kopierten Klasse bei einem Upgrade auf eine neuere Version bestehen bleibt. Alternativ k�nnen Sie zudem zum Importieren ein CSS @Import Kommando nutzen.

Dateityp-Icons
==============

Attachments' f�gt ein Icon am Anfang der Frontend-Liste jedes Anhangs ein. 
Wenn Sie ein anderes Icon nutzen wollen, gehen Sie wie folgt vor:
(1) Laden Sie ein zugeschnittenes Icon auf Ihren Server in den Ordner 'media/attachments/icons', �berschreiben Sie das dort liegende gegebenfalls;
(2) Editieren Sie Datei 'components/com_attachments/file_types.php' und f�gen Sie entsprechende Zeile in den statischen Array $attachments_icon_from_file_extension ein, der einer Dateiendung einen eindeutigen Icon-Namen zuweist (diese befinden sich allesamt in dem media/attachments/icons Server-Ordner. Gibt es Schwierigkeiten, m�ssen Sie eventuell noch den Mime-Typ via dem Array $attachments_icon_from_mime_type als separate Anweisung zuf�gen.
(3) Vergessen Sie nicht, vor dieser Modifizierung die Dateien und betroffenen Icons anderswo zu sichern, sp�testens bei einem Upgrade zu weiteren 'Attachments' Versionen der Zukunft sollten Sie dies tun.

�bersetzungen
==================

Diese Erweiterung unterst�tzt diverse �bersetzungen 
und ist bereits in folgenden Sprachen (neben englisch) erh�tlich. 
Bitte beachten Sie, dass die meisten der Sprachen noch im Update-Prozess f�r 'Attachments' befindlich sind.
Sollten Sie �bersetzungs-Pakete f�r Version 1.3.4 brauchen, kontaktieren Sie den Autor direkt.

Der Dank geb�hrt diesen �bersetzern: 

* **Bulgarisch:** by Stefan Ilivanov (being updated to 2.0)
* **Catalan:** by Jaume Jorba (2.0)
* **Chinesisch:** Traditional and simplified Chinese translations by baijianpeng (白建鹏) (being updated to 2.0)
* **Croatian:** Tanja Dragisic (1.3.4)
* **Czech:** by Tomas Udrzal (1.3.4)
* **Dutch:** by Parvus (2.0)
* **Finnish:** by Tapani Lehtonen (2.0)
* **French:** by Marc-André Ladouceur (2.0) and Pascal Adalian (1.3.4)
* **German:** by Bernhard Alois Gassner (2.0) Michael Scherer (1.3.4)
* **Greek:** by Harry Nakos (being updated to 2.0)
* **Hungarian:** Formal and informal translations by Szabolcs Gáspár (1.3.4)
* **Italian:** by Piero Mattirolo (2.0) and Lemminkainen and Alessandro Bianchi (1.3.4)
* **Norwegian:** by Roar Jystad (2.0) and Espen Gjelsvik (1.3.4)
* **Persian:** by Hossein Moradgholi and Mahmood Amintoosi (2.0)
* **Polish:** by Sebastian Konieczny (2.0) and Piotr Wójcik (1.3.4)
* **Portuguese (Brazilian):** by Arnaldo Giacomitti and Cauan Cabral (being updated to 2.0)
* **Portuguese (Portugal):** by José Paulo Tavares (2.0) and Bruno Moreira (1.3.4)
* **Romanian:** by Alex Cojocaru (2.0)
* **Russian:** by Sergey Litvintsev (2.0) and евгений панчев (Yarik Sharoiko) (1.3.4)
* **Serbian:** by Vlada Jerkovic (being updated to 2.0)
* **Slovak:** by Miroslav Bystriansky (1.3.4)
* **Slovenian:** by Matej Badalič (2.0)
* **Spanish:** by Manuel María Pérez Ayala (2.0) and Carlos Alfaro (1.3.4)
* **Swedish:** by Linda Maltanski (2.0) and Mats Elfström (1.3.4)
* **Turkish:** by Kaya Zeren (2.0)

Vielen Dank an die �bersetzer!  Wenn auch Sie helfen m�chten, 'Attachments' zu �bersetzen, kontaktieren Sie bitte den Autor (siehe `Kontakt`_ section am Ende des Files).

Warnungen
=========

* **Wenn Sie Dateien hochladen wollen, die sensible oder private Inhalte sind, nutzen Sie unbedingt die Optionen der *Sicheren Uploads*.** Nutzen Sie diese nicht, werden die Dateien in einem �ffentlich zug�nglichen Ordner abgelegt und sind jedem zug�nglich, der die volle URL kennt. Die *sichere* Variante sorgt daf�r, dass nur Nutzer mit ausreichenden Rechten Zugriff auf Dateien haben, sie Sie sie in den obigen Optionen definiert haben. Weitere Hinweise finden Sie in dem Abschnitt zu den *Sicheren Dateidownloads*.
* Bei jedem Upload wird der der Datei-Unterordner Joomlas! gecheckt und erstellt, wenn er nicht vorhanden ist. Standardm��ig ist dies 'attachments' im Wurzelverzeichnis Ihrer Installation. Der Ordnername kann in den Einstellungen ge�ndert werden.  Ist 'Attachments' nicht in der Lage, einen Unteordner zu erstellen, m�ssen Sie dies manuell tun. Hernach kann es zu Upload-Schwierigkeiten kommen, wenn die korrekten Berechtigungen nicht gesetzt sind - CHMOD muss dann auf 755 gesetzt sein. Beachten Sie bei Problemen, dass Ihr Stammordner von Direktiven des Providers / Hosters eingeschr�nkt sein kann und Einstellungen durchaus zu einem Verbot von Skriptausf�hrungen (und php) f�hren k�nnen. Sie m�ssen diese in einem solchen Fall tempor�r erlauben, damit Uploads mit 'Attachments' m�glich werden.
* Wenn die Erweiterung bestimmte Dateitypen nicht erfolgreich auf den Server l�dt (z.B. zip-Dateien), denken Sie daran, dass die Bestimmungen des Joomla!-Medienmanagers respektiert und nicht �berschrieben werden. Nur dort integrierte Mime-Typen und Dateiendungen sind f�r den Upload freigegeben, um potenziell gef�hrliche Dateien f�r einen Server fernzuhalten.  Der Administrator kann die erlaubten Dateitypen im Joomla!-Backend in den "Globalen Einstellungen" im System-Tab anpassen. F�gen Sie dort Dateiendung und den Mime-Typ ein.
* Sehen Sie Anh�nge im Frontend nicht, kann dies verschiedene Gr�nde haben:
     - Der Anhang ist nicht ver�ffentlicht. �ndern Sie dies direkt in der Komponente im Backend ab.
     - Der zugeh�rige Artikel oder anderer Inhalt ist nicht ver�ffentlicht.
     - Die Option 'Wer kann die Anh�nge sehen' ist eingeschalten, Sie sind jedoch nicht eingeloggt - oder die Datei ist in selber Option auf 'Niemand' eingestellt. Auch f�r diese �nderungen begeben Sie sich in die Komponentenparameter.
     - Das Plugin 'Content - Attachments' ist nicht freigegeben. Abhilfe schaffen Sie im Joomla!-Plugin-Manager.
     - Der Zugriffslevel im Plugin 'Content - Attachments' ist nicht auf '�ffentlich' geschalten.
* Wenn es Probleme mit der Gr��e der Dateien beim Upload gibt, liegt dies an den maximalen Uploadgenehmigungen des Servers. Nutzen Sie eine .htaccess-Datei im Wurzelverzeichnis Ihrer Installation, f�gen Sie folgende Zeilen ein, um die Beschr�nkungen hoch zu setzen:::

     php_value upload_max_filesize 32M
     php_value post_max_size 32M

  wobei Sie die 32M (Megabyte) zu jeder beliebigen Gr��e ab�ndern k�nnen.
* 'Attachments' unterst�tzt nun das Anh�ngen von URLs an Inhalte. Wenn Ihr Server auf Windows Vista l�ft, gibt es oft Probleme beim Anhang, der ``localhost`` inkludiert - ein bekanntes Problem in Zusammenhang mit IPv4 und IPv6 Konflikten.  Um das Problem zu beheben, editieren Sie die Datei::

       C:\Windows\System32\drivers\etc\hosts

  Kommentieren Sie die Zeile mit ``::1``  aus. Beachten Sie, dass  ``hosts`` eine versteckte Systemdatei ist. Gegebenfalls passen Sie bitte Ihre Ordnereinstellungen an, wenn Sie diese nicht sehen k�nnen.
* Wenn Sie Dateien via dem Editor anh�ngen, gibt es kein direktes Feedback, dass der Upload erfolgreich war, obgleich es funktioniert. Sie werden die Datei nach dem n�chsten Speichern sehen.
* 'Attachments' unterst�tzt nun den Upload w�hrend des Schreibens eines Artikels im Editor. Es gibt kein Limit. Neue Dateien befinden sich im Schwebezustand, solange der Artikel oder Inhalt nicht oder nicht neu nach dem Upload gespeichert wurde. In diesem (hoffentlich kurzem) Schwebezustand wird die Datei lediglich durch die Nutzer-ID identifiziert. Wenn also mehr als ein Nutzer diesen Account nutzt und zeitgleich Uploads durchgef�hrt werden, kann keine Garantie auf die korrekte Zuordnung zum Artikel gegeben sein. In der Regel werden Sie aber sicher keine doppelte Accountnutzung zulassen.
* Im Backend erscheint beim Ausf�hren eines der Kommandos manchmal eine Warnung, dass der Browser den Befehl erneut senden muss. Das ist harmlos, klicken Sie bedenkenlos auf [Ok] und das Kommando wird ausgef�hrt.
* Das Kommando "Systemdateinamen regenerieren" funktioniert f�r Migrationen von Win auf Linux Server. Es funktioniert ebenso f�r Migrationen von Linux auf Win-Server, mit Einschr�kungen: 

     - Beim Kopieren der Dateien auf Windows Server m�ssen Sie daf�r sorgen, dass der Attachments Ordner und all seine Dateien im einem beschreibbaren (durch den Server) Joomla-Verzeichnis liegen. 
     - Sie k�nnten Probleme beim Portieren von Unicode-Zeichen haben. Der Grund liegt in der Archivierungssoftware, die windowsseitig Unicode-Zeichen inkorrekt interpretiert. Sie sollten diese Dateien speichern, korrespondierende Anh�nge l�schen und neu anh�ngen.
* Es gibt ein Hilfeforum und ein 'Frequently Asked Questions' (Fragen und Anworten) Forum f�r die 'Attachments' Erweiterung auf der joomlacode.org Webseite.  Stellen Sie ein Problem fest, das in dieser Hilfsdatei nicht inkludiert ist, konsultieren Sie bitte die Foren:

     - `Attachments Forums at
       http://joomlacode.org/gf/project/attachments/forum/ 
       <http://joomlacode.org/gf/project/attachments/forum/>`_


Upgraden
========

Upgraden ist einfacher geworden. Installieren Sie einfach die neue Version von 'Attachments', auch wenn Sie bereits eine �ltere nutzen.

* *[Dieser Schritt ist optional aber sehr empfohlen - Legen Sie ein Backup der Attachments-Datenbank an f�r den Fall, dass irgendwelche Probleme auftauchen.]*
  Nutzen Sie  `phpMyAdmin <http://www.phpmyadmin.net/home_page/index.php>`_
  oder ein anderes SQL Tool) um die Inhalte der Tabelle 'jos_attachments' zu exportieren. Nutzen Sie hierbei die 'Export' Funktion mit kompletten Inserts (nicht erweitert). Die hochgeladenen Dateien ebenfalls zu sichern, kann manchen �rger ersparen.
* **Sie brauchen die vorherige Version von Attachments nicht zu deinstallieren.** Dieser Vorgang wurde mit Versionen 2.0 und 1.3.4, nicht mit fr�heren, umfangreich getestet.
* Sie brauchen nichts zu tun, um ihre Dateianh�nge zu bewahren, installieren Sie einfach die neue Komponente - alles bleibt erhalten.
* Wenn Sie Ihre Dateien nicht behalten m�chten, deinstallieren Sie diese bitte vor der Neuinstallation via dem Joomla-Backend.
* Der Multi-Installer wird alle notwendigen Komponenten und Plugins aktualisieren und zugleich aktivieren. Deaktivieren Sie unerw�nschte hernach via dem Plugin-Manager im Joomla-Backend. Gibt es Probleme, sollten Sie eine manuelle Installation der Plugins etc. der Reihe nach durchf�hren. In der INSTALL Datei in der Haupt-zip findet sich die korrekte die Ordnerstruktur wieder. 


Danksagungen
============

Gro�er Dank geht an folgende Mitwirkende und Ressourcen:

* Das Buch *Learning Joomla! 1.5 Extension Development: Creating Modules,
  Components, and Plugins with PHP* von Joseph L. LeBlanc war sehr hilfreich beim Erstellen der 'Attachments' Erweiterungen.
* Die Icons der Dateitypen wurden aus verschiedenen Quellen zusammengetragen, inklusive:
    - `The Silk icons by Mark James (http://www.famfamfam.com/lab/icons/silk/) <http://www.famfamfam.com/lab/icons/silk/>`_
    - `File-Type Icons 1.2 by John Zaitseff (http://www.zap.org.au/documents/icons/file-icons/sample.html) <http://www.zap.org.au/documents/icons/file-icons/sample.html>`_
    - `Doctype Icons 2 by Timothy Groves (http://www.brandspankingnew.net/archive/2006/06/doctype_icons_2.html) <http://www.brandspankingnew.net/archive/2006/06/doctype_icons_2.html>`_
    - `OpenDocument icons by Ken Baron (http://eis.bris.ac.uk/~cckhrb/webdev/) <http://eis.bris.ac.uk/~cckhrb/webdev/>`_
    - `Sweeties Base Pack by Joseph North (http://sweetie.sublink.ca) <http://sweetie.sublink.ca>`_

  Viele Icons von den Originalquellen wurden f�r 'Attachments' modifiziert. Die Originale k�nnen Sie auf den gelisteten Webseiten herunter laden.
* Vielen Dank an Paul McDermott f�r die gro�z�gige Spende des Suchplugins!
* Ein Dank geht auch an Mohammad Samini f�r das Zusteuern einigen PHP-Codes und CSS-Dateien, um die Leserichtung rechts-nach-links zu verbessern.
* Danke auch an Florian Tobias Huber, der die Ansichten optimierte, wenn die Cache-Funktionen laufen. 
* Danke an Manuel María Pérez Ayala f�r die Anst��e zum Entwickeln des integrierten Multi-Installers, der die Joomla-API nutzt und automatisch die Komponente und alle Plugins in einem Schritt installiert. Ich verstand es so, dass die Technik im Original von JFusion stammt.
* Danke, Ewout Weirda, f�r viele helfende Diskussionen und Empfehlungen bei der Entwicklung der 'Attachments' Komponente.

Kontakt
=======

Bitte melden Sie Fehler und schreiben Sie Empfehlungen an  `jmcameron@jmcameron.net <mailto:jmcameron@jmcameron.net>`_ (in englisch).
