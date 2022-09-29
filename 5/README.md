# backend:
<br />
cd 5/api/server
<br />
php -S localhost:8001
<br /><br />

# frontend:
<br />
cd 5/frontend
<br />
npm install
<br />
npm run build
<br /><br />

cd 5/frontend/dist
<br />
php -S localhost:8002
<br /><br />

open http://localhost:8001/?path=parse
<br />
open http://localhost:8002
