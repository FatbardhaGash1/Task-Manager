# Task-Manager
Task Manager është një aplikacion web për menaxhimin e detyrave ditore, i zhvilluar me PHP  dhe CSS modern.Aplikacioni përdor sessionet dhe cookies për ruajtjen e të dhënave dhe preferencave të përdoruesit.

----------Karakteristikat Kryesore---------- 

- Shto, Fshij, Ndrysho statusin e detyrave (CRUD operations)
- Prioritetet e detyrave (Urgent, High, Medium, Low)
- Statistika të detajuara në Dashboard
- Light / Dark mode - zgjedhja e temës ruhet në cookie
- Validimi i të dhënave me RegEx (email, titulli i detyrës)
- Sistemi i roleve (Admin dhe User)
- Dizajn modern responsive - punon në PC, tablet, telefon
- Animacione dhe hover efekte
- Ruajtja e të dhënave në PHP session

-----Udhëzimet për Instalim dhe Ekzekutim-----
1. Kërkesat
PHP 7.4 ose më i ri
Web server (XAMPP / WAMP / MAMP)

2. Kopjo folderin në direktorinë e serverit
-Për XAMPP:
C:\xampp\htdocs\Task-Manager\

3. Hapat për Ekzekutim
Starto serverin (Apache në XAMPP/WAMP/MAMP)
Hap shfletuesin dhe shko te: http://localhost/Task-Manager/

4. Eksploro faqet:
Ballina (index.php)
Dashboard (dashboard.php) - Statistika
Detyrat (tasks.php) - CRUD operacione
Profili (profile.php) - Ndrysho temën

-----Testimi i Funksionaliteteve-----
-Testimi i Login/Logout
Kyçu me admin@example.com / admin123
Kyçu me user@example.com / user123
Shko te dashboard - roli ndryshon
Kliko "Dil" për logout

-Testimi i Roleve
Admin - sheh të gjitha detyrat
User - sheh vetëm detyrat e veta

-Testimi i RegEx
Në login.php - email jo valid (pa @ ose .)
Në tasks.php - titull me më pak se 3 karaktere

-Testimi i OOP
User.php - klasa User dhe AdminUser me trashëgimi
Task.php - klasa Task dhe ImportantTask me trashëgimi

-Testimi i Sessions & Cookies
Pas login - sessioni krijon user_id, user_role, user_name
Në profile - ndrysho temën (cookie ruhet për 30 ditë)
Cookie last_user ruan përdoruesin e fundit

Aplikacion NUK përdor databazë momentalisht. Të gjitha të dhënat ruhen në session dhe humbin kur mbyllet shfletuesi.