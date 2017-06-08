TO RUN THIS PROJECT:

To run this test you just have to install the Docker and Docker Compose. After the installation you just have to make sure that
the folder "/var" of the project have read and write permission. After that you have to run these console commands inside
the project folder (in that order):

docker-compose build
docker-compose up -d

After that you just go to your web browser and set the url to:
http://localhost:8082/

Please don't forget to try the HARD level :)


=================================================================================================================================

About the test:
My approach on this project was to create the game logic on a separated package to make them really reusable.
So i've created all the logic on the TicTacToeGame folder and he is totally decoupled of the rest of the code.

The back-end logic was made using the symfony framework through the two controllers that i've wrote to this application.
The DefaultController is for the game front-end and the ApiController is for the api calls. And of course we have all the
javascript to create the game. As it was requested all the logic is inside the PHP and the javascript have to ask to the
api what's the next move and the status of the game.

So we have two difficulty levels on the game that corresponds to the two AI classes. One of these AI generates a simple
random response (easy game) and the other one uses the minimax(hard game) algorithm to decides the next moves of the CPU.