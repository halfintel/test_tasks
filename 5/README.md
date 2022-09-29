# start

backend:
cd 5/api/server
php -S localhost:8001

frontend:
cd 5/frontend
npm install
npm run build

cd 5/frontend/dist
php -S localhost:8002


open http://localhost:8001/?path=parse
open http://localhost:8002
