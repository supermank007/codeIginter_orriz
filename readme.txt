Before start:
1 create .env.php from .env.php.example.
2 create .htaccess from .htaccess.example.
3 the content of node/.nodejs_url file must be equal to BASE_URL from step 1.
4 run nodejs node/app.js (make sure nodejs 4 or newer installed).
	4.1 This task can be automated by following steps (also this MUST be done in production):
	- install pm2 (npm install pm2@latest -g)
	- start app.js (pm2 start /PATH_TO_PROJECT/node/app.js)
	- generate pm2 command (pm2 startup)
	- execute generated command from previous step
	- save current started app to pm2 autostart list (pm2 save)