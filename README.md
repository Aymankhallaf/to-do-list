About The Project

Welcome to salle d'escalade v.1 test crud asyncrone
website salle d'escalade to reserve a seance.

first after fork the project you need to install a local host you could use docker
## BUILD AND RUN

To build images and run all containers and volumes
```sh
docker-compose up -d
```
## install the database
there are an example of database in folder sql-example.
you could import it.
and connect to it by change parameters in .env file.
## dont forget to run vite
if you aren't in app folder
write 
```sh
cd app
```
then 
```sh
npm run dev.

## Read
from home page go to reservation page.
it will fetch the data in asyn to get the reservation details
![Capture d’écran 2024-07-31 004054](https://github.com/user-attachments/assets/090b60a0-3519-448c-b27d-8e1a5df31732)

## write
it would write the reservation data in database and show it.
After choosing a reservation details and press confirm
![Capture d’écran 2024-07-31 005400](https://github.com/user-attachments/assets/8ad5c47a-dd46-4423-ac0e-ecf79ec47521)

## Update
Pressing modifier, will premet to edit the reservation (update the reservation in  database) 

## Delete
Pressing annuler, will premet to delete the reservation (delete the reservation in  database) 



