# Open Loyalty

Open Loyalty is a headless loyalty technology for visionaries and innovators who want to implement an effective loyalty program that cuts across multiple touchpoints. Our solution is 100% API-first and unlike old-fashioned, monolithic software, it’s easy to integrate with and ensures complete flexibility, speed, and effectiveness at all levels. 

## Screenshots

![Admin Cockpit](https://user-images.githubusercontent.com/3582562/54033263-1db79500-41b4-11e9-8f2d-9b91acce50cf.png)

## Url access

After starting Open Loyalty, it exposes services under the following URLs:

 * http://openloyalty.localhost:8182 - the administration panel,
 * http://openloyalty.localhost:8183 - the customer panel,
 * http://openloyalty.localhost - RESTful API port
 * http://openloyalty.localhost/doc - swagger-like API doc
 

## Quick install


```
./docker/base/build_dev.sh
```

and run containers:

```
docker-compose -f docker/docker-compose.dev.yml up
```

Remember to setup database using bellow command:

```
docker-compose -f docker/docker-compose.dev.yml exec --user=www-data php phing setup
```

After starting Open Loyalty in developer mode, it exposes services under slightly different URLs:

 * http://openloyalty.localhost:8081/admin - the administration panel,
 * http://openloyalty.localhost:8081/client - the customer panel,
 * http://openloyalty.localhost:8081/pos - the merchant panel,
 * http://openloyalty.localhost - RESTful API port
 * http://openloyalty.localhost/app_dev.php/doc - swagger-like API doc

## Documentation

Technical documentation of Open Loyalty is located [here](backend/doc/index.rst). 

