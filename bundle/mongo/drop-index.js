db.http_service.dropIndex( { "time": 1 }, { name:"uin_generate", partialFilterExpression: { "address":{$eq:"uin/generate"} }, expireAfterSeconds: 604800 } )
