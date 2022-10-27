# notification-service

```
docker-compose up --build
```

## DB
```
php console/create_users_table.php
php console/users_faker.php
```

### check send mail subscription flow
```
run console/check_subscription_cron.php  - (from browser or console) for send messages to rabbit
```
### check mail valid flow
```
run localhost:8088/http/register.php
```
