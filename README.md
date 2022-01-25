![kolabee](https://kolabee.com/wp-content/uploads/2020/12/Kolabee-Logotype.png)

# Projet kolabee By SII

Pour lancer le projet il faut le clonner depuis le depot git.

* Se déplacer dans le projet depuis le terminal et lancer la commande :
    
        composer install et composer update

* Puis : ouvrir le dossier avec votre IDE ensuit dans le terminal lancer la commande suivante pour créer la BDD
            
              php bin/console doctrine:database:create
* Ensuite lancer la migration avec doctrine :

         php bin/console make:migration
         php bin/console doctrine:migrations:migrate
et vérifier que la BDD a bien été créée et que les tables contiennent des enregistrements.

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