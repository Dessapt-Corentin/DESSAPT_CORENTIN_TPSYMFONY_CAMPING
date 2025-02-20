# DESSAPT_CORENTIN_TPSYMFONY_CAMPING
Partie Sympfony

Projet de création d'un site de location pour un camping.

Récipe BACK-END :
-PHP.

Framework:
Symfony.

FRONT-END:
Html.twig
Scss.
JavaScript.

Lancement du projet :
Se placer à la racine du projet dans un terminal.
executer "docker compose up" (-d si l'on veut le détacher) pour lancer la construction.

SI LE PROJET N'A PAS ETE CONFIGURE

METHODO
Après avoir lancé le docker, Faire :

ccomposer install
ccomposer create-project symfony/skeleton:"7.3.x-dev" ./ si le www est entièrement vide
ccomposer require symfony/webpack-encore-bundle

DANS nnpm :
nnpm (rentrer dans le container)
npm install
npm i bootstrap
npm install sass-loader node-sass --save-dev
npm run build

puis on lance npm run watch

POUR REMETTRE A ZERO LA BASE :
cconsole d:d:d --force
cconsole d:d:c
cconsole d:m:m
cconsole d:f:l

CREDENTIALS :
database : symfony_camping_dc
username : admin 
mdp : admin 
port : 80 et 3306

FONCTIONNE AVEC DESSAPT_CORENTIN_TPSYMFONY_CAMPING_JS
