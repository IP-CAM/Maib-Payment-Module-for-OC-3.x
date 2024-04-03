[![N|Solid](https://www.maib.md/images/logo.svg)](https://www.maib.md)

# Maib Payment Gateway pentru Opencart v. 3.x
Acest modul vă permite să integrați magazinul dvs. online cu noul **API e-commerce** de la **maib** pentru a accepta plăți online (Visa / Mastercard / Google Pay / Apple Pay).

## Descriere
Cu etapele de integrare și cerințele către website puteți face cunoștință [aici](https://docs.maibmerchants.md/ro/etape-si-cerinte-pentru-integrare).

Pentru a testa integrarea veți avea nevoie de datele de acces a unui Proiect de Test (Project ID / Proejct Secret / Signature Key). Pentru aceasta vă rugăm să veniți cu o solicitare la adresa de email: ecom@maib.md.

Pentru a efectua plăți reale, trebuie să aveți contractul e-commerce semnat și să efectuați cel puțin o tranzacție reușită folosind datele Proiectului de Test și datele cardului pentru teste. 

După semnarea contractului veți primi acces la platforma maibmerchants și veți avea posibilitatea să activați Proiectul de Producție.

### Pași pentru activarea Proiectului de Producție
1. Profil completat pe platforma maibmerchants
2. Profil validat
3. Contract e-commerce

## Funcțional
**Plăți online**: Visa / Mastercard / Apple Pay / Google Pay.

**Trei valute**: MDL / USD / EUR (în dependență de setările Proiectului dvs).

**Returnare plată**: Pentru a returna plata, este necesar să actualizați starea comenzii (vedeți _refund.png_) la starea selectată pentru _Plată returnată_ în setările extensiei **maib** (vedeți _settings.png_). Suma plății va fi returnată pe cardul clientului.

## Cerințe 
- Înregistrare pe platforma maibmerchants.md
- Opencart v. 3.x
- extensiile _curl_ and _json_ activate

## Instalare
1. Descărcați modulul de pe Github sau din repozitoriu Opencart (_maib3.ocmod.zip_).
2. În panoul de administrare accesați _Extensii > Instalator Extensii_.
3. Apăsați butonul _Încarcă_ și selectați modulul descărcat. Odată ce încărcarea este finalizată, OpenCart va începe procesul de instalare.
4. Accesați _Extensii > Modificări_ și apăsați butonul _Reîmprospătează_.
5. Accesați _Extensii > Extensii_ și alegeți tipul de extensie _Metode de plată_. You should see **maib** extension in the list.
6. Alegeți extensia **maib** din listă și apăsați butonul _Instalează_.
7. Apăsați butonul Editează pentru setarea extensiei.

## Setări
1. Titlu - titlul metodei de plată afișată clientului pe pagina de finalizare a comenzii.
2. Stare - activare/dezactivare extensie.
3. Depanare - activare/dezactivare log-urile extensiei în fișierul _maib.log_.
4. Ordinea de sortare - ordinea de sortare a metodei de plată pe pagina de finalizare a comenzii.
5. Zona geografică - selectați regiunile geografice pentru care se va aplica metoda de plată.
6. Project ID - Project ID din maibmerchants.md
7. Project Secret - Project Secret din maibmerchants.md. Este disponibil după activarea proiectului.
8. Signature Key - Signature Key pentru validarea notificărilor pe Callback URL. Este disponibil după activarea proiectului.
9. Ok URL / Fail URL / Callback URL - adăugați aceste link-uri în câmpurile respective ale setărilor Proiectului în maibmerchants.
10. Plată în așteptare - Starea comenzii când plata este în așteptare.
11. Plată cu succes - Starea comenzii când plata este finalizată cu succes.
12. Plată eșuată - Starea comenzii când plata a eșuat.
13. Platã returnatã - Starea comenzii când plata este returnată. Pentru returnarea plății, actualizați starea comenzii la starea selectată aici (vedeți _refund.png_).

## Depanare
Activați depanarea în setările extensiei și accesați fișierul cu log-uri.

Dacă aveți nevoie de asistență suplimentară, vă rugăm să nu ezitați să contactați echipa de asistență ecommerce **maib**, expediind un e-mail la ecom@maib.md.

În e-mailul dvs., asigurați-vă că includeți următoarele informații:
- Numele comerciantului
- Project ID
- Data și ora tranzacției cu erori
- Erori din fișierul cu log-uri
