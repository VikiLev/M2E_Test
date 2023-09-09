M2E Test
=====================

### VERSION 1.0.0

### Description

bin/start: start work with the project
bin/stop: Stop all project containers.

Import data
bin/magento m2e:import app/code/M2E/Test/data.csv
bin/magento m2e:import app/code/M2E/Test/data.xml

Api
https://magento.test/rest/V1/test/find

Api with parameters
https://magento.test/rest/V1/test/find?ship_to_name=Legolas
https://magento.test/rest/V1/test/find?limit=3&status=new

