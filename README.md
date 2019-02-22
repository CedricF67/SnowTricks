# SnowTricks

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/55430ebdfff4439085990ff86deae032)](https://app.codacy.com/app/CedricF67/SnowTricks?utm_source=github.com&utm_medium=referral&utm_content=CedricF67/SnowTricks&utm_campaign=Badge_Grade_Dashboard)

A website for snowboard  tricks

## How to install
1) Download the project.
2) Edit the '.env' file to specify the database you will be using (line 27), as well as the mailer (line 34).
3) Upload all files to your server.
4) In a terminal, type the following command to create the database : php bin/console doctrine:database:create
5) If you want to set up the demo fixtures, run the following command as well : php bin/console doctrine:fixtures:load

## How to login
If you have set up the demo fixtures, you can log in already with 3 users : Cedric, Magalie and Pierre. The password is the same for all 3 : password

If you haven't set the fixtures, you can create an user by clicking the 'S'enregistrer' link on the top right of the screen.
