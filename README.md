![kolabee](https://kolabee.com/wp-content/uploads/2020/12/Kolabee-Logotype.png)

* Détail du projet dans WIKI 
* Systéme de gestion de BDD Mariadb 10.3.34

# Projet kolabee By SII

Pour lancer le projet il faut le clonner depuis le depot git.

* Se déplacer dans le projet depuis le terminal et lancer la commande :
    
        composer install

* Créer le fichier .env.local à la racine (copie du .env) et corriger la ligne DATABASE_URL. Penser à mettre à jour la version de mysql :
ex : 

        DATABASE_URL="mysql://sc1cenacle_tkkd_symfony:change-me@127.0.0.1:3306/sc1cenacle_tkkd_symfony?serverVersion=10.3.34-MariaDB"
    
* Puis : ouvrir le dossier avec votre IDE ensuit dans le terminal lancer la commande suivante pour créer la BDD
            
        php bin/console doctrine:database:create

* Ensuite lancer la migration avec doctrine :

         php bin/console make:migration
         php bin/console doctrine:migrations:migrate
et vérifier que la BDD a bien été créée et que les tables contiennent des enregistrements.

* Enfin, lancer les commandes suivantes (attention : il faut **node v14** ou +) :

        npm install
        npm run build

Toujours depuis le terminal lancer la commande suivante pour démarrer le projet :

        symfony server:start

PS : pour le projet je n'utilise pas des logiciels du type **WampServer** je travaille qu'en ligne de commande c'est  
plus rapides efficace et cela vous évite d'installer des logiciels sur votre poste pour une question de sécurité ;  
mais c'est à titre personnel.



Troubleshooting

        Package fzaninotto/faker is abandoned, you should avoid using it
Ce package est déprécié mais ne sert qu'à générer des jeux de données, le supprimer ou le passer en dépendance de dev

        Environment variable not found: "APP_AUTHOR".  
S'il manque une variable d'environnement, la définir dans .env : APP_AUTHOR=TOTO

        The metadata storage is not up to date, please run the sync-metadata-storage command to fix this issue
Vérifier l'url de la base de données dans le .env, si besoin redéfinir cette url dans un fichier .env.local qui va surcharger les valeurs du .env
Ne pas oublier de mettre le .env.local dans le .gitignore

        An exception has been thrown during the rendering of a template ("Could not find the entrypoints file from Webpack
Penser à faire le npm install + npm run build