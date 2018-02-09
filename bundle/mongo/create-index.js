//db.http_service.getIndexes(); - просмотреть все индексы
db.http_service.createIndex( { "time": 1 }, { name:"uin_generate", partialFilterExpression: { "address":{$eq:"uin/generate"} }, expireAfterSeconds: 604800 } )